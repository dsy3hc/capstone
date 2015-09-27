<?php
namespace App\Test\TestCase\Controller;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use DateTime;

class UsersControllerTest extends IntegrationTestCase {
	public $fixtures = [
		'app.users',
		'app.roles',
		'app.reservations',
        'app.timeoff',
		'app.settings',
		'app.cat_certifications'
    ];

	public function setUp() {
		parent::setUp();
		$this->Users = TableRegistry::get('Users');
        $this->Timeoff = TableRegistry::get('Timeoff');
	}

	private function loginAsAdmin() {
		$user = $this->Users->get(1);
		$this->session(['Auth.User'=> $user->toArray()]);
	}

    private function loginAsClient() {
        $client = $this->Users->get(2);
        $this->session(['Auth.User'=> $client->toArray()]);
    }

    private function loginAsScheduler() {
        $user = $this->Users->get(9);
        $this->session(['Auth.User'=> $user->toArray()]);
    }

	private function logout() {
		$this->session(['Auth.User' => null]);
	}

	public function testLoginWithValidUser() {
		$user = [
			'email' => 'client@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('/users/login', $user);
		$this->assertSession($user['email'], 'Auth.User.email');
		$this->assertRedirect('/');
	}

	public function testLoginWithInvalidCombination() {
		$user = [
			'email' => 'client@ridejaunt.org',
			'password' => 'abc'
				];
		$this->post('/users/login', $user);
		$this->assertSession(null, 'Auth.User');
		$this->assertResponseContains('Login');
	}

	public function testLoginWithMissingEmail() {
		$user = [
			'email' => '',
			'password' => 'pass'
				];
		$this->post('/users/login', $user);
		$this->assertSession(null, 'Auth.User');
		$this->assertResponseContains('Login');
	}

	public function testLoginWithMissingPassword() {
		$user = [
			'email' => 'client@ridejaunt.org',
			'password' => ''
				];
		$this->post('/users/login', $user);
		$this->assertSession(null, 'Auth.User');
		$this->assertResponseContains('Login');
	}

	public function testLoginWhileLoggedIn() {
		$user = [
			'email' => 'client@ridejaunt.org',
			'password' => 'pass'
				];
        $this->loginAsAdmin();
		$this->post('/users/login', $user);
		$this->assertRedirect('/');

	}

//    public function testLoginWithoutClientID() {
//        $user = [
//            'email' => 'client2@ridejaunt.org',
//            'password' => 'pass'
//        ];
//        $this->post('/users/login', $user);
//        $this->assertSession(null, 'Auth.user');
//        $this->assertResponseContains('Your account is still being processed');
//    }
//
//    public function testLoginNoClientIdNoConfirmedPassword() {
//        $user = [
//            'email' => 'client1@ridejaunt.org',
//            'password' => 'pass'
//        ];
//        $this->post('/users/login', $user);
//        $this->assertSession(null, 'Auth.user');
//        $this->assertResponseContains('Your account is being processed. In the meantime, please confirm your email');
//    }

    public function testLogout() {
        $this->loginAsAdmin();
        $this->get('/users/logout');
        $this->assertSession(null, 'Auth.User');
        $this->assertRedirect(['controller' => 'users', 'action' => 'login']);
    }

	public function testSignUpNotRidden() {
		$user = [
			'first_name' => 'Thomas',
			'last_name' => 'Jefferson',
			'email' => 'tj@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => false
        ];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('you must first set up a client profile');
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignUpNoFirstName() {
		$user = [
			'first_name' => '',
			'last_name' => 'Jefferson',
			'email' => 'tj@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('This field cannot be left empty');
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignUpNoLastName() {
		$user = [
			'first_name' => 'Thomas',
			'last_name' => '',
			'email' => 'tj@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('This field cannot be left empty');
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignUpExtraField() {
		$user = [
			'first_name' => 'Thomas',
			'last_name' => 'Jefferson',
			'email' => 'tj@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true,
			'role_id' => 1 // try to sign up with admin privileges
				];
		$this->post('/users/signup', $user);
		$user = $this->Users->find()->where(['email' => $user['email']])->first();

		// user should be signed up with a role_id == 2 (client)
		$this->assertEquals(2, $user->role_id, 'User was able to set their role');
	}



	public function testSignupWithDuplicateEmail() {
		$user = [
			'first_name' => 'Jeff',
			'last_name' => 'Thomas',
			'email' => 'jt@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('That email is taken');
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(1, $query->count());
	}

	public function testSignupWithMismatchedPasswords() {
		$user = [
			'first_name' => 'Thomas',
			'last_name' => 'Jefferson',
			'email' => 'client12@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'ssap',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('Those passwords don&#039;t match'); //html encoding
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignupWithNoEmail() {
		$user = [
			'first_name' => 'Teresa',
			'last_name' => 'Sullivan',
			'email' => '',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('This field cannot be left empty');
		$query = $this->Users->find()->where(['last_name' => $user['last_name'],
				'first_name' => $user['first_name']
				]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignupWithBadEmail() {
		$user = [
			'first_name' => 'Teresa',
			'last_name' => 'Sullivan',
			'email' => 'clientridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('E-mail must be valid');
		$query = $this->Users->find()->where(['last_name' => $user['last_name'],
				'first_name' => $user['first_name']
				]);
		$this->assertEquals(0, $query->count());
	}

	public function testSignupWithAllValidInputs() {
		$user = [
			'first_name' => 'Thomas',
			'last_name' => 'Jefferson',
			'email' => 'client2@ridejaunt.org',
			'password' => 'pass',
			'confirm' => 'pass',
			'ridden_before' => true 
				];
		$this->post('/users/signup', $user);
		$this->assertResponseContains('Thank you for your interest');
		$query = $this->Users->find()->where(['email' => $user['email']]);
		$this->assertEquals(1, $query->count());
	}
	//Client ID/Email verified login Tests

	public function testNoClientIDNoEmail(){
		$user = [
			'email' => 'client1@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('users/login', $user);
		$this->assertResponseContains('Your account is being processed. In the meantime, please confirm your email');
	}
	public function testNoClientIDEmail(){
		$user = [
			'email' => 'client2@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('users/login', $user);
		$this->assertResponseContains('Your account is still being processed');
	}	
	public function testClientIDNoEmail(){
		$user = [
			'email' => 'client3@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('users/login', $user);
		$this->assertResponseContains("processed your account but you still need to confirm your email");
	}
	public function testClientIDEmail(){
		$user = [
			'email' => 'client4@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('users/login', $user);
		$this->assertSession($user['email'], 'Auth.User.email','User should have logged in.');
		$this->assertRedirect('/');
	}

    public function testGetEdit() {
        $this->loginAsAdmin();
        $this->get('/users/edit/1');
        $this->assertResponseOk();
    }

    public function testGetEditNoId() {
        $this->loginAsAdmin();
        $this->get('/users/edit');
        $this->assertResponseError();
    }

	public function testEditFirstName() {
		$this->loginAsAdmin();

		$data = [
			'first_name' => 'James'
			];
		$this->post('/users/edit/1', $data);
		$this->assertResponseOk();
		$this->assertRedirect('/users');
		$fname = $this->Users->get(1)->first_name;
		$this->assertEquals($fname, $data['first_name']);

		$this->logout();
	}

    public function testEditEmployee() {
        $this->loginAsAdmin();
        $data = [
            'role_id' => '1'
        ];
        $this->post('users/edit/9', $data);
        $this->assertResponseOk();
        $this->assertRedirect('/users');
        $employee = $this->Users->get(9);
        $this->assertEquals(null, $employee->clientID);
        $this->assertEquals(1, $employee->role_id);
    }

    public function testEditInvalidRole() {
        $this->loginAsAdmin();
        $data = [
            'role_id' => '999'
        ];
        $this->post('users/edit/9', $data);

        $this->assertResponseOk();
        $this->assertResponseContains("Unable to update user");

        $employee = $this->Users->get(9);
        $this->assertNotEquals(999, $employee->role_id);
    }

	public function testEditEvilAdmin() {
		$this->loginAsAdmin();
		$data = [
			"password" => "gg no re",
			];
		$this->post('users/edit/2', $data);

		$this->logout();

		// make sure that old password still works
		$user = [
			'email' => 'client@ridejaunt.org',
			'password' => 'pass'
				];
		$this->post('users/login', $user);
		$this->assertSession($user['email'], 'Auth.User.email',"Admin was able to change user's password");
		$this->assertRedirect('/');
	}

    public function testSaveAll() {
        $this->loginAsAdmin();

        $data = [
            'User' => [
                0 => [
                    'id' => 4,
                    'clientID' => 22904
                ]
            ]
        ];
        $this->post('/users/saveAll', $data);
        $this->assertResponseOk();

        $client_id = $this->Users->get(4)->clientID;
        $this->assertEquals(22904, $client_id);
    }

    public function testGetAdd() {
        $this->loginAsAdmin();
        $this->get('/users/add');
        $this->assertResponseOk();
    }

    public function testAddEmailTaken() {
        $this->loginAsAdmin();
        $data = [
            'first_name' => 'Tommy',
            'last_name' => 'J',
            'email' => 'hourly@ridejaunt.org',
            'role_id' => 3
        ];
        $this->post('/users/add', $data);
        $this->assertResponseOk();
        $this->assertResponseContains("email is taken");
    }

    public function testAddNoRole() {
        $this->loginAsAdmin();
        $data = [
            'first_name' => 'Tommy',
            'last_name' => 'Jeff',
            'email' => 'hourly999@ridejaunt.org',
        ];
        $this->post('/users/add', $data);
        $this->assertResponseOk();
        $user = $this->Users->findAllByEmail('hourly999@ridejaunt.org')->first();
        $this->assertNotEquals($user, null);
    }

	public function testAddHourlyEmployee() {
		$this->loginAsAdmin();

		$user = [
			'first_name' => 'Tommy',
			'last_name' => 'J',
			'email' => 'hourly2@ridejaunt.org',
			'role_id' => 3
        ];
		$this->post('/users/add', $user);
		$this->assertResponseOk();
		$this->assertRedirect(['controller' => 'users', 'action' => 'index']);
		$email = $this->Users->find()
			->where(['email' => 'hourly2@ridejaunt.org'])
			->first()
			->email;
		$this->assertEquals($email, $user['email']);

		$this->logout();
	}

	public function testGetIndex() {
        // update the created time for the first request to be something recent
        $recent = new DateTime('now - 3 days');
        $request = $this->Timeoff->get(1);
        $request->created = $recent;
        $this->Timeoff->save($request);

		$this->loginAsAdmin();
		$this->get('/users');
		$this->assertResponseOk();
		$this->logout();
	}

    public function testGetIndexNoUsers() {
        $this->loginAsAdmin();

        $users = $this->Users->find('all');
        foreach ($users as $user) {
            $this->Users->delete($user);
        }

        $this->get('/users');
        $this->assertResponseOk();
    }

	//find better assert and fix url
	public function testIndexWithAdminFilter() {
		$this->loginAsAdmin();
		$this->post('/users?filter=admin');
		$this->assertResponseOk();
		$this->logout();
	}

	//find better assert and fix url
	public function testIndexWithPendingFilter() {
		$this->loginAsAdmin();
		$this->post('/users?filter=pending');
		$this->assertResponseOk();
		$this->logout();
	}

	//find better assert and fix url
	public function testIndexWithFilterSearch() {
		$this->loginAsAdmin();
		//$query = ['filter' => 'Admin', 'search' => 'Thomas'];
		$this->post('/users?search=thomas&filter=admin');
		$this->assertResponseOk();
		$this->logout();
	}

    public function testIndexWithInvalidPage() {
        $this->loginAsAdmin();
        $this->get('/users?filter=admin&page=10');
        $this->assertRedirect([
           'controller' => 'users',
            'action' => 'index',
            'filter' => 'admin'
        ]);
    }

	public function testMetricLoadAsAdmin() {
		$this->loginAsAdmin();
		$this->post('/users/metrics/');
		$this->assertResponseOk();
		$this->logout();
	}

    public function testGetMetricsNoUsers() {
        $this->loginAsAdmin();

        $users = $this->Users->find('all');
        foreach ($users as $user) {
            $this->Users->delete($user);
        }

        $this->get('/users/metrics/');
        $this->assertResponseOk();
    }

    public function testMetricsGraph() {
        $this->loginAsAdmin();
        $this->get('/users/graph.pdf');
        $this->assertResponseOk();
    }

    public function testGetCSV() {
        $this->loginAsAdmin();
        $this->get('/users/graph_csv.csv');
        $this->assertResponseOk();
    }

	public function testConfirmNoKey() {
		$this->get('users/confirm');
		$this->assertResponseOk();
		$this->assertRedirect('/users/login');
	}
	
	public function testConfirmKeyNoUser() {
		$this->get('users/confirm/notarealkey');
		$this->assertResponseOk();
		$this->assertRedirect('/users/login');
	}

	public function testConfirmKeyUser() {
		$this->get('users/confirm/M2154Cm7hmit4aFS83qYxjDYWSpEnLjz');
		$this->assertResponseOk();
		//assert success
		$this->assertRedirect('/users/login');
	}

    public function testConfirmPost() {
        $this->post('users/confirm');
        $this->assertResponseOk();
    }

	public function testResetPasswordWithoutKey() {
		$this->post('users/reset_password/');
		$this->assertResponseOk();
	}

    // tries to use a key that doesn't correspond to any user
    public function testResetPasswordWithBadKey() {
        $this->get('users/reset_password/foo');
        $this->assertResponseOk();
        $this->assertRedirect('users/reset_password');
    }

	public function testResetPasswordWithKey() {
        $data = [
            'password' => 'jaunt',
            'confirm_password' => 'jaunt'
        ];
		$this->post('users/reset_password/1', $data);
		$this->assertResponseOk();

        // try logging in with new password
        $user = [
            'email' => 'client4@ridejaunt.org',
            'password' => 'jaunt'
        ];
        $this->post('/users/login', $user);
        $this->assertSession($user['email'], 'Auth.User.email');
        $this->assertRedirect('/');
	}

    public function testResetPasswordMismatchedPasswords() {
        $data = [
            'password' => 'asdf',
            'confirm_password' => 'jkl;'
        ];
        $this->post('users/reset_password/1', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('Passwords do not match');

        // ensure that you cannot login with either of the passwords
        $user = [
            'email' => 'client4@ridejaunt.org',
            'password' => 'asdf'
        ];
        $this->post('/users/login', $user);
        $this->assertSession(null, 'Auth.User.email');
        $this->assertResponseContains('Login');

        $user = [
            'email' => 'client4@ridejaunt.org',
            'password' => 'jkl;'
        ];
        $this->post('/users/login', $user);
        $this->assertSession(null, 'Auth.User.email');
        $this->assertResponseContains('Login');
    }

    public function testResetPasswordWithUnconfirmedEmail() {
        // password should be reset and the user's email should also
        // be confirmed
        $data = [
            'password' => 'jaunt',
            'confirm_password' => 'jaunt'
        ];
        $this->post('users/reset_password/128', $data);
        $this->assertResponseOk();

        // try logging in with new password
        $user = [
            'email' => 'client5@ridejaunt.org',
            'password' => 'jaunt'
        ];
        $this->post('/users/login', $user);
        $this->assertSession($user['email'], 'Auth.User.email');
        $this->assertRedirect('/');

        // make sure email is confirmed
        $user = $this->Users->findAllByEmail('client5@ridejaunt.org')->first();
        $this->assertNotEquals($user->email_confirm_date, null);
    }

    public function testRequestPasswordReset() {
        $data = [
            'email' => 'client@ridejaunt.org'
        ];
        $this->post('/users/reset_password', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('A password reset link has been sent');
    }

    public function testGetProfile() {
        $this->loginAsClient();
        $this->get('/users/profile');
        $this->assertResponseOk();
    }

    public function testGetProfileAdmin() {
        $this->loginAsAdmin();
        $this->get('/users/profile');
        $this->assertResponseOk();
    }

    public function testGetProfileScheduler() {
        $this->loginAsScheduler();
        $this->get('/users/profile');
        $this->assertResponseOk();
    }

    public function testGetProfileNoReservations() {
        $client = $this->Users->get(3);
        $this->session(['Auth.User'=> $client->toArray()]);
        $this->get('/users/profile');
        $this->assertResponseOk();
        $this->assertResponseContains('0');
    }

	public function testProfileAdmin() {
		$this->loginAsAdmin();
		$this->post('/');
		$this->assertResponseOk();
		//assert no reservation/timeoff (check html generated by paginate)
		$this->logout();
	}
	
	public function testProfileEditAdminNoChange() {
		$this->loginAsAdmin();
		$this->get('users/edit_profile');
		$this->assertResponseOk();
		$this->logout();
	}

    public function testEditPassword() {
        $this->loginAsClient();

        $data = [
            'password' => 'jaunt!',
            'cPassword' => 'jaunt!',
            'email' => 'client@ridejaunt.org'
        ];
        $this->post('/users/edit_profile', $data);
        $this->assertResponseOk();

        $this->logout();
        $user = [
            'email' => 'client@ridejaunt.org',
            'password' => 'jaunt!'
        ];
        $this->post('/users/login', $user);
        $this->assertSession($user['email'], 'Auth.User.email');
    }

    public function testEditProfileLanguage() {
        $this->loginAsClient();
        $data = [
            'password' => 'jaunt!',
            'cPassword' => 'jaunt!',
            'email' => 'client@ridejaunt.org',
            'language' => 'en_US'
        ];
        $this->post('/users/edit_profile', $data);
        $this->assertResponseOk();
        $user = $this->Users->get(2);
        $this->assertEquals('en_US', $user->language);
    }

    public function testEditProfileMismatchedPasswords() {
        $this->loginAsClient();

        $data = [
            'password' => 'jaunt!',
            'cPassword' => 'foo',
            'email' => 'client@ridejaunt.org'
        ];
        $this->post('/users/edit_profile', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('Passwords do not match');

        $this->logout();
        $user = [
            'email' => 'client@ridejaunt.org',
            'password' => 'jaunt!'
        ];
        $this->post('/users/login', $user);
        $this->assertSession(null, 'Auth.User.email');
    }

    // TODO: clients should have to reconfirm their email
    public function testEditEmailAddress() {
        $this->loginAsClient();

        $data = [
            'password' => '',
            'cPassword' => '',
            'email' => 'jauntR00lz@gmail.com'
        ];
        $this->post('/users/edit_profile', $data);
        $this->assertResponseOk();

        $user = $this->Users->get(2);
        $this->assertEquals('jauntR00lz@gmail.com', $user->email);
    }

    // tries to change email to one that is taken by another user
    public function testEditEmailAddressTaken() {
        $this->loginAsClient();

        $data = [
            'password' => '',
            'cPassword' => '',
            'email' => 'client2@ridejaunt.org'
        ];
        $this->post('/users/edit_profile', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('That email is taken');
    }

	public function testDeleteAsAdmin() {
		$this->loginAsAdmin();
		$this->post('users/delete/8');
		$this->assertResponseOk();
		$query = $this->Users->find()->where(['id' => 8	]);
		$this->assertEquals(0, $query->count());
		$this->logout();
	}
}
