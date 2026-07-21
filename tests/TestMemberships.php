<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Memberships;

class TestMemberships extends TestCase
{
    private $memberships;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->memberships = new Memberships($this->mockPDO);
    }

    public function testGetMemberships()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM memberships')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->memberships->getMemberships($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetMembershipById()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM memberships WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->memberships->getMembershipById($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateMembership()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Membership']);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO memberships (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->memberships->createMembership($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testUpdateMembership()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Test Membership']);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE memberships SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->memberships->updateMembership($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testDeleteMembership()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM memberships WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->memberships->deleteMembership($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}