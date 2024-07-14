<?php

namespace Database\Seeders\Versioned\V1;

use App\Database\Seeders\VersionedSeeder;
use App\Models\Position;

class PositionSeederV1 extends VersionedSeeder
{
    public function run(): void
    {
        $positions = [
            'lawyer',
            'Content manager',
            'Security',
            'Designer'
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position
            ]);
        }
    }

    protected function getVersion(): int
    {
        return 1;
    }
}