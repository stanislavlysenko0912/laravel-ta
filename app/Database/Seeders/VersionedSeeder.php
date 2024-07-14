<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class VersionedSeeder extends Seeder
{
    public function __invoke(array $parameters = []): void
    {
        if (!$this->hasRun()) {
            $this->run();
            $this->markAsRun();
        }
    }

    protected function hasRun(): bool
    {
        return DB::table('seeders')
            ->where('seeder', static::class)
            ->where('batch', $this->getVersion())
            ->exists();
    }

    abstract protected function getVersion(): int;

    abstract public function run(): void;

    protected function markAsRun(): void
    {
        DB::table('seeders')->insert([
            'seeder' => static::class,
            'batch' => $this->getVersion(),
            'run_at' => now(),
        ]);
    }
}