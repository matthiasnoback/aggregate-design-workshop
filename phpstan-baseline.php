<?php

declare(strict_types=1);

$ignoreErrors = [];
$ignoreErrors[] = [
    'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
    'identifier' => 'deadCode.unreachable',
    'count' => 12,
    'path' => __DIR__ . '/test/Unit/TicketMill/Domain/Model/Concert/ConcertTest.php',
];
$ignoreErrors[] = [
    'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
    'identifier' => 'deadCode.unreachable',
    'count' => 3,
    'path' => __DIR__ . '/test/Unit/TicketMill/Domain/Model/Reservation/ReservationTest.php',
];

return [
    'parameters' => [
        'ignoreErrors' => $ignoreErrors,
    ],
];
