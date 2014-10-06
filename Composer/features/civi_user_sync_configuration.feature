Feature: As the Wordpress Site administrator, I want to install and configure the civi_user_sync plugin to synchronise
  CiviCRM Member accounts with the Wordpress user accounts.


  Scenario:
    When I login with username "phpstorm" and password "phpstorm"
    And I confirm that "civicrm" plugin is installed on the Wordpress site.
    And I see "Tadpole CiviMember Role Synchronize"
    And I activate the Tadpole CiviMember Role Synchronize plugin.
    Then I see "Plugin activated."











