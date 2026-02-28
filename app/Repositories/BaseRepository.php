<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use SuperFrameworkEngine\App\UtilORM\ORM;

abstract class BaseRepository implements RepositoryInterface
{
    protected ORM $db;
    protected string $table;

    public function __construct(ORM $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        return $this->db->db($this->table)->all();
    }

    public function find(int $id): ?array
    {
        return $this->db->db($this->table)->find($id);
    }

    public function create(array $data): string
    {
        return $this->db->db($this->table)->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->db($this->table)->where('id = ?', [$id])->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->db->db($this->table)->delete($id);
    }
}
