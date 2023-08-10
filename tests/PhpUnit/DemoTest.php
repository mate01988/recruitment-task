<?php

namespace App\Tests\PhpUnit;

use App\Meeting\Domain\Entity\Meeting;
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    public function testFramework()
    {
        $meeting = new Meeting('Some Meeting', new \DateTimeImmutable('now'), 5);
        $this->assertInstanceOf(Meeting::class, $meeting);
    }
}
