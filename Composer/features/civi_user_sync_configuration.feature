Feature: As the Wordpress Site administrator, I want to install and configure the civi_user_sync plugin to synchronise
  CiviCRM Member accounts with the Wordpress user accounts.


  Scenario:
    When I login with username "phpstorm" and password "phpstorm"
    Given I am logged into the Wordpress site as an "administrator"
    When I go to the Plugins admin page
    And "CiviCrm" plugin is installed on the Wordpress site.
    And I see "Tadpole CiviMember Role Synchronize"
    And I see "Activate" in the same row.
    When I click "/wp-admin/plugins.php?action=activate&plugin=civi_member_sync%2Fcivi_member_sync.php&plugin_status=all&paged=1&s&_wpnonce=a255252579."
    Then I should see "Plugin activated."











