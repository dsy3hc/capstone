<?php

namespace App\Controller;

use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Email\Email;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;

// @codeCoverageIgnoreStart
if (!Configure::read('PHPUNIT')) {
    require(ROOT . '/config/secret.php');
}
// @codeCoverageIgnoreEnd

// the following two functions come from
// http://stackoverflow.com/questions/1846202/
// php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 0) return $min; // not so random...
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length=32) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

class UsersController extends AppController {
    public $components = ['Flash', 'RequestHandler'];

    public $paginate = [
        'limit' => 15, // display 15 results per page
        'order' => [
            'Users.email' => 'asc'
        ],
        'contain' => ['Roles'],
        'sortWhitelist' => [
            'email', 'clientID', 'Roles.name', 'expiration_date'
        ]
    ];

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        // Unauthenticated users may access the following pages
        $this->Auth->allow([
            'signup',
            'logout',
            'confirm',
            'reset_password',
        ]);
    }

    public function isAuthorized($user) {
        // All authenticated users may peform the following actions
        if (in_array($this->request->action, ['profile', 'edit_profile'])) {
            return true;
        }
        return parent::isAuthorized($user);
    }

    public function login() {
        // prevent logged in users from accessing login page
        if (!is_null($this->Auth->user('id'))) {
            $this->redirect('/');
        }
        $this->layout = 'landing';
        if ($this->request->is('post')) {
            // attempt to retrieve a user with the given email/password combination
            $user_array = $this->Auth->identify();
            if ($user_array) {
                // valid email/password combination
                $user = $this->Users->get($user_array['id']);
                if (!$user->is_email_confirmed) {
                    if (!$user->is_client_id_confirmed) {
                        // No Client ID and unconfirmed email
                        $this->Flash->error(__("Your account is being processed. In the meantime, please confirm your email."));
                    }
                    else {
                        // Client ID present but email still not confirmed
                        $this->Flash->error(__("We've processed your account but you still need to confirm your email."));
                    }
                }
                else if (!$user->is_client_id_confirmed) {
                    // Confirmed email but no Client ID
                    $this->Flash->error(__("Your account is still being processed."));
                }
                else {
                    // store redirect so that it doesn't get lost when we destroy the session
                    $redirect = $this->Auth->redirectUrl();
                    // destroying the session prevents users from being able to access the welcome page
                    $this->request->session()->destroy();

                    $this->Auth->setUser($user_array);
                    return $this->redirect($redirect);
                }
            }
            else {
                // invalid email/password combination - display error
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function signup() {
        $this->layout = 'landing';
        $user = $this->Users->newEntity($this->request->data, [
            'fieldList' => ['first_name', 'last_name', 'email', 'password', 'confirm', 'language', 'ridden_before']
        ]);
        $sorry = __('Thank you for your interest in riding JAUNT and using {0}
                to easily manage your trips. To use {0}, you must first set up a client profile
                with a JAUNT staff member. Please call {1} to complete this step before
                registering for {0}. Thanks!', [Configure::read('App.name'), Configure::read('JAUNT.phone_number')]);
        $this->set('sorry_message', $sorry);
        $this->set('display', 'hidden'); // hide the 'sorry' message by default

        if ($this->request->is('post')) {
            // @codeCoverageIgnoreStart
            if (!Configure::read('PHPUNIT')) {
                $verified = $this->verifyCaptcha($this->request->data['g-recaptcha-response']);
            }
            // @codeCoverageIgnoreEnd
            else {
                // set 'verified' = true when tests are running
                $verified = true;
            }
            if ($verified) {
                if (!$user->ridden_before) {
                    $this->set('display', ''); // display the 'sorry' message
                } else {
                    // generate a random key for email confirmation
                    $key = getToken();
                    $user->email_confirm_key = $key;

                    if ($this->Users->save($user, ['validate' => 'signup'])) {
                        $this->email_confirmation($user, $key);
                        $this->set('email', $user['email']);
                        $this->render('welcome');
                    } else {
                        $this->Flash->error(__('Please resolve the errors below'));
                    }
                }
            } else {
                // @codeCoverageIgnoreStart
                $this->Flash->error(__("There was an error when processing the reCAPTCHA response. Please try again."));
                // @codeCoverageIgnoreEnd
            }
        }

        // Insert the ReCAPTCHA key into the document
        $this->set('site_key', Configure::read('recaptcha_config')['site_key']);
        $this->set('user', $user);
    }

    public function index($filter = NULL) {
        $options = [];
        // build an array of roles with the format ['admin' => 'admin', ...]
        $roles_query = $this->Users->Roles->find('list')->toArray();
        $role_names = array_keys(array_flip($roles_query));
        $roles = array_combine($role_names, $role_names);

        if (!empty($this->request->query)) {
            if (array_key_exists('search', $this->request->query)) {
                $query = $this->request->query['search'];
                if (!empty($query)) {
                    $this->set('search', $query);
                    $search_options = [
                        'conditions' => [
                            'OR' => [
                                'email LIKE' => "%$query%",
                                'first_name LIKE' => "%$query%",
                                'last_name LIKE' => "%$query%",
                                'clientID' => $query,
                                'Roles.name' => $query
                            ]
                        ]
                    ];
                    $options = array_merge_recursive($options, $search_options);
                }
            }

            if (array_key_exists('filter', $this->request->query)) {
                $filter = $this->request->query['filter'];
                $filter_options = [];
                if ($filter == 'pending') {
                    $filter_options = [
                        'conditions' => [
                            'AND' => [
                                'clientID is' => null,
                                'Roles.name' => 'client',
                            ]
                        ]
                    ];
                }
                else if (array_key_exists($filter, $roles)) {
                    $filter_options = [
                        'conditions' => ['Roles.name' => $filter]
                    ];
                }
                $options = array_merge_recursive($options, $filter_options);
            }
        }

        // set view variables
        $this->set('query', $this->request->query);
        $this->set('pending', $filter == 'pending');
        $this->set('filter_options', $roles + ['pending' => 'pending']);
        try {
            $users = $this->paginate($this->Users->find('all', $options));
        }
        catch (NotFoundException $e) {
            // invalid page - redirect to first page
            $this->redirect([
                'action' => 'index',
                'filter' => $filter,
            ]);
        }
        $this->set(compact('users'));

        //call function that checks who is active or not
        $this->check_activity();
    }

    public function saveAll(){
        if($this->request->is('post')){
            $data=$this->request->data;
            foreach($data['User'] as $index){
                $id=$index['id']+0;
                $user=$this->Users->get($id);
                $user->set('clientID',$index['clientID']+0);
                $this->Users->save($user,['validate'=>'edit']);
            }
        }
        $this->redirect(['action'=>'index']);
    }

    public function add() {
        $user = $this->Users->newEntity($this->request->data, [
            'fieldList' => ['first_name', 'last_name', 'email', 'role_id', 'language', 'clientID', 'cat_disability_num', 'expiration_date']
        ]);
        if ($this->request->is('post')) {
            // store a random token as a temp password
            $user->password = getToken();
            $key = getToken();
            $user->password_reset_key = $key;

            if ($this->Users->save($user, ['validate' => 'add'])) {
                $this->email_account_setup($user, $key);
                $this->Flash->success(__('Account created'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $roles = $this->Users->Roles->find('list');
        $this->set('roles', $roles);
        $this->set('user', $user);
    }

    public function metrics() {
    // get a list of the last 12 months, including the current month
        $month = strtotime('first day of this month', time());
        $months[date("m Y", $month)] = date("M Y", $month);
        for ($i = 1; $i < 12; $i++) {
            $month = strtotime('first day of last month', $month);
            $months[date("m Y", $month)] = date("M Y", $month);
        }
        $graph_labels = array_reverse(array_values($months));
        $this->set('first_month', $graph_labels[0]);
        $this->set('last_month', $graph_labels[count($graph_labels) - 1]);

        $this->set('numUsers', $this->Users->find('all')->count());
        $this->loadModel('Reservations');
        $this->loadModel('Timeoff');
        $this->set('numReservations', $this->Reservations->find('all')->count());
        $this->set('pendingReservations', $this->Reservations->find('all')
                                                ->where(['Reservations.status' => 0])
                                                ->count()
                                            );
        $this->set('approvedReservations', $this->Reservations->find('all')
                                                ->where(['Reservations.status' => 1])
                                                ->count());
        $this->set('deniedReservations', $this->Reservations->find('all')
                                              ->where([ 'Reservations.status' => 2])
                                              ->count());
        $this->set('userReservations', "");
        $this->set('userActivity', "");
        $this->set('userName', "");
            
        $this->set('users', $this->Users->find('list', [
            'idField' => 'clientID', 
            'valueField' => 'full_name'
        ]));


        // ******** graph variables ********

        // get a list of the last 12 months, including the current month
        $month = strtotime('first day of this month', time());
        $months[date("m Y", $month)] = date("M Y", $month);
        for ($i = 1; $i < 12; $i++) {
            $month = strtotime('first day of last month', $month);
            $months[date("m Y", $month)] = date("M Y", $month);
        }
        $this->set('graph_labels', json_encode(array_reverse(array_values($months))));

        $twelve_months_ago = strtotime('first day of this month last year', time());
        $twelve_months_ago = date("Y-m-d 00:00:00", $twelve_months_ago);

        $query = $this->Reservations->find('all', [
            'fields' => ['month' => 'date_format(created_time, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Reservations.created_time >' => $twelve_months_ago],
            'group' => ['year(created_time)', 'month(created_time)']
        ]);
        $reservations = $this->mergeMonths($query, $months);
        $this->set('graph_reservation_points', json_encode([
            'Reservations' => $reservations
        ]));

        $query = $this->Users->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Users.created >' => $twelve_months_ago],
            'group' => ['year(created)', 'month(created)']
        ]);
        $users = $this->mergeMonths($query, $months);
        $this->set('graph_registration_points', json_encode([
            'Registrations' => $users
        ]));

        // 'sick' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'sick'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $sick = $this->mergeMonths($query, $months);

        // 'annual' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'annual'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $annual = $this->mergeMonths($query, $months);

        // 'bonus' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'bonus'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $bonus = $this->mergeMonths($query, $months);

        $this->set('graph_timeoff_points', json_encode([
            'Sick' => $sick,
            'Annual' => $annual,
            'Bonus' => $bonus
        ]));

    }

    public function graph(){

        $this->loadModel('Reservations');
        $this->loadModel('Timeoff');
        // ******** graph variables ********

        // get a list of the last 12 months, including the current month
        $month = strtotime('first day of this month', time());
        $months[date("m Y", $month)] = date("M Y", $month);
        for ($i = 1; $i < 12; $i++) {
            $month = strtotime('first day of last month', $month);
            $months[date("m Y", $month)] = date("M Y", $month);
        }
        $this->set('graph_labels', array_reverse(array_values($months)));

        $twelve_months_ago = strtotime('first day of this month last year', time());
        $twelve_months_ago = date("Y-m-d 00:00:00", $twelve_months_ago);

        $query = $this->Reservations->find('all', [
            'fields' => ['month' => 'date_format(created_time, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Reservations.created_time >' => $twelve_months_ago],
            'group' => ['year(created_time)', 'month(created_time)']
        ]);
        $reservations = $this->mergeMonths($query, $months);
        $this->set('graph_reservation_points', $reservations);

        $query = $this->Users->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Users.created >' => $twelve_months_ago],
            'group' => ['year(created)', 'month(created)']
        ]);
        $users = $this->mergeMonths($query, $months);
        $this->set('graph_registration_points', $users);

        // 'sick' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'sick'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $sick = $this->mergeMonths($query, $months);

        // 'annual' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'annual'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $annual = $this->mergeMonths($query, $months);

        // 'bonus' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'bonus'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $bonus = $this->mergeMonths($query, $months);

        $this->set('graph_timeoff_points', [
            'Sick' => $sick,
            'Annual' => $annual,
            'Bonus' => $bonus
        ]);

        if (Configure::read('PHPUNIT')) {
            return $this->redirect('/');
        }
// @codeCoverageIgnoreStart
    }
// @codeCoverageIgnoreEnd

    public function graph_csv(){

        $this->loadModel('Reservations');
        $this->loadModel('Timeoff');
        // ******** graph variables ********

        // get a list of the last 12 months, including the current month
        $month = strtotime('first day of this month', time());
        $months[date("m Y", $month)] = date("M Y", $month);
        for ($i = 1; $i < 12; $i++) {
            $month = strtotime('first day of last month', $month);
            $months[date("m Y", $month)] = date("M Y", $month);
        }
        $this->set('graph_labels', array_reverse(array_values($months)));

        $twelve_months_ago = strtotime('first day of this month last year', time());
        $twelve_months_ago = date("Y-m-d 00:00:00", $twelve_months_ago);

        $query = $this->Reservations->find('all', [
            'fields' => ['month' => 'date_format(created_time, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Reservations.created_time >' => $twelve_months_ago],
            'group' => ['year(created_time)', 'month(created_time)']
        ]);
        $reservations = $this->mergeMonths($query, $months);
        $this->set('graph_reservation_points', $reservations);

        $query = $this->Users->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => ['Users.created >' => $twelve_months_ago],
            'group' => ['year(created)', 'month(created)']
        ]);
        $users = $this->mergeMonths($query, $months);
        $this->set('graph_registration_points', $users);

        // 'sick' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'sick'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $sick = $this->mergeMonths($query, $months);

        // 'annual' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'annual'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $annual = $this->mergeMonths($query, $months);

        // 'bonus' requests
        $query = $this->Timeoff->find('all', [
            'fields' => ['month' => 'date_format(created, "%m %Y")', 'total' => 'count(*)'],
            'conditions' => [
                'Timeoff.created >' => $twelve_months_ago,
                'Timeoff.request_type' => 'bonus'
            ],
            'group' => ['year(created)', 'month(created)']
        ]);
        $bonus = $this->mergeMonths($query, $months);

        $this->set('graph_timeoff_points', [
            'Sick' => $sick,
            'Annual' => $annual,
            'Bonus' => $bonus
        ]);

        $this->layout = null;

        $this->autoLayout = false;
    }

    private function mergeMonths($query, $months) {
        $count_per_month = [];
        foreach ($query as $row) {
            $count_per_month[$row['month']] = $row['total'];
        }

        $combined = [];
        foreach ($months as $numeric => $name) {
            $month_has_count = array_key_exists($numeric, $count_per_month);
            if ($month_has_count) {
                $combined[$name] = intval($count_per_month[$numeric]);
            }
            else {
                $combined[$name] = 0;
            }
        }

        $combined = array_reverse($combined);

        return array_values($combined);
    }

    public function confirm($key = null) {
        if ($this->request->is(['get'])) {
            if (!$key) {
                return $this->redirect(['action' => 'login']);
            }
            // find the user corresponding to the key (if any)
            $user = $this->Users->findAllByEmailConfirmKey($key)->first();
            if (!$user) {
                return $this->redirect(['action' => 'login']);
            }
            if (isset($key) && $user) {
                // record current time
                $user->email_confirm_date = date("Y-m-d H:i:s");
                // record ip address of request to confirm
                $user->email_confirm_ip = $this->request->clientIp();
                // delete the key
                $user->email_confirm_key = null;
                $this->Users->save($user, ['validate' => false]);
                $this->Flash->success(__('Email confirmed. You can now login below.'));
            }
        }
        return $this->redirect(['action' => 'login']);
    }

    public function reset_password($key = null) {
        $this->layout = 'password';
        if (!$key) {
            // display the reset password form
            $this->reset_password_request();
        }
        else {
            // key is present - process the key
            $this->process_password_reset($key);
        }
    }

    private function process_password_reset($key) {
        $user = $this->Users->findAllByPasswordResetKey($key)->first();
        if (!$user) {
            // key doesn't correspond to any user
            // strip off bad key and redirect to password reset form
            $this->redirect(['action' => 'reset_password']);
        }

        if ($this->request->is(['post'])) {
            $data = $this->request->data;
            if ($data['password'] != $data['confirm_password']) {
                $this->Flash->error(__('Passwords do not match'));
                return;
            }
            $user->password = $data['password'];
            $user->password_reset_key = null;
            if (!$user->is_email_confirmed) {
                // also confirm email while we're at it
                $user->email_confirm_date = date("Y-m-d H:i:s");
                $user->email_confirm_ip = $this->request->clientIp();
                $user->email_confirm_key = null;
            }
            $this->Users->save($user, ['validate' => false]);
            $this->Flash->success(__('Password Updated'));
            return $this->redirect(['action' => 'login']);
        }
    }

    private function reset_password_request() {
        if ($this->request->is(['post'])) {
            $email = array_key_exists('email', $this->request->data) ? $this->request->data['email'] : null;
            $user = $this->Users->findAllByEmail($email)->first();
            if (isset($email) && $user) {
                $key = getToken();
                $user->password_reset_key = $key;
                $this->Users->save($user, ['validate' => false]);

                $this->email_reset_password($user, $key);
                $this->set('email', $user['email']);
                $this->render('reset_password_confirm');
            }
            else {
                $this->Flash->error(__('Please provide the email address you use to log in'));
            }
        }
        $this->render('reset_password_form');
    }

    public function profile(){
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id);
        $clientID = $user->clientID;
        $this->set('user_role', $user->role_id);
        $this->set('user', $user);

        $lastActivity = $this->activityForUser($user);
        $this->set('Last_Activity', $lastActivity);


        $this->loadModel('Reservations');
        $this->paginate = [
            'limit' => 5,
            'order' => [
                'Reservations.pick_up_day' => 'asc'
            ]
        ];

        $pending_reservations = $this->Reservations->find()
                                                  ->where(['clientID ='=> $clientID,
                                                           'Reservations.status' => 0
                                                            ])
                                                  ->order(['pick_up_day'=>'asc']);
        $this->set('reservations', $this->paginate($pending_reservations));

        $numReservations = $this->Reservations->find()
                                                         ->where(['status =' => 1,
                                                                  'clientID ='=>$clientID])
                                                         ->count(); 
        $now = Time::now();
        $months_active = $now->diff($this->Users->get($id)['created'])->m;
        if($numReservations == 0) {
            $this->set('resPerMonth', 0);
        } else {
            $this->set('resPerMonth', $numReservations / ($months_active + 1));
        }

        $this->loadModel('Timeoff');
        $pending_timeoff = $this->Timeoff
            ->find()
            ->where(['status' => 0,
                    'user_id'=> $id])
            ->order(['created' => 'DESC']);
        $this->set('pending_timeoff',$pending_timeoff);
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }

        $user = $this->Users->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Users->patchEntity($user, $this->request->data, [
                'fieldList' => ['first_name', 'last_name', 'role_id', 'language', 'clientID', 'cat_disability_num', 'expiration_date']
            ]);
            if (array_key_exists('role_id', $this->request->data) &&
                $this->request->data['role_id'] != 2) {
                // might be switching user from client to employee,
                // make sure that client id is null
                $user->clientID = null;
            }
            if ($this->Users->save($user, ['validate' => 'edit'])) {
                $user->checkCATCertification('<=', true);
                $this->Flash->success(__('User updated'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update user'));
        }
        $roles = $this->Users->Roles->find('list');
        $this->set('roles', $roles);
        $this->set('user', $user);
	}
        
        public function edit_profile() {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id);
        if ($this->request->is(['post', 'put'])) {
            if($this->request->data['password'] == $this->request->data['cPassword']){
                $this->Users->patchEntity($user, $this->request->data);
                $user->set('modified',time());
                if (array_key_exists('email', $this->request->data)) {
                    $user->set('email', $this->request->data['email']);
                }
                if (array_key_exists('language', $this->request->data)) {
                    $user->set('language', $this->request->data['language']);
                }
                if ($this->request->data['password'] != ''){
                    $user->set('password', $this->request->data['password']);
                }

                if ($this->Users->save($user, ['validate' => 'edit'])) {
                    $this->Flash->success(__('Profile Updated'));
                    return $this->redirect(['action' => 'profile']);
                }
                $this->Flash->error(__('We were unable to update your profile'));
            } else {
                $this->Flash->error(__('Passwords do not match'));
            }
        }
        $this->set('user', $user);
	}
	
	public function delete($id) {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->get($id);
        $this->Users->delete($user);
        $this->Flash->success(__('The user with id: {0} has been deleted.', h($id)));
        return $this->redirect(['action' => 'index']);
    }

    private function email_confirmation($user, $key) {
        // send an email with the link
        $email = new Email('default');
        $email->template('email_confirmation')
            ->emailFormat('html')
            ->viewVars( array(
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'key' => $key
            ))
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($user['email'])
            ->subject('Welcome to JAUNT '. Configure::read('App.name'));

        // @codeCoverageIgnoreStart
        if (!Configure::read('PHPUNIT')) {
            $email->send();
        }
        // @codeCoverageIgnoreEnd
    }

    private function email_account_setup($user, $key) {
        // send an email to the user letting them know they need to
        // setup a password for their account
        $email = new Email('default');
        $email->template('password_setup')
            ->emailFormat('html')
            ->viewVars( array(
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'key' => $key
            ))
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($user['email'])
            ->subject(__('JAUNT {0} Account Setup', Configure::read('App.name')));

        // @codeCoverageIgnoreStart
        if (!Configure::read('PHPUNIT')) {
            $email->send();
        }
        // @codeCoverageIgnoreEnd
    }

    private function email_reset_password($user, $key) {
        // send an email with the link
        $email = new Email('default');
        $email->template('password_reset')
            ->emailFormat('html')
            ->viewVars( array(
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'key' => $key
            ))
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($user['email'])
            ->subject(__('Password reset for JAUNT {0}', Configure::read('App.name')));

        // @codeCoverageIgnoreStart
        if (!Configure::read('PHPUNIT')) {
            $email->send();
        }
        // @codeCoverageIgnoreEnd
    }
    public function check_activity(){
        /* check if member is active or not list */
        $users = $this->Users->find('all');
        $lastActivities = array();

        // get the 'active_time' setting from the settings table
        $this->loadModel('Settings');
        $settings = $this->Settings->get(2);
        $active_time = $settings['value'];

        foreach ($users as $user){
            $lastActivity = $this->activityForUser($user);

            if ($lastActivity->wasWithinLast($active_time)){
                $lastActivities[$user->clientID] = "active";
            }
            else {
                $lastActivities[$user->clientID] = "inactive";
            }
       }
       $this->set('activities', $lastActivities);
    }

    private function activityForUser($user) {
        $lastLogin = $user->modified;
        $lastActivity = $lastLogin;

        $this->loadModel('Reservations');
        $lastReserved = $this->Reservations->find()->where(['clientID ='=>$user->clientID])->order(['created_time'=>'DESC'])->first();
        if($lastReserved){
            $lastActivity = max($lastActivity, $lastReserved->created_time);
        }

        $this->loadModel('Timeoff');
        $lastTimeOff = $this->Timeoff->find()->where(['user_id ='=>$user->id])->order(['created'=>'DESC'])->first();
        if($lastTimeOff){
            $lastActivity = max($lastActivity, $lastTimeOff->created);
        }

        return $lastActivity;
    }

    /**
     * @codeCoverageIgnore
     */
    private function verifyCaptcha($response) {
        $config = Configure::read('recaptcha_config');
        $secret = $config['secret'];
        $verification_url = 'https://www.google.com/recaptcha/api/siteverify';

        $http = new Client();
        $response = $http->post($verification_url, ['secret' => $secret, 'response' => $response]);
        $json = $response->json;
        return $json['success'] == true;
    }
}
?>
