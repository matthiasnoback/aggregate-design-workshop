<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Exception;
use PHPUnit\Framework\Assert;
use TicketMill\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->serviceContainer = new ServiceContainer();
    }

    private function expectException(callable $function, string $exceptionClass, string $exceptionMessage): void
    {
        try {
            $function();

            throw new ExpectedAnException();
        } catch (Exception $exception) {
            if ($exception instanceof ExpectedAnException) {
                throw $exception;
            }

            Assert::assertInstanceOf($exceptionClass, $exception);
            Assert::assertContains(
                $exceptionMessage,
                $exception->getMessage()
            );
        }
    }
}
