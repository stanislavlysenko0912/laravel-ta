<?php

namespace App\Http\Controllers\V1\User;

use App\Exceptions\MessageException;
use App\Http\Controllers\V1\V1Controller;
use App\Http\Requests\V1\User\UsersListRequestV1;
use App\Http\Requests\V1\User\UserStoreRequestV1;
use App\Http\Resources\V1\User\UserResourceV1;
use App\Http\Responses\CustomPaginatedResponse;
use App\Models\User;
use App\Services\ImageOptimizer\ImageOptimizerService;
use OpenApi\Attributes as OA;
use OpenAPI\Operation\ApiGet;
use OpenAPI\Operation\ApiPost;
use OpenAPI\Request\QueryParameters;
use OpenAPI\Request\RequestForm;
use OpenAPI\Responses\ResponseError;
use OpenAPI\Responses\ResponseJsonSuccess;
use Storage;
use Str;


class UserController extends V1Controller
{
    #[ApiGet(
        path: '/users',
        summary: 'Get list of users',
        tags: ['users'],
        description: "**Returns users data from a database divided into pages and sorted by ID in the ascending order.**
 - You can specify the parameters such as count and page, which correspond to the number of users on the page, missing record number and page number.
 - To navigate through the pages, you can use the links in the server's response: next_link to go to the next page and prev_link to return to the previous page.
 - If the next or previous page does not exist, the next_link/prev_link parameter will be set to null",
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Specify the page that you want to retrieve',
                in: 'query',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'count',
                description: 'Specify the number of users on the page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
        ],
    )]
    #[ResponseJsonSuccess(UserResourceV1::class, paginated: 'users')]
    public function index(UsersListRequestV1 $request)
    {
        $perPage = $request->input('count');
        $page = $request->input('page');

        $users = User::with('position')->paginate($perPage, ['*'], 'page', $page);

        return new CustomPaginatedResponse($users, UserResourceV1::class, 'users');
    }

    #[ApiGet(
        path: '/users/{id}',
        summary: 'Get user by id',
        tags: ['users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id of the user to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ]
    )]
    #[ResponseJsonSuccess(UserResourceV1::class, wrap: 'user')]
    #[ResponseError(404, 'No user found')]
    /** @throws MessageException */
    public function show(int $id): UserResourceV1
    {
        $user = User::with('position')->find($id);

        if (!$user) {
            throw new MessageException('No user found', 404);
        }

        return UserResourceV1::make($user);
    }

    /**
     * @throws MessageException
     */
    #[ApiPost(
        path: '/users',
        summary: 'Create a new user',
        tags: ['users'],
        description: "**User registration request.**

All fields are *required*:
- name - user name, should be 2-60 characters
- email - user email, must be a valid email according to RFC2822
- phone - user phone number, should start with code of Ukraine +380
- position_id - user position id. You can get list of all positions with their IDs using the API method GET api/v1/positions
- photo - user photo should be jpg/jpeg image, with resolution at least 70x70px and size must not exceed 5MB.",
        auth: true,
        requestClass: UserStoreRequestV1::class
    )]
    #[ResponseJsonSuccess(UserResourceV1::class, response: 201, wrap: 'user')]
    public function store(UserStoreRequestV1 $request, ImageOptimizerService $imageOptimizer): UserResourceV1
    {
        $data = $request->validated();

        $file = $request->file('photo');
        $extension = $file->extension();

        $optimizedImageData = $imageOptimizer->optimize(file_get_contents($file->getPathname()));

        if (is_null($optimizedImageData)) {
            throw new MessageException('Image optimization failed, try later', 500);
        }

        $filename = md5(Str::random(10) . time()) . '.' . $extension;
        $path = 'images/users/' . $filename;
        Storage::disk('public')->put($path, $optimizedImageData);

        $data['photo'] = $path;


        $user = User::create($data);

        // Line bellow can be evaded. Need set timestamp on model creation (not in db)
        $user->refresh();

        return UserResourceV1::make($user);
    }
}
