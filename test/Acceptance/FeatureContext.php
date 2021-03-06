<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use Exception;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    /**
     * @var ServiceContainer
     */
    private $container;

    private ?ConcertId $concertId = null;

    private ?string $emailAddress = null;

    private ?ReservationId $reservationId = null;

    /**
     * @BeforeScenario
     */
    public function setUp(): void
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
     * @Then I should be able to make a reservation for :numberOfSeats seats
     * @Given :numberOfSeats seats have already been reserved
     */
    public function iMakeAReservationForSeats(int $numberOfSeats, string $emailAddress = 'test@example.com'): void
    {
        Assertion::isInstanceOf($this->concertId, ConcertId::class);

        $this->emailAddress = $emailAddress;

        $this->reservationId = $this->container->makeReservationService()->makeReservation(
            $this->concertId->asString(),
            $emailAddress,
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
    public function iShouldReceiveAnEmailSaying(string $messageContains): void
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

    /**
     * @When I cancel this reservation
     */
    public function iCancelThisReservation(): void
    {
        Assertion::isInstanceOf($this->concertId, ConcertId::class);
        Assertion::isInstanceOf($this->reservationId, ReservationId::class);

        $this->container->cancelReservation()->cancelReservation(
            $this->concertId->asString(),
            $this->reservationId->asString()
        );
    }
}
