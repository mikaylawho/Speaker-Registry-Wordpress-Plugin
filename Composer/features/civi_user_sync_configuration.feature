Feature: As the Wordpress Site administrator, I want to install and configure the civi_user_sync plugin to synchronise
  CiviCRM Member accounts with the Wordpress user accounts.

  Scenario: Deactivate the the Tadpole CiviMember Role Synchronize plugin.
    When I login with username "phpstorm" and password "phpstorm"
    And I go to the Plugins Admin page
    And I see "Tadpole CiviMember Role Synchronize"
    Given The Tadpole CiviMember Role Synchronize plugin is "activated"
    When I activate the Tadpole CiviMember Role Synchronize plugin.
    Then I see "Plugin <strong>deactivated</strong>."

  Scenario: Activate the Tadpole CiviMember Role Synchronize plugin.
    When I login with username "phpstorm" and password "phpstorm"
    And I confirm that "civicrm" plugin is installed on the Wordpress site.
    And I see "Tadpole CiviMember Role Synchronize"
    Given The Tadpole CiviMember Role Synchronize plugin is "not activated"
    When I activate the Tadpole CiviMember Role Synchronize plugin.
    Then I see "Plugin <strong>activated</strong>."

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


   Scenario: Edit an Association Rule


   Scenario: Manually Sync CiviCrm Roles with Wordpress Roles


   Scenario: Import CiviCrm Members into the Wordpress User list


   Scenario: Login as newly created users to check their permissions.



















