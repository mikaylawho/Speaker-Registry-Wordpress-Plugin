<?php
/**
 * Created by PhpStorm.
 * User: mikelhensley
 * Date: 9/9/14
 * Time: 9:18 PM
 */

use Behat\BehatBundle\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;

class FeatureContext extends BehatContext{


	/**
	 * @When /^I visit a page with the URL "view\-speakers\.php"$/
	 */
	public function iVisitAPageWithTheURL($url)
	{
		throw new \Behat\Behat\Tester\Exception\PendingException();
	}

	/**
	 * @Then /^I should see an page title that says "([^"]*)"$/
	 */
	public function iShouldSeeAnPageTitleThatSays($page_title)
	{
		throw new \Behat\Behat\Tester\Exception\PendingException();
	}

	/**
	 * @Given /^A "([^"]*)" plugin in the row with the id "kyss\-speaker\-registry"$/
	 */
	public function aPluginInTheRowWithTheId($arg1, $arg2)
	{
		throw new \Behat\Behat\Tester\Exception\PendingException();
	}

	/**
	 * @Given /^A logged in user of type "([^"]*)"$/
	 */
	public function aLoggedInUserOfType($arg1)
	{
		throw new \Behat\Behat\Tester\Exception\PendingException();
	}


}