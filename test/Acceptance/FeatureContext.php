<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Exception;
use PHPUnit\Framework\Assert;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    /**
     * @var Exception|null
     */
    private $exception;

    /**
     * @var ServiceContainer
     */
    private $container;

    /**
     * @var ConcertId|null
     */
    private $concertId;

    /**
     * @var string|null
     */
    private $emailAddress;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->container = new ServiceContainer();
    }

    /**
     * @Given a concert was planned with :numberOfSeats seats
     */
    public function aConcertWasPlannedWithSeats(int $numberOfSeats): void
    {
        $service = $this->container->planConcertService();
        $name = 'A concert';
        $date = '2020-09-01 20:00';

        // TODO plan the concert
        // TODO store the returned ConcertId in $this->concertId

        throw new PendingException();
    }

    /**
     * @When I make a reservation for :numberOfSeats seats and provide :emailAddress as my email address
     * @Given :numberOfSeats seats have already been reserved
     */
    public function iMakeAReservationForSeats(int $numberOfSeats, string $emailAddress): void
    {
        Assertion::isInstanceOf($this->concertId, ConcertId::class);

        $service = $this->container->makeReservationService();

        $concertId = $this->concertId->asString();
        $this->emailAddress = $emailAddress;

        // TODO make the reservation

        throw new PendingException();
    }

    /**
     * @When I try to make a reservation for :numberOfSeats seats
     */
    public function iTryToMakeAReservationForSeats(int $numberOfSeats): void
    {
        throw new PendingException();

        $this->shouldFail(
            function () {
                Assertion::isInstanceOf($this->concertId, ConcertId::class);

                $service = $this->container->makeReservationService();
                $concertId = $this->concertId->asString();
                $emailAddress = 'test@example.com';

                // TODO make the reservation
            }
        );
    }

    /**
     * @Then I should receive an email on the provided address saying: :message
     */
    public function iShouldReceiveAnEmailSaying(string $messageContains)
    {
        Assertion::string($this->emailAddress);

        $this->container->mailer()->assertEmailSent(
            $this->emailAddress,
            $messageContains
        );
    }

    /**
     * @Then the system will show me an error message saying that :messageContains
     */
    public function theSystemWillTellMeThat(string $messageContains): void
    {
        Assert::assertInstanceOf(Exception::class, $this->exception);
        Assert::assertContains($messageContains, $this->exception->getMessage());
    }

    private function shouldFail(callable $function): void
    {
        try {
            $function();

            throw new ExpectedAnException();
        } catch (Exception $exception) {
            $this->exception = $exception;
        }
    }
}
