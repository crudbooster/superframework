<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RedisService;
use Predis\Client;
use PHPUnit\Framework\MockObject\MockObject;

class RedisServiceTest extends TestCase
{
    private RedisService $service;
    private MockObject|Client $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockClient = $this->getMockBuilder(Client::class)
            ->addMethods(['get', 'setex', 'incr', 'expire'])
            ->getMock();
            
        $this->service = new RedisService();
        
        // Inject mock client
        $reflection = new \ReflectionClass($this->service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->service, $this->mockClient);
    }

    public function test_get_unserializes_value()
    {
        $data = ['foo' => 'bar'];
        $this->mockClient->expects($this->once())
            ->method('get')
            ->with('test_key')
            ->willReturn(serialize($data));

        $result = $this->service->get('test_key');
        $this->assertEquals($data, $result);
    }

    public function test_set_serializes_value()
    {
        $data = ['foo' => 'bar'];
        $this->mockClient->expects($this->once())
            ->method('setex')
            ->with('test_key', 3600, serialize($data));

        $this->service->set('test_key', $data);
    }

    public function test_incr_returns_value()
    {
        $this->mockClient->expects($this->once())
            ->method('incr')
            ->with('test_key')
            ->willReturn(5);

        $result = $this->service->incr('test_key');
        $this->assertEquals(5, $result);
    }

    public function test_expire_sets_ttl()
    {
        $this->mockClient->expects($this->once())
            ->method('expire')
            ->with('test_key', 60);

        $this->service->expire('test_key', 60);
    }
}
