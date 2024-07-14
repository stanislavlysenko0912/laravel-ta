## Used technologies
- Laravel 11
- PHP 8.2
- PostgreSQL 16
- Docker && Docker Compose

### Used libs
| Library                       | Usage                |
|-------------------------------|----------------------|
| `lcobucci/jwt`                | JWT token generation |
| `spatie/laravel-ray`          | Debugging tool       |
| `darkaonline/l5-swagger`      | API documentation    |
| `intervention/image`          | Image manipulation   |
| `barryvdh/laravel-ide-helper` | Typehint Helper*     |


Used [OpenApiAttributes](https://github.com/butthurtdeveloper/OpenAPIAttributes) by [butthurtdeveloper](https://github.com/butthurtdeveloper) for API documentation.<br>
*Some changed, not perfect but for current needs it's enough.*

#### Services that need api keys
- [TinyPng](https://tinypng.com/)
- [APILayer](https://apilayer.com/marketplace/image_optimizer-api) (optional)

#### Usefully commands
| Command                            | Description                        |
|------------------------------------|------------------------------------|
| `php artisan migrate:fresh --seed` | Refresh database and seed data     |
| `php artisan ide-helper:generate`  | Generate IDE Helper file           |
| `php artisan ide-helper:meta`      | Generate meta file for IDE Helper  |
| `php artisan ide-helper:eloquent`  | Generate Eloquent model helper     |
| `php artisan ide-helper:models -M` | Generate models helper             |
| `php artisan l5-swagger:generate`  | Generate Swagger API documentation |

Full ide-helper command
```bash
php artisan ide-helper:generate && php artisan ide-helper:meta && php artisan ide-helper:eloquent && php artisan ide-helper:models -M
```

