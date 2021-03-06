<?php

use Mockery as m;
use Myth\Auth\Models\LoginModel;
use Myth\Auth\Authentication\AuthenticationBase;

class AuthenticationBaseLoginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var AuthenticationBase
     */
    protected $auth;
    /**
     * @var LoginModel
     */
    protected $loginModel;

    public function setUp()
    {
        parent::setUp();

        $this->loginModel = m::mock(LoginModel::class);

        $this->auth = new AuthenticationBase(new \Myth\Auth\Config\Auth());
        $this->auth->setLoginModel($this->loginModel);
    }


    public function testRecordLoginAttemptSuccess()
    {
        $credentials = [
            'password' => 'secret',
            'email' => 'joe@example.com'
        ];

        $this->loginModel->shouldReceive('insert')->once()->with(\Mockery::subset([
            'ip_address' => '0.0.0.0',
            'attempt_key' => 'email',
            'attempt_value' => 'joe@example.com',
            'user_id' => 12,
            'date' => date('Y-m-d H:i:s')
        ]))->andReturn(true);

        $this->assertTrue($this->auth->recordLoginAttempt($credentials, '0.0.0.0', 12));
    }

}
