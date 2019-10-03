Feature:

  Scenario: Buying tickets when plenty of seats are available
    Given a concert was scheduled with 10 seats
    When I buy 2 tickets
    Then I should receive an email saying I've bought 2 tickets for this concert

  Scenario: Buying tickets when not enough seats are available
    Given a concert was scheduled with 10 seats
    And 7 tickets have already been bought
    When I buy 6 tickets
    Then the system will tell me that "Not enough seats were available"
