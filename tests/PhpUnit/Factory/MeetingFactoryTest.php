<?php

namespace App\Tests\PhpUnit\Factory;

use App\Meeting\Domain\Entity\Meeting;
use App\Meeting\Domain\Entity\MeetingStatus;
use App\Meeting\Domain\Factory\MeetingFactory;
use PHPUnit\Framework\TestCase;

class MeetingFactoryTest extends TestCase
{
    public function testCreateObject()
    {
        $name = 'Test 1';
        $startTime = new \DateTimeImmutable();
        $maximumNumberOfParticipants = 5;

        $meeting = (new MeetingFactory())->create($name, $startTime, $maximumNumberOfParticipants);
        $this->assertInstanceOf(Meeting::class, $meeting);

        $this->assertEquals($startTime, $meeting->getStartTime());
        $this->assertEquals(MeetingStatus::InSession->value, $meeting->getStatus()->value);
        $this->assertEquals($maximumNumberOfParticipants, $meeting->getMaxUsers());

        $this->assertNotEmpty($meeting->getId());
        $this->assertIsString($meeting->getId());
        $this->assertIsObject($meeting->getStartTime());
        $this->assertIsObject($meeting->getEndTime());
    }
}
