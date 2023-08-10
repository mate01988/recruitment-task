<?php

declare(strict_types=1);

namespace App\Tests\PhpUnit\Controller;

use App\Meeting\Domain\Aggregate\MeetingSerializer;
use App\Meeting\Infrastructure\Repository\MeetingRepository;
use App\Shared\Domain\DataFixtures\MeetingFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @covers \App\Meeting\Application\Controller\Meeting\GetMeetingQueryController
 *
 * @runTestsInSeparateProcesses
 *
 * @group functional
 * @group MeetingQuery
 */
final class GetMeetingQueryControllerTest extends WebTestCase
{
    private static KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        self::$client = self::createClient();

        $databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([
            MeetingFixtures::class,
        ]);
    }

    public function testGetMeetingQuery(): void
    {
        /** @var $meetingRepository MeetingRepository */
        $meetingRepository = self::getContainer()->get(MeetingRepository::class);

        /** @var MeetingSerializer $meetingSerializer */
        $meetingSerializer = self::getContainer()->get(MeetingSerializer::class);

        $testMeeting = $meetingRepository->first();
        self::$client->request('GET', sprintf('/api/meeting/%s', $testMeeting->getId()));
        $this->assertResponseIsSuccessful();

        $this->assertEquals(
            $meetingSerializer->fromEntity($testMeeting)->toArray(),
            json_decode(self::$client->getResponse()->getContent(), true)
        );
    }
}
