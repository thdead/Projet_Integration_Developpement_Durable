<?php
use PHPUNIT\Framework\TestCase;
require '../user/User.php';

/*
*@covers Residence
*/

/*
*@dependencies hydrate
*/
class UserTest extends TestCase{
  //dépend de hydrate qui elle-même dépend de tous les sets.
  public function testConstructor(){
    $user = new User(['id'=>7]);
    $this->assertEquals(7,$user->getId());
  }
  //Test setId()
  public function testSetId(){
    $user = new User([]);
    $this->assertNull($user->setId(7));
    //Testing double
    $this->assertEquals(7,$user->getId());
    //Testing text
    $this->assertEquals(User::INVALID_FORMAT,$user->setId('texte'));
    //Testing 0
    $this->assertEquals(User::TOO_SHORT,$user->setId(0));
    //Testing negative number
    $this->assertEquals(User::TOO_SHORT,$user->setId(-5));
    //Testing normal value
    $this->assertNull($user->setId(5));
    //Verify if the value is set
    $this->assertEquals(5,$user->getId());
  }
  //test setFirstName()
  public function testSetFirstName(){
    $user = new User([]);
    //Testing number
    $this->assertEquals(User::INVALID_FORMAT,$user->setFirstName(5));
    //Testing double
    $this->assertEquals(User::INVALID_FORMAT,$user->setFirstName(5.6));
    //Testing short string
    $this->assertEquals(User::TOO_SHORT,$user->setFirstName('A'));
    //Testing long string
    $long = 'This text length is way more bigger than 32 characters';
    $this->assertTrue(strlen($long) > 32);
    $this->assertEquals(User::TOO_LONG,$user->setFirstName($long));
    //Invalid text
    $invalid = '<?php ?>';
    $this->assertEquals(User::INVALID_FORMAT,$user->setFirstName($invalid));
    $invalid = 'Gestion "de" projet';
    $this->assertEquals(User::INVALID_FORMAT,$user->setFirstName($invalid));
    //valid text
    $valid = 'Jonathan';
    $this->assertNull($user->setFirstName($valid));
    $this->assertEquals(strtolower($valid),$user->getFirstName());
  }
  //test setLastName()
  public function testSetLastName(){
    $user = new User([]);
    //Testing number
    $this->assertEquals(User::INVALID_FORMAT,$user->setLastName(5));
    //Testing double
    $this->assertEquals(User::INVALID_FORMAT,$user->setLastName(5.6));
    //Testing short string
    $this->assertEquals(User::TOO_SHORT,$user->setLastName('A'));
    //Testing long string
    $long = 'This text length is way more bigger than 32 characters';
    $this->assertTrue(strlen($long) > 32);
    $this->assertEquals(User::TOO_LONG,$user->setLastName($long));
    //Invalid text
    $invalid = '<?php ?>';
    $this->assertEquals(User::INVALID_FORMAT,$user->setLastName($invalid));
    $invalid = 'Gestion-de "projet"';
    $this->assertEquals(User::INVALID_FORMAT,$user->setLastName($invalid));
    //valid text
    $valid = 'Noel';
    $this->assertNull($user->setLastName($valid));
    $this->assertEquals(strtolower($valid),$user->getLastName());
  }
  //test setPassword()
  public function testSetPassword(){
    $user = new User([]);
    $pwd = 'short';
    $this->assertEquals(User::TOO_SHORT,$user->setPassword($pwd));
    $pwd = 'longenough';
    $this->assertNull($user->setPassword($pwd));
    $pwd = 'This_is_a_too_long_password';
    $this->assertEquals(User::TOO_LONG,$user->setPassword($pwd));
    $pwd = 'sp 1*/-+#sqDf"';
    $this->assertNull($user->setPassword($pwd));
    //Password != hash
    $this->assertTrue($pwd != $user->getPassword());
  }
  //test passwordMatch()
  public function testPasswordMatch(){
    $user = new User([]);
    $pwd = 'sp 1*/-+#sqDf"';
    $user->setPassword($pwd);
    $hash = $user->getPassword();
    $this->assertTrue($user->passwordMatch($pwd,$hash));
    $hash2 = $hash.'eD';
    $this->assertFalse($user->passwordMatch($pwd,$hash2));
    $pwd2 = '';
    $this->assertFalse($user->passwordMatch($pwd2,$hash));
  }
  //test setBirthDate()
  public function testSetBirthDate(){
    $user = new User([]);
    $bd = '1996-01-24';
    //valid
    $this->assertNull($user->setBirthDate($bd));
    //invalid 30 february
    $bd = '1996-02-30';
    $this->assertEquals(User::INVALID_FORMAT,$user->setBirthDate($bd));
    //TOO YOUNG, MINIMUM AGE IS 16
    $bd = '2015-02-25';
    $this->assertEquals(User::TOO_SHORT,$user->setBirthDate($bd));
    //TOO OLD
    $bd = '1900-02-25';
    $this->assertEquals(User::TOO_LONG,$user->setBirthDate($bd));
    //invalids dates
    $bd = '24 01 1996';
    $this->assertEquals(User::INVALID_FORMAT,$user->setBirthDate($bd));
    $bd = '1950-13-25';
    $this->assertEquals(User::INVALID_FORMAT,$user->setBirthDate($bd));
    $bd = '2017-12-5';
    $this->assertEquals(User::TOO_SHORT,$user->setBirthDate($bd));
  }
  //test setEmail()
  public function testSetEmail(){
    $user = new User([]);
    //valid
    $email = 'valid.email@gmail.com';
    $this->assertNull($user->setEmail($email));
    //invalid
    $email = 'invalid.email@gmail';
    $this->assertTrue($user->setEmail($email) != NULL);
    //not an email
    $email = 'invalid.email?';
    $this->assertEquals(User::INVALID_FORMAT,$user->setEmail($email));
  }
  //test setPhoneNumber()
  public function testSetPhoneNumber(){
    $user = new User([]);
    //valid
    $phone = '0477290948';
    $this->assertNull($user->setPhoneNumber($phone));
    //invalid
    $phone = 'text';
    $this->assertEquals(User::INVALID_FORMAT,$user->setPhoneNumber($phone));
    //invalid
    $phone = '0477';
    $this->assertEquals(User::INVALID_FORMAT,$user->setPhoneNumber($phone));
  }
  //test setGender()
  public function testSetGender(){
    $user = new User([]);
    //
    $gender ='M';
    $this->assertNull($user->setGender($gender));
    $gender = 5;
    $this->assertTrue($user->setGender($gender)!=null);
  }
}
