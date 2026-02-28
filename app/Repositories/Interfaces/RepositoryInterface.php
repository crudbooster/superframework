<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): string;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
