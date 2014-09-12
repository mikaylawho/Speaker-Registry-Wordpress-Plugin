Feature: I am a wordpress site administrator and I need to activate this plugin.

  Scenario: Activate the plugin without errors.
    Given A "Deactivated" plugin in the row with the id "kyss-speaker-registry"
    And A logged in user of type "administrator"
    When I visit "/wp-admin/plugins.php"
    And I click "Activate" within the row with the id "kyss-speaker-registry"
    Then I should see "Plugin activated"
