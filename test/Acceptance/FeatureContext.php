<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use Exception;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

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
        $this->concertId = $this->container->planConcertService()->plan(
            'A concert',
            '2020-09-01 20:00',
            $numberOfSeats
        );
    }

    /**
     * @When I make a reservation for :numberOfSeats seats and provide :emailAddress as my email address
     */
    public function iMakeAReservationForSeats(int $numberOfSeats, string $emailAddress): void
    {
        Assertion::isInstanceOf($this->concertId, ConcertId::class);

        $this->emailAddress = $emailAddress;

        $this->container->makeReservationService()->makeReservation(
            $this->concertId->asString(),
            $emailAddress,
            $numberOfSeats
        );
    }

    /**
     * @Given :numberOfSeats seats have already been reserved
     */
    public function seatsHaveAlreadyBeenReserved(int $numberOfSeats): void
    {
        Assertion::isInstanceOf($this->concertId, ConcertId::class);

        $this->container->makeReservationService()->makeReservation(
            $this->concertId->asString(),
            'test@example.com',
            $numberOfSeats
        );
    }

    /**
     * @When I try to make a reservation for :numberOfSeats seats
     */
    public function iTryToMakeAReservationForSeats(int $numberOfSeats): void
    {
        $this->shouldFail(
            function () use ($numberOfSeats) {
                Assertion::isInstanceOf($this->concertId, ConcertId::class);

                $this->container->makeReservationService()->makeReservation(
                    $this->concertId->asString(),
                    'test@example.com',
                    $numberOfSeats
                );
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
        $this->assertCaughtExceptionMatches(
            Exception::class,
            $messageContains
        );
    }
}
