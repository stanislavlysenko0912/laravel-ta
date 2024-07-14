<?php

namespace Database\Seeders\Versioned\V1;

use App\Database\Seeders\VersionedSeeder;
use App\Models\Position;
use App\Models\User;

class UserSeederV1 extends VersionedSeeder
{
    public function run(): void
    {
        $positionIds = Position::pluck('id')->toArray();

        User::factory()
            ->count(45)
            ->state(function () use ($positionIds) {
                return [
                    'position_id' => $positionIds[array_rand($positionIds)],
                ];
            })
            ->create();
    }

    protected function getVersion(): int
    {
        return 1;
    }
}