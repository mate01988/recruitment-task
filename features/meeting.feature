Feature:
    In order to attend the meeting
    As a participant
    I want to join the meeting

    Scenario Outline: Participants successfully join a meeting with limited space
        Given There is a meeting in "<delay>" minutes with 5 free space
        When I added <numberOfParticipants> to meeting
        Then I have <numberOfParticipants> on meeting
        And Status meeting is <status>
        Examples:
            | delay     | numberOfParticipants  | status        |
            | 0         | 1                     | "IN_SESSION"  |
            | 0         | 3                     | "IN_SESSION"  |
            | 0         | 5                     | "FULL"        |
            | +10       | 3                     | "OPEN"        |
            | +10       | 5                     | "FULL"        |
            | -120      | 3                     | "DONE"        |
            | -120      | 5                     | "DONE"        |
            | -60       | 3                     | "IN_SESSION"  |
            | -60       | 5                     | "FULL"        |
            | -61       | 3                     | "DONE"        |

    Scenario Outline: Participants failed join a meeting with limited space
        Given There is a meeting in "<delay>" minutes with 5 free space
        When I added <numberOfParticipants> to meeting that should throw an exception
        Then Then I should catch an exception
        Examples:
            | delay     | numberOfParticipants |
            | -61       | 6      |
            | 0         | 6      |
            | 0         | 8      |
            | +120      | 6      |
