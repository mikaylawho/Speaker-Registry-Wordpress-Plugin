<?php
/**
 * Created by PhpStorm.
 * User: mikelhensley
 * Date: 10/3/14
 * Time: 11:20 AM
 */



use Behat\Behat\Tester\Exception\PendingException,
    Behat\Behat\Context,
    Behat\Mink,
    Behat\MinkExtension,
    Behat\MinkExtension\Context\MinkContext;

use Behat\Mink\Session,
    Behat\Mink\Driver\GoutteDriver;


require_once '/Users/mikelhensley/Sites/wp_phpstorm/Composer/vendor/autoload.php';
require_once '/Users/mikelhensley/Sites/wp_phpstorm/Composer/src/Framework/Assert/Functions.php';


class CustomFeatureContext extends MinkContext {

    private $session;

//        private $mink;
    function __construct() {
        $this->getMink( new Mink\Mink() );
        $this->session = new Session( new GoutteDriver() );
        $this->session->start();
    }


    /**
     * @When /^I login with username "([^"]*)" and password "([^"]*)"$/
     * @param $username
     * @param $password
     *
     * @throws Exception
     */
    public function iLoginWithUsernameAndPassword( $username, $password ) {
        if ( ! $this->session->isStarted() ) {
            throw new PendingException( 'session is not started!' );
        }


        // get page content:
        $this->session->visit( "http://localhost:8888/wp_phpstorm/wp-login.php" );
        $page = $this->session->getPage();
        if ( ! isset( $page ) ) {
            throw new PendingException( "cannot retrieve the login page!" );
        }

        $login_field = $page->findById( 'user_login' );
        $login_field->setValue( $username );
        $pass_field = $page->findById( 'user_pass' );
        $pass_field->setValue( $password );
        $submit = $page->findById( 'wp-submit' );
        $submit->click();

        assertContains( 'wp-admin', $this->session->getCurrentUrl() );

    }


    /**
     * @Given /^I see "([^"]*)"$/
     * @param $text
     */
    public function iSee( $text ) {
        $this->ConfirmTextOnPage( $text, $this->session->getCurrentUrl() );
    }


    /**
     * @When /^I click "([^"]*)"$/
     * @param $ActivateLink
     */
    public function iClick( $ActivateLink ) {

//        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//        $this->session->visit( 'http://localhost:8888/wp_phpstorm' . $ActivateLink );
//        $link = $this->session->getPage()->find(
//            'xpath',
//            "//*[@id='wpbody-content']/div[3]/h2/a[1]"
//        );

        $link = $this->session->getPage()->FindLink( $ActivateLink );


        $link->click();


    }

    /**
     * @Given /^I confirm that "([^"]*)" plugin is installed on the Wordpress site\.$/
     * @param $CiviCrm
     */
    public function iConfirmThatPluginIsInstalledOnTheWordpressSite( $CiviCrm ) {
        $this->ConfirmTextOnPage( $CiviCrm, 'http://localhost:8888/wp_phpstorm/wp-admin/plugins.php' );

    }


    /**
     * @When /^I activate the Tadpole CiviMember Role Synchronize plugin\.$/
     */
    public function iActivateTheTadpoleCiviMemberRoleSynchronizePlugin() {

        $link = $this->session->getPage()->find(
            'xpath',
            "//*[@id='tadpole-civimember-role-synchronize']/td[1]/div/span[1]/a"
        );


        $link->click();
    }


    /**
     * @Given /^The Tadpole CiviMember Role Synchronize plugin is "([^"]*)"$/
     */
    public function theTadpoleCiviMemberRoleSynchronizePluginIs( $activated_or_not_activated ) {
        $link = $this->session->getPage()->find(
            'xpath',
            "//*[@id='tadpole-civimember-role-synchronize']/td[1]/div/span[1]/a"
        );
        if ( $activated_or_not_activated == "not activated" ) {
            assertContains( 'Activate', $link->getAttribute( 'title' ) );
        } else {
            assertContains( 'Deactivate', $link->getAttribute( 'title' ) );
        }
    }


    /**
     * @Given /^I go to the Plugins Admin page$/
     */
    public function iGoToThePluginsAdminPage() {
        $this->VisitPage( 'http://localhost:8888/wp_phpstorm/wp-admin/plugins.php' );
    }


    /**
     * @Given /^I go to civi_user_sync configuration page\.$/
     */
    public function iGoToCivi_user_syncConfigurationPage() {
        $this->VisitPage( "http://localhost:8888/wp_phpstorm/wp-admin/admin.php?page=civi_member_sync/list.php" );
    }

    /**
     * @Then /^I see "([^"]*)" element id "([^"]*)" with one or more options\.$/
     */
    public function iSeeElementIdWithOneOrMoreOptions( $type, $id ) {
        $optionElements = array();
        if ( $type == 'select' ) {
            $element        = $this->session->getPage()->findById( $id );
            $optionElements = $element->findAll( 'css', 'option' );


        } elseif ( $type == 'checkbox' ) {
            if ( $id == 'current' ) {
                $container      = $this->session->getPage()->findById( 'current-status-td' );
                $optionElements = $container->findAll( 'css', 'input' );
            } elseif ( $id == 'expire' ) {
                $container      = $this->session->getPage()->findById( 'expire-status-td' );
                $optionElements = $container->findAll( 'css', 'input' );
            }

        }

        assertGreaterThan( 1, count( $optionElements ) );

    }


    /*private helper methods below*/

    /**
     * @param $page_url
     */
    private function VisitPage( $page_url ) {
        $this->session->visit( $page_url );
    }

