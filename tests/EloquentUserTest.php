<?php
use Ipunkt\Simpleauth\models\EloquentUser;

/**
 * Created by PhpStorm.
 * User: sven
 * Date: 22.05.14
 * Time: 16:08
 */

class EloquentUserTest extends \Orchestra\Testbench\TestCase {
    /**
     * @var EloquentUser
     */
    private $user;

    /**
     * Setup which happens before each test is run
     */
    public function setUp() {
        parent::setUp();
        $this->user = new EloquentUser();
    }
    /** @test */
    public function exists() {
        new EloquentUser();
    }

    /** @test */
    public function can_set_password() {
        $pw = 'testpassword';
        $this->user->setPassword($pw);
        $this->assertTrue(hash::check($pw, $this->user->getAuthPassword()));
    }

    /** @test */
    public function can_set_email() {
        $email = 'test@test.de';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
        $this->assertEquals($email, $this->user->getReminderEmail());
    }

    /** @test */
    public function can_set_identifier() {
        $identifier = 'bernhard';
        $this->user->setIdentifier($identifier);
        $this->assertEquals($identifier, $this->user->getIdentifier());
    }

    /** @test */
    public function can_set_extra_fields() {
        $field = 'hell';
        $value = true;
        $this->user->setExtra($field, $value);
        $this->assertEquals($value, $this->user->getExtra($field));
    }

    /** @test */
    public function validation_fails_if_email_field_is_no_email() {
        $this->user->setEmail('bla');
        $this->assertFalse($this->user->validate());
        $this->assertCount(1, $this->user->validationErrors());
    }

    /** @test */
    public function validation_passes_if_email_field_is_an_email() {
        $this->user->setEmail('bla@blubb.de');
        $this->assertTrue($this->user->validate());
        $this->assertCount(0, $this->user->validationErrors());
    }

    /**
     * @return array
     */
    protected function getPackageProviders() {
        return [
            'Ipunkt\Simpleauth\SimpleauthServiceProvider'
        ];
    }
}
 