<?php

namespace Tests\Unit;

use App\Repositories\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use SuperFrameworkEngine\App\UtilORM\ORM;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $repository;
    private MockObject|ORM $mockORM;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockORM = $this->createMock(ORM::class);
        $this->repository = new UserRepository($this->mockORM);
    }

    public function test_all_returns_array_from_orm()
    {
        $expectedData = [['id' => 1, 'name' => 'John Doe']];

        $this->mockORM->expects($this->once())
            ->method('db')
            ->with('users')
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('all')
            ->willReturn($expectedData);

        $result = $this->repository->all();
        $this->assertEquals($expectedData, $result, "Repository all() should return data from ORM");
    }

    public function test_find_returns_null_when_not_found()
    {
        $this->mockORM->expects($this->once())
            ->method('db')
            ->with('users')
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $result = $this->repository->find(1);
        $this->assertNull($result, "Find should return null for non-existent user");
    }

    public function test_create_returns_inserted_id()
    {
        $data = ['name' => 'New User'];

        $this->mockORM->expects($this->once())
            ->method('db')
            ->with('users')
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('insert')
            ->with($data)
            ->willReturn('1');

        $result = $this->repository->create($data);
        $this->assertEquals('1', $result, "Create should return the inserted ID");
    }

    public function test_update_returns_true_on_success()
    {
        $id = 1;
        $data = ['name' => 'Updated User'];

        $this->mockORM->expects($this->once())
            ->method('db')
            ->with('users')
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('where')
            ->with('id = ?', [$id])
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('update')
            ->with($data)
            ->willReturn(true);

        $result = $this->repository->update($id, $data);
        $this->assertTrue($result, "Update should return true on success");
    }

    public function test_delete_returns_true_on_success()
    {
        $id = 1;

        $this->mockORM->expects($this->once())
            ->method('db')
            ->with('users')
            ->willReturn($this->mockORM);

        $this->mockORM->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);

        $result = $this->repository->delete($id);
        $this->assertTrue($result, "Delete should return true on success");
    }
}
