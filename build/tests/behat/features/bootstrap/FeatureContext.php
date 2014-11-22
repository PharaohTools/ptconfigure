<?php

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Behat context class.
 */
class FeatureContext extends MinkContext implements ContextInterface
{

    protected $session ;

    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // $this->useContext('default_context', new \DefaultContext());
    }

    /**
     * @Then /^I should see the site logo$/
     */
    public function iShouldSeeTheSiteLogo()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $page->find('css', 'html.no-js body.home.page.page-id-5.page-template.page-template-page-home-php div#header header div.container h1 a img');
    }

    /**
     * @Then /^I should see the site menu$/
     */
    public function iShouldSeeTheSiteMenu()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $page->find('css', 'html.no-js body.home.page.page-id-5.page-template.page-template-page-home-php div#header header div.container nav');
    }

    /**
     * @Then /^I should see slider$/
     */
    public function iShouldSeeSlider()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $page->find('css', 'input#edit-name');
        $page->find('css', 'input#edit-pass');
    }

    /**
     * @Then /^I enter my username in the username field$/
     */
    public function iEnterMyUsernameInTheUsernameField()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $el = $page->find('css', 'input#edit-name');
        $el->setValue("ishouldhiredave") ;
    }

    /**
     *@Given /^I enter my password in the password field$/
     */
    public function iEnterMyPasswordInThePasswordField()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $el = $page->find('css', 'input#edit-pass');
        $el->setValue("rightnow") ;
    }

    /**
     * @Then /^I submit the login form$/
     */
    public function iSubmitTheLoginForm()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $el = $page->find('css', 'input#edit-submit');
        $el->press();
    }

    /**
     * @Then /^I should see a greeting message$/
     */
    public function iShouldSeeAGreetingMessage()
    {
        $page = $this->getMainContext()->getSession()->getPage() ;
        $el = $page->find('css', 'html.js body div.region.region-page-top div#toolbar.toolbar.overlay-displace-top.clearfix.toolbar-processed div.toolbar-menu.clearfix ul#toolbar-user li.account.first a');
        $plainText = $el->getText();
        if ($plainText != "Hello ishouldhiredave") {
            throw new Exception('Incorrect Greeting Message'); };
    }

}