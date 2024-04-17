<?php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $pdf = new Pdf();

        $title = 'Mon pdf';
        $created_at = new \DateTimeImmutable();

        $pdf->setTitle($title);
        $pdf->setCreatedAt($created_at);

        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($created_at, $pdf->getCreatedAt());
    }
}