<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase as BaseTestCase;
use SuperFrameworkEngine\App\UtilORM\ORM;
use SuperFrameworkEngine\Foundation\Container;
use SuperFrameworkEngine\Super;

abstract class TestCase extends BaseTestCase
{
    protected Container $app;
    protected ORM $db;

    protected function setUp(): void
    {
        parent::setUp();

        if (!defined('BASE_PATH')) {
            define('BASE_PATH', dirname(__DIR__));
        }

        $this->app = Container::getInstance();

        // Ensure bootstrapper runs
        new Super();

        // Manually run AppServiceProvider for tests
        (new \App\Providers\AppServiceProvider())->run();

        // Setup test database (SQLite in memory)
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create necessary tables for tests
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255),
            email VARCHAR(255),
            password VARCHAR(255),
            created_at TIMESTAMP,
            updated_at TIMESTAMP
        )");

        $this->db = new ORM($pdo);
        $this->app->singleton(ORM::class, fn () => $this->db);
    }
}
