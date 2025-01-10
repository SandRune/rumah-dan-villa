<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../reservationtest.php';

class ReservationTest extends TestCase {
    private $mockConn;

    protected function setUp(): void {
        $this->mockConn = $this->createMock(mysqli::class);
    }

    public function testIsDateAvailable() {
        $property_id = 1;
        $start_date = '2023-01-10';
        $end_date = '2023-01-15';

        // Mock hasil query
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Tidak ada tanggal yang berbenturan
        $mockResult->method('num_rows')->willReturn(0);

        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertTrue(isDateAvailable($this->mockConn, $property_id, $start_date, $end_date));
    }

    public function testIsDateUnavailable() {
        $property_id = 1;
        $start_date = '2023-01-10';
        $end_date = '2023-01-15';

        // Mock hasil query
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Ada tanggal yang berbenturan
        $mockResult->method('num_rows')->willReturn(1);

        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertFalse(isDateAvailable($this->mockConn, $property_id, $start_date, $end_date));
    }

    public function testCreateReservationSuccess() {
        $property_id = 1;
        $user_id = 1;
        $start_date = '2023-01-10';
        $end_date = '2023-01-15';
        $guests = 4;

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockStmt->method('execute')->willReturn(true);
        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertTrue(createReservation($this->mockConn, $property_id, $user_id, $start_date, $end_date, $guests));
    }

    public function testCreateReservationFailure() {
        $property_id = 1;
        $user_id = 1;
        $start_date = '2023-01-10';
        $end_date = '2023-01-15';
        $guests = 4;

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockStmt->method('execute')->willReturn(false);
        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertFalse(createReservation($this->mockConn, $property_id, $user_id, $start_date, $end_date, $guests));
    }
}
?>
