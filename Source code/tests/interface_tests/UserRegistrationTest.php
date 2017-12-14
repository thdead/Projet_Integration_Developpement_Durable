<?php
/**
 * Author: JORDAN LGH
 * Purpose: interface testing
 */
require '../vendor/autoload.php';
use PHPUNIT\Framework\TestCase;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
class UserRegistrationTest extends TestCase{
    protected $driver;
    /**
     * Set up the configuration of selenium & webdriver.
     */
    public function setUp(){
        $host = 'http://localhost:4444/wd/hub'; //default host
        $capabilities = DesiredCapabilities::chrome(); //use chrome browser
        $this->driver = RemoteWebDriver::create($host, $capabilities, 5000); //initialize & create the driver
        $this->driver->get('http://www.emonitor.ovh/'); //go to url emonitor.ovh
    }
    /**
     * This test fill the form with an invalid birthdate.
     */
    public function testFormRegistrationWithInvalidBirthDate(){
        sleep(1);
        $this->driver->findElement(WebDriverBy::id('registration'))->click();//click button
        sleep(1);
        $this->fillForm('LGH','Jordan','Password0123','24-01-1996','ipdddddddes.lagache@gmail.com','M','');
        sleep(2);
        $this->driver->findElement(WebDriverBy::name('sub'))->click(); //click submit
        $error = (bool)$this->driver->findElement(WebDriverBy::id('err'));
        $this->assertTrue(!empty($error)); //error?
        sleep(5);
        // $this->quit();
    }
    /**
     * Fill the form with information given.
     */
    public function fillForm($lastName,$firstName,$password,$birthDate,$email,$gender,$phone=''){
        $this->driver->findElement(WebDriverBy::name('lastName'))->sendKeys($lastName);
        $this->driver->findElement(WebDriverBy::name('firstName'))->sendKeys($firstName);
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::name('birthDate'))->sendKeys($birthDate);
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys($email);
        $this->driver->findElement(WebDriverBy::name('gender'))->sendKeys($gender);
        $this->driver->findElement(WebDriverBy::name('phone'))->sendKeys($phone);
    }
    /**
     * Leave the browser.
     */
    public function quit(){
        $this->driver->quit();
    }
}    
