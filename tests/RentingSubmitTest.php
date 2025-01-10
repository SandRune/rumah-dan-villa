<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../renting_submit_functions.php';

class RentingSubmitTest extends TestCase {
    private $mockConn;

    protected function setUp(): void {
        // Mock koneksi database
        $this->mockConn = $this->createMock(mysqli::class);
    }

    public function testValidateIframeValid() {
        $iframe = "<iframe src='https://example.com'></iframe>";
        $this->assertTrue(validateIframe($iframe));
    }

    public function testValidateIframeInvalid() {
        $iframe = "<div></div>";
        $this->assertFalse(validateIframe($iframe));
    }

    public function testUploadImagesSuccess() {
        $mockFiles = [
            "name" => ["image1.jpg", "image2.png"],
            "tmp_name" => ["/tmp/phpYzdqkD", "/tmp/phpUxfGzP"],
            "error" => [0, 0],
            "size" => [12345, 67890]
        ];
        $uploadDir = __DIR__ . "/uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageURLs = uploadImages($mockFiles, $uploadDir);
        $this->assertCount(2, $imageURLs);

        foreach ($imageURLs as $url) {
            $this->assertFileExists($url);
            unlink($url); // Hapus file setelah pengujian
        }

        rmdir($uploadDir);
    }

    public function testInsertPropertySuccess() {
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('error')->willReturn('');
        $mockStmt->method('insert_id')->willReturn(1);

        $this->mockConn->method('prepare')->willReturn($mockStmt);

        $data = [
            "location" => "Jakarta",
            "title" => "Beautiful Apartment",
            "description" => "Cozy and modern.",
            "price" => 1000000,
            "propertyType" => "Entire Place",
            "guestrooms" => 2,
            "bedrooms" => 1,
            "beds" => 1,
            "bathrooms" => 1,
            "amenities" => "Wifi, TV, Air Conditioning",
            "images" => "image1.jpg, image2.jpg",
            "map_location" => "<iframe src='https://maps.google.com'></iframe>"
        ];

        $propertyId = insertProperty($this->mockConn, $data);
        $this->assertEquals(1, $propertyId);
    }
}
?>
