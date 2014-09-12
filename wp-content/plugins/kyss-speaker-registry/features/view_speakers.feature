Feature: List of speakers is displayed for the user in an "accordion" style table.

  Scenario: User wants to view the information for potential speakers for an upcoming event.
    When I visit a page with the URL "view-speakers.php"
    Then I should see an page title that says "View Speakers"
