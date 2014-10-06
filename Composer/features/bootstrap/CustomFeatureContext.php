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
        $this->getMink(new Mink\Mink());
        $this->session = new Session(new GoutteDriver());
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
        $login_field->setValue( 'phpstorm' );
        $pass_field = $page->findById( 'user_pass' );
        $pass_field->setValue( 'phpstorm' );
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

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $this->session->visit( 'http://localhost:8888/wp_phpstorm' . $ActivateLink );

    }

    /**
     * @Given /^I confirm that "([^"]*)" plugin is installed on the Wordpress site\.$/
     * @param $CiviCrm
     */
    public function iConfirmThatPluginIsInstalledOnTheWordpressSite( $CiviCrm ) {
        $this->ConfirmTextOnPage( $CiviCrm, 'http://localhost:8888/wp_phpstorm/wp-admin/plugins.php' );

    }

    /**
     * @param $content_string
     *
     * @param $page_url
     *
     * @internal param $CiviCrm
     */
    private function ConfirmTextOnPage( $content_string, $page_url ) {
        // get page content:
        //$this->session->visit( "http://localhost:8888/wp_phpstorm/wp-admin/plugins.php" );
        $this->session->visit( $page_url );
        $page = $this->session->getPage();
        if ( ! isset( $page ) ) {
            throw new PendingException( "cannot retrieve the page!" );
        }
        $page_content = $page->getContent();

        assertContains( $content_string, $page_content );
    }

    /**
     * @Given /^I activate the Tadpole CiviMember Role Synchronize plugin\.$/
     */
    public function iActivateTheTadpoleCiviMemberRoleSynchronizePlugin() {
        $feature_row = $this->session->getPage()->findById('tadpole-civimember-role-synchronize');
        $span = $feature_row->find(
            'xpath',
            $this->session->getSelectorsHandler()->selectorToXpath('css', 'span.activate') // just changed xpath to css
        );
        $link = $span->find(
            'xpath',
            $this->session->getSelectorsHandler()->selectorToXpath('css', 'a') // just changed xpath to css
        );

        $link->click();
    }

    //#tadpole-civimember-role-synchronize > td.plugin-title > div >  > a


}



