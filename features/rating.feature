Feature:
  In order to send a rating of the meeting
  As a participant
  I want to send my rating

  Scenario Outline: Participants successfully send a rating of the meeting
    Given There is a closed meeting with <number> participant
    When I added <rating> to meeting for each participant
    Then I have the <avg> of the rating for that meeting

    Examples:
      | number | rating | avg |
      | 4      | 4      | 4   |
      | 5      | 5      | 5   |
      | 1      | 5      | 5   |
      | 5      | 1      | 1   |
      | 5      | 2      | 2   |

  Scenario Outline: Participants failed send a rating of the meeting
    Given There is a closed meeting with <number> participant
    When I added invalid <rating> to meeting for each participant
    Then Then I should catch an exception

    Examples:
      | number | rating |
      | 1      | 6      |
      | 3      | -1     |
      | 5      | 0      |

  Scenario: Participants send a rating of the unclosed meeting
    Given There is an unclosed meeting with 3 participant
    When I added invalid 3 to meeting for each participant
    Then Then I should catch an exception