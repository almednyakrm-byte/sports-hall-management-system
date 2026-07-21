<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bookings;
use App\Database;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestBookings extends TestCase
{
    private MockObject $pdo;
    private Bookings $bookings;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->bookings = new Bookings(new Database($this->pdo));
    }

    public function testGetBookings(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM bookings')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->getBookings($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateBooking(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO bookings (name, email, date) VALUES (:name, :email, :date)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'date' => '2024-01-01',
            ]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->createBooking($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testUpdateBooking(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE bookings SET name = :name, email = :email, date = :date WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'id' => 1,
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'date' => '2024-01-02',
            ]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->updateBooking($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testDeleteBooking(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM bookings WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->deleteBooking($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}