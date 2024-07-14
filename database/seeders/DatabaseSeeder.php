<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Versioned\V1\PositionSeederV1;
use Database\Seeders\Versioned\V1\UserSeederV1;
use Database\Seeders\Versioned\V1\UserSeederV2;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            $this->seedLocal();
        }
    }

    private function seedLocal(): void
    {
        $this->call([
            PositionSeederV1::class,
            UserSeederV1::class,
        ]);
    }
}
