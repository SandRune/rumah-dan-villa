<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../admin_functions.php';

class AdminFunctionsTest extends TestCase {
    private $mockConn;

    protected function setUp(): void {
        // Mock koneksi database
        $this->mockConn = $this->createMock(mysqli::class);
    }

    public function testApproveReservation() {
        $reservation_id = 1;
        $action = 'approve';

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Mock hasil query
        $mockResult->method('num_rows')->willReturn(1);
        $mockResult->method('fetch_assoc')->willReturn([
            'user_id' => 1,
            'property_name' => 'Beautiful Villa',
            'start_date' => '2023-01-10',
            'end_date' => '2023-01-15',
        ]);

        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertTrue(handleReservationAction($this->mockConn, $reservation_id, $action));
    }

    public function testRejectReservation() {
        $reservation_id = 1;
        $action = 'reject';
        $reason = 'Double booking';

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Mock hasil query
        $mockResult->method('num_rows')->willReturn(1);
        $mockResult->method('fetch_assoc')->willReturn([
            'user_id' => 1,
            'property_name' => 'Beautiful Villa',
            'start_date' => '2023-01-10',
            'end_date' => '2023-01-15',
        ]);

        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->assertTrue(handleReservationAction($this->mockConn, $reservation_id, $action, $reason));
    }

    public function testReservationNotFound() {
        $reservation_id = 99;
        $action = 'approve';

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Mock hasil query
        $mockResult->method('num_rows')->willReturn(0);

        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Reservation not found.");

        handleReservationAction($this->mockConn, $reservation_id, $action);
    }
}
?>
