<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Payments;

class TestPayments extends TestCase
{
    private $payments;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->payments = new Payments($this->pdo);
    }

    public function testGetPayments()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'amount' => 10.99],
                ['id' => 2, 'amount' => 5.99],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM payments'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->getPayments($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testGetPaymentById()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'amount' => 10.99]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM payments WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->getPaymentById($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testCreatePayment()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([10.99]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO payments (amount) VALUES (?)'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['amount' => 10.99]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->createPayment($request, $response);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testUpdatePayment()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([10.99, 1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE payments SET amount = ? WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['amount' => 10.99]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->updatePayment($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeletePayment()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM payments WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->deletePayment($request, $response);
        $this->assertEquals(204, $result->getStatusCode());
    }
}