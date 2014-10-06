<?php
/**
 * Created by PhpStorm.
 * User: mikelhensley
 * Date: 10/3/14
 * Time: 11:20 AM
 */



use Behat\Behat\Tester\Exception\PendingException,
    Behat\Behat\Context;

use Behat\MinkExtension\Context\MinkContext;

use Behat\Mink\Session,
    Behat\Mink\Driver\GoutteDriver;




    class CustomFeatureContext extends MinkContext {

        /** @BeforeFeature */
        public static function prepareForTheFeature() {

            global $session;

            echo "inside constructor";
            $driver = new GoutteDriver();
            // init session:
            $session = new Session( $driver );

            // init session:
            $session = new \Behat\Mink\Session( $driver );

            // start session:
            $session->start();

        } // clean database or do other preparation stuff

        /** @Given we have some context */
        public function prepareContext() {
        } // do something

        /** @When event occurs */
        public function doSomeAction() {
        } // do something

        /** @Then something should be done */
        public function checkOutcomes() {
            global $session;
            $session->end();
        } // do something


        /**
         * @Given /^I am logged into the Wordpress site as an "([^"]*)"$/
         * @param $administrator
         */
        public function iAmLoggedIntoTheWordpressSiteAsAn( $administrator ) {
            $user = wp_get_current_user();
            assertEquals( $user->roles[0], $administrator );
        }


        /**
         * @Given /^"([^"]*)" plugin is installed on the Wordpress site\.$/
         * @param $CiviCrm
         */
        public function pluginIsInstalledOnTheWordpressSite( $CiviCrm ) {

            $this->assertPageContainsText( $CiviCrm );
            //$result = assertContains(file_get_contents( ABSPATH . '/wp-includes/plugin.php' ), $CiviCrm);
            //return $result;
        }

        /**
         * @Given /^I see "([^"]*)"$/
         * @param $plugin_title
         */
        public function iSee( $plugin_title ) {
            //assertContains(file_get_contents( ABSPATH . '/wp-includes/plugin.php' ), $plugin_title );
            $this->assertPageContainsText( $plugin_title );
        }

        /**
         * @Given /^I see "([^"]*)" in the same row\.$/
         * @internal param $arg1
         */
        public function iSeeInTheSameRow() {
            $activate_uri = 'plugins.php?action=activate&amp;plugin=civi_member_sync';
            $page         = file_get_contents( ABSPATH . '/wp-includes/plugin.php' );
            assertContains( $page, $activate_uri );
        }

        /**
         * @When /^I click "([^"]*)"$/
         * @param $ActivateLink
         */
        public function iClick( $ActivateLink ) {

            $this->visit( $ActivateLink );
        }

        /**
         * @Then /^I should see "([^"]*)"$/
         * @param $confirmation
         */
        public function iShouldSee( $confirmation ) {
            $this->assertPageContainsText( $confirmation );
        }

        /**
         * @When /^I go to the Plugins admin page$/
         */
        public function iGoToThePluginsAdminPage() {
            $this->visit( site_url() . '/wp-admin/plugins.php' );
        }

        /**
         * @When /^I login with username "([^"]*)" and password "([^"]*)"$/
         * @param $username
         * @param $password
         *
         * @throws Exception
         */
        public function iLoginWithUsernameAndPassword( $username, $password ) {
            global $session;
            $this->$session->visit( 'http://localhost:8888/wp_admin.php' );

            //throw new PendingException( "not done yet!" );

        }


    }



