<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;

use DateTime;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'app.users',
		'app.roles',
		'app.cat_certifications'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
		$this->Users = TableRegistry::get('Users', $config);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Users);

		parent::tearDown();
	}

    public function testCheckCatCertificationExact30() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 30 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('=', true);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationExact60() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 60 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('=', false);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationExact90() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 90 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('=', false);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationInexact() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 15 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('=', false);
        $this->assertEquals($expiring, false);
    }

    public function testCheckCatCertificationLessThan30() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 15 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('<=', false);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationLessThan60() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 45 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('<=', false);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationLessThan90() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 83 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $expiring = $user->checkCATCertification('<=', false);
        $this->assertEquals($expiring, true);
    }

    public function testCheckCatCertificationNullExpiration() {
        $user = $this->Users->get(2);
        $expiring = $user->checkCATCertification('<=', false);
        $this->assertEquals($expiring, false);
    }

    public function testcheckCatCertificationInvalidComparison() {
        $user = $this->Users->get(2);
        $exp_date = new DateTime('today + 83 days');
        $user->expiration_date = $exp_date;
        $this->Users->save($user);

        $this->setExpectedException('Exception');
        $user->checkCATCertification('foo', false);
    }

}
