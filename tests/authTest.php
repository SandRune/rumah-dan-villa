<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../auth.php';

class AuthTest extends TestCase {
    private $mockConn;

    protected function setUp(): void {
        // Mock koneksi database
        $this->mockConn = $this->createMock(mysqli::class);

        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);

        // Mock hasil query
        $mockResult->method('num_rows')->willReturn(1);
        $mockResult->method('fetch_assoc')->willReturn([
            'id' => 1,
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT)
        ]);

        // Mock prepared statement
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('get_result')->willReturn($mockResult);

        // Mock metode prepare
        $this->mockConn->method('prepare')->willReturn($mockStmt);
    }

    public function testAuthenticateUserSuccess() {
        $response = authenticateUser('test@example.com', 'password123', $this->mockConn);
        $this->assertTrue($response['success']);
        $this->assertEquals('Login berhasil!', $response['message']);
    }

    public function testAuthenticateUserInvalidPassword() {
        $response = authenticateUser('test@example.com', 'wrongpassword', $this->mockConn);
        $this->assertFalse($response['success']);
        $this->assertEquals('Password salah.', $response['message']);
    }

    public function testAuthenticateUserEmailNotFound() {
        $this->mockConn->method('prepare')->willReturnCallback(function () {
            $mockStmt = $this->createMock(mysqli_stmt::class);
            $mockResult = $this->createMock(mysqli_result::class);

            $mockResult->method('num_rows')->willReturn(0);
            $mockStmt->method('execute')->willReturn(true);
            $mockStmt->method('get_result')->willReturn($mockResult);

            return $mockStmt;
        });

        $response = authenticateUser('unknown@example.com', 'password123', $this->mockConn);
        $this->assertFalse($response['success']);
        $this->assertEquals('Email tidak terdaftar. Silakan Sign Up.', $response['message']);
    }
}
?>
