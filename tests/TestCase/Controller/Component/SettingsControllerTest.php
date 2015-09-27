<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SettingsController;
use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\Core\Configure;

class SettingsControllerTest extends IntegrationTestCase {
    public $fixtures = [
        'app.settings',
        'app.users'
    ];
    public $components = ['Auth'];

    public function setUp() {
        parent::setUp();
        $this->Settings = TableRegistry::get('Settings');
        $this->Users = TableRegistry::get('Users');
        $user = $this->Users->get(1);
        $this->session(['Auth.User'=> $user->toArray()]);
    }
    public function testIndex(){
        $this->post("/settings");
        $this->assertResponseOk();
    }
    public function testEditValid(){

        $edit = [
            'send_email' => 'yes',
            'active_time' => '2 months',
            'email_template' => 'default',
            'request_time' => '2 days',
            'time_off_request_notification' => 'yes'
        ];

        $this->post('/settings', $edit);
        $this->assertResponseOk();
        $send = $this->Settings->getSetting('send_email');
        $active = $this->Settings->getSetting('active_time');
        $this->assertEquals('yes', $send);
        $this->assertEquals('2 months', $active);
    }

    public function testEditInvalid(){

        $edit = [
            'send_email' => 'foo'
        ];

        $this->post('/settings',$edit);
        $this->assertResponseOk();
        $this->assertResponseContains('Some settings could not be saved');
        $send = $this->Settings->getSetting('send_email');
        $this->assertNotEquals('foo', $send);
    }

    public function testUnexpectedSetting() {
        $edit = [
            'unexpected' => 'boo'
        ];

        $this->post('/settings',$edit);
        $this->assertResponseOk();
    }
}
?>