    /**
     * @param $content_string
     *
     * @param $page_url
     *
     * @internal param $CiviCrm
     */
    private function ConfirmTextOnPage( $content_string, $page_url ) {
        $this->VisitPage( $page_url );
        $page = $this->session->getPage();
        if ( ! isset( $page ) ) {
            throw new PendingException( "cannot retrieve the page!" );
        }
        $page_content = $page->getContent();

        assertContains( $content_string, $page_content );
    }

    /*end private helper method section*/


    /**
     * @Given /^I go to the Civi Member Sync configuration page\.$/
     */
    public function iGoToTheCiviMemberSyncConfigurationPage() {
        $this->VisitPage( "http://localhost:8888/wp_phpstorm/wp-admin/options-general.php?page=civi_member_sync/list.php" );
    }

    /**
     * @Given /^I select "([^"]*)" in the "([^"]*)" dropdown$/
     */
    public function iSelectInTheDropdown( $dropdown_option, $dropdown_label ) {

        $dropdown_id = '';
        if ( strpos( $dropdown_label, 'CiviMember Membership Type' ) !== false ) {
            $dropdown_id = 'civi_member_type';
        } elseif ( strpos( $dropdown_label, 'Select a Wordpress Role' ) !== false ) {
            $dropdown_id = 'wp_role';
        } elseif ( strpos( $dropdown_label, 'Wordpress Expiry Role' ) !== false ) {
            $dropdown_id = 'expire_assign_wp_role';
        }

        $value         = '';
        $dropdown      = $this->session->getPage()->findById( $dropdown_id );
        $dropdown_list = $dropdown->getParent()->findAll( 'xpath', "select[@id='" . $dropdown_id . "']/option[@value != '']" );
        //$dropdown->selectOption( $dropdown_option );

        assertGreaterThan( 1, count( $dropdown_list ), 'can not find the dropdown options!' );

        foreach ( $dropdown_list as $item ) {
            //find which option matches $dropdown_option
            //get that option
            if ( $item->getText() == $dropdown_option ) {
                //get the value for that option
                //$html = $item->getHtml();
                $value = $item->getAttribute( 'value' );
                $dropdown->setValue( $value );

            }

        }

        assertEquals( $dropdown->getValue(), $value, 'The ' . $dropdown_id . ' dropdown was not properly set to ' . $dropdown_option );
    }







    /**
     * @Given /^I check "([^"]*)" in "([^"]*)" checkboxes$/
     */
    public function iCheckInCheckboxes( $options_list, $list_label ) {
        $select_items   = explode( ',', $options_list );
        $checkbox_array = array();
        $labels         = $this->session->getPage()->findAll( 'xpath', '//label' );
        $counter        = 0;
        foreach ( $labels as $label ) {
            if ( in_array( $label->getText(), $select_items ) and strpos( $label->getAttribute( 'for' ), strtolower( $list_label ) ) !== false ) {
                $checkbox_array[ $counter ] = array( $label->getAttribute( 'for' ), $label->getText() );
                $counter ++;
            }
        }

        foreach ( $checkbox_array as $checkbox ) {
            $checkbox_to_check = $this->session->getPage()->findById( $checkbox[0] );
            if ( ! $checkbox_to_check->isChecked() ) {
                //this is working on the first "New" in the Current Status list, but failing for all others. No clue why...
                $checkbox_to_check->check();
            }

            assertTrue( $checkbox_to_check->isChecked(), 'First check of checkbox ' . $checkbox[1] . ' / id = ' . $checkbox[0] . ' should be checked but it is not.' );
        }

        //confirm that the appropriate checkboxes are checked
        foreach ( $checkbox_array as $checkbox ) {
            $checked_item = $this->session->getPage()->findById( $checkbox[0] );
            assertTrue( $checked_item->isChecked(), $list_label . 'Second check of checkbox ' . $checkbox[1] . ' / id = ' . $checkbox[0] . ' should be checked but it is not.' );
        }

    }

    /**
     * @Given /^I click button "([^"]*)"$/
     */
    public function iClickButton( $button_text ) {
        $button = $this->session->getPage()->find('xpath', '//*/input[@type="submit"]');
        assert($button->getValue() == $button_text);
        $button->press();

        $page = $this->session->getCurrentUrl();
        $response_content = $this->session->getResponseHeaders();
        $response_string = '';
        foreach($response_content as $response ){
            $response_string = $response_string . '  ' . implode($response);

        }

        //check for required field errors on the form
        $error_span = $this->session->getPage()->find('xpath', '//*[@id="wpbody-content"]/span');
        assertEmpty($error_span->getText(), 'Failed submitting the new Association Rule form due to failed form field validations: ' . $error_span->getText());

    }


    /**
     * @Then /^I see a table with a row containing "([^"]*)", "([^"]*)", "([^"]*)", "([^"]*)", "([^"]*)"$/
     */
    public function iSeeATableWithARowContaining2( $civi_role, $wp_role, $current_list, $expired_list, $wp_expire_role ) {
        $current_url = $this->session->getCurrentUrl();

        assertContains('list.php', $current_url, 'This is not the CiviCrm Association Rule page! Currently on ' . $current_url );

        $table_rows = $this->session->getPage()->findAll( 'xpath', '//*[@id="the-list"]/tr/[td[1]/text()[contains(., "' . $civi_role . '")]]' );


        assertEquals(1,count($table_rows),"The new Association Rule was not found!");

        $row_cells = $this->session->getPage()->findAll( "css", "td" );

        assertContains( $civi_role, $row_cells[1] );


    }


}





