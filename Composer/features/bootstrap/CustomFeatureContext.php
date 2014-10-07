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
        if ( strpos( $dropdown_label, 'CiviMember Membership Type'  ) !== false ) {
            $dropdown_id = 'civi_member_type';
        } elseif ( strpos( $dropdown_label, 'Select a Wordpress Role' ) !== false ) {
            $dropdown_id = 'wp_role';
        } elseif ( strpos( $dropdown_label, 'Wordpress Expiry Role' ) !== false ) {
            $dropdown_id = 'expire_assign_wp_role';
        }

        $dropdown = $this->session->getPage()->findById( $dropdown_id );
        $dropdown->selectOption( $dropdown_option );


    }

    /**
     * @Given /^I check "([^"]*)" in "([^"]*)" checkboxes$/
     */
    public function iCheckInCheckboxes( $options_list, $list_label ) {

        if ( strpos( $list_label, 'Current Status' ) != false ) {
            $container      = $this->session->getPage()->findById( 'current-status-td' );
            $optionElements = $container->findAll( 'css', 'input' );
        } elseif ( strpos( $list_label, 'Expire Status'  ) != false ) {
            $container      = $this->session->getPage()->findById( 'expire-status-td' );
            $optionElements = $container->findAll( 'css', 'input' );
        }

        $select_items = explode( ',', $options_list );

        if ( isset( $optionElements ) ) {
            foreach ( $optionElements as $option ) {
                if ( in_array( $option->label, $select_items ) ) {
                    $option->check();

                }

            }
        }

    }


}





