Feature:

  Scenario: Reserving seats when plenty of seats are available
    Given a concert was planned with 10 seats
    When I make a reservation for 2 seats and provide "test@example.com" as my email address
    Then I should receive an email on the provided address saying: "2 seats have been reserved"

  Scenario: Reserving seats when not enough seats are available
    Given a concert was planned with 10 seats
    And 7 seats have already been reserved
    When I try to make a reservation for 6 seats
    Then the system will show me an error message saying that "Not enough seats were available"
