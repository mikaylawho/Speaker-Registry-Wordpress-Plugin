Feature: As a site admin, I want to create a new Association Rule to tell the sync process what types of CiviMembers
  to sync, and how to map them to Wordpress roles.

  Scenario: View page to Add Association Rule to Tadpole CiviMember Role Synchronize plugin.
    Given I login with username "phpstorm" and password "phpstorm"
    Then I go to the Plugins Admin page
    Given The Tadpole CiviMember Role Synchronize plugin is "activated"
    Then I go to civi_user_sync configuration page.
    When I click "Add Association Rule"
    Then I see "select" element id "civi_member_type" with one or more options.
    And I see "select" element id "wp_role" with one or more options.
    And I see "checkbox" element id "current" with one or more options.
    And I see "checkbox" element id "expire" with one or more options.
    And I see "select" element id "expire_assign_wp_role" with one or more options.

  Scenario: Add a New Association Rule
    Given I login with username "phpstorm" and password "phpstorm"
    And I go to the Civi Member Sync configuration page.
    When I click "Add Association Rule"
    And I select "General" in the "Select a CiviMember Membership Type" dropdown
    And I select "Contributor" in the "Select a Wordpress Role" dropdown
    And I check "New,Current,Grace" in "Current" checkboxes
    And I check "Expired,Pending,Cancelled,Deceased" in "Expire" checkboxes
    And I select "Subscriber" in the "Select a Wordpress Expiry Role" dropdown
    And I click button "Add association rule"
    Then I see a table with a row containing "General", "Contributor", "New,Current,Grace", "Expired,Pending,Cancelled,Deceased", "Subscriber"



  Scenario: Edit an Association Rule

