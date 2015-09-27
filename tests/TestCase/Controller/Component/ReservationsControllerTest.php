<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ReservationsController;
use App\Controller\UsersController;
use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\Core\Configure;

use DateTime;

class ReservationsControllerTest extends IntegrationTestCase {
    public $fixtures = [
        'app.reservations',
        'app.users',
	'app.settings'
    ];
    public $components = ['Auth'];

    private function loginAsAdmin() {
	$user = $this->Users->get(1);
	$this->session(['Auth.User'=> $user->toArray()]);
    }

    private function loginAsClient() {
	$user = $this->Users->get(2);
	$this->session(['Auth.User'=> $user->toArray()]);
    }

    private function loginAsScheduler() {
	$user = $this->Users->get(9);
	$this->session(['Auth.User'=> $user->toArray()]);
    }

    public function setUp() {
        parent::setUp();
        $this->Reservations = TableRegistry::get('Reservations');  
        $this->Users = TableRegistry::get('Users');
        $user = $this->Users->get(1);
        $this->session(['Auth.User'=> $user->toArray()]);
  

    }

    public function testReservationWithValidReservation() {
        $trip_day = new DateTime('now + 3 days');
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => $trip_day->format('Y-m-d'),
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 50,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        //$this->post('/users/login', $user);
        $this->post('/reservations/reserve', $reservation);
        $this->assertResponseOk();
        $this->assertRedirect('/reservations');
        
        //check that value is inserted into database
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(1,$query->count());
        //check after sucessfull insertion it sends back to view all reservations

    }

    public function testReservationWithValidOneWayReservation() {
        $trip_day = new DateTime('now + 3 days');
        $reservation = [
            'created_time' => '2015-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => $trip_day->format('Y-m-d'),
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 571,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 1
        ];
        //$this->post('/users/login', $user);
        $this->post('/reservations/reserve', $reservation);
        $this->assertResponseOk();
        $this->assertRedirect('/reservations');
        
        //check that value is inserted into database
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum'], 'one_way =' => $reservation['one_way']]);
        $this->assertEquals(1,$query->count());
        //check after sucessfull insertion it sends back to view all reservations

    }
    public function testValidReservationWithDoctorsAppointment() {
        $trip_day = new DateTime('now + 3 days');
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 1,
            'pick_up_day' => $trip_day->format('Y-m-d'),
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 50, 
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        //$this->post('/users/login', $user);
        $this->post('/reservations/reserve', $reservation);
        $this->assertResponseOk();
        $this->assertRedirect('/reservations');
        
        //check that value is inserted into database
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(1,$query->count());
        //check after sucessfull insertion it sends back to view all reservations

    }


    public function testReservationWithMissingFirstName() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name'=> NULL,
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 52, 
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
   
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
        //$this->assertResponseContains('Reserve');
        //$this->assertRedirect('/reservations/reserve');

    }
    public function testReservationWithMissingLastName() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name'=> 'Thomas',
            'last_name' => NULL,
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 52,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
        //$this->assertResponseContains('Reserve');
        //$this->assertRedirect('/reservations/reserve');

    }


    public function testReservationWithMissingPickUpAddress() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=> NULL,
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 53,
            'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }

    public function testReservationWithMissingPickUpCity() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> NULL,
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 54,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0 
        ];
               
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }
    public function testReservationWithMissingZipcode() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'JPA',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> NULL,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 54, 
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
               
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }
    public function testReservationWithMissingDropOffAddress() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'a',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> NULL,
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 55,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }
    public function testReservationWithMissingDropOffCity() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'a',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Rice',
            'drop_off_unit' => null,
            'drop_off_city'=> NULL,
            'drop_off_zip'=> 22902,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 56,
            'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }
    public function testReservationWithMissingDropOffZipcode() {
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'a',
            'pick_up_unit' => null,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Rice',
            'drop_off_unit' => null,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> NULL,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 56,
	    'status'=> 0,
	    'comments'=> 'yet another comment',
	    'physicians'=> 0,
	    'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }

    public function testReserveWithPickupAfterReturn() {
        $trip_day = new DateTime('now + 3 days');
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => $trip_day->format('Y-m-d'),
            'pick_up_time'=> [
                'hour' => 12,
                'minute' => 36,
                'meridian' => 'pm'
            ],
            'pick_up_address'=>'Address',
            'pick_up_unit' => null,
            'pick_up_city'=> 'Charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Rice',
            'drop_off_unit' => null,
            'drop_off_city'=> 'Charlottesville',
            'drop_off_zip'=> 22903,
            'return_time'=> [
                'hour' => 10,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'bookingID'=> 0,
            'bookingNum'=> 64,
            'comments'=> '',
            'physicians'=> 0,
            'children'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $this->assertResponseContains("Return time must occur after pickup time");
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(0,$query->count());
    }

    public function testReserveWithWillCall() {
        $this->loginAsClient();
        $trip_day = new DateTime('now + 3 days');
        $reservation = [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'doctors_appointment' => 0,
            'pick_up_day' => $trip_day->format('Y-m-d'),
            'pick_up_time'=> [
                'hour' => 8,
                'minute' => 36,
                'meridian' => 'am'
            ],
            'pick_up_address'=>'Address',
            'pick_up_unit' => null,
            'pick_up_city'=> 'Charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Rice',
            'drop_off_unit' => null,
            'drop_off_city'=> 'Charlottesville',
            'drop_off_zip'=> 22903,
            'return_time'=> null,
            'willcall' => '1',
            'bookingID'=> 0,
            'bookingNum'=> 64,
            'comments'=> '',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0
        ];
        $this->post('/reservations/reserve', $reservation);
        $this->assertResponseOk();
        $query = $this->Reservations->find()->where(['bookingNum =' => $reservation['bookingNum']]);
        $this->assertEquals(1, $query->count());
    }

    public function testIndexLoadAsAdmin() {
	$this->loginAsAdmin();
	$this->post('/reservations/');
	$this->assertResponseOk();
    }

    public function testIndexLoadAsScheduler() {
	$this->loginAsScheduler();
	$this->post('/reservations/');
	$this->assertResponseOk();
    }

    public function testPendingReservationsLoadAsAdmin() {
	$this->loginAsAdmin();
	$this->post('/reservations/pending/');
	$this->assertResponseOk();
    }

    public function testApprovedReservationsLoadAsAdmin() {
	$this->loginAsAdmin();
	$this->post('/reservations/approved/');
	$this->assertResponseOk();
    }
  
    public function testDeniedReservationsLoadAsAdmin() {
	$this->loginAsAdmin();
	$this->post('/reservations/denied/');
	$this->assertResponseOk();
    }

    public function testEditPickUpTime() {
	$this->loginAsAdmin();
	$data = [
		'pick_up_time' => [
            'hour' => 9,
            'minute' => 36,
            'meridian' => 'pm'
        ]
	];
	$this->post('/reservations/edit/1', $data);
	$this->assertResponseOk();
	$this->assertRedirect('/reservations/pending');
	$time = $this->Reservations->get(1)->pick_up_time;
	$status = $this->Reservations->get(1)->status;
	$this->assertEquals($time, new Time('9:36 pm'));
	$this->assertEquals(1, $status);
    }

    public function testDenyReservation() {
	$this->loginAsAdmin();
	$this->post('/reservations/deny/1');
	$this->assertResponseOk();
	$this->assertRedirect('/reservations/denied');
	$status = $this->Reservations->get(1)->status;
	$this->assertEquals(2, $status);
    }

    public function testUpcomingReservationsAsClient() {
	$this->loginAsClient();
	$this->post('/reservations/upcoming_reservations/');
	$this->assertResponseOk();	
    }

    public function testPastReservationsAsClient() {
	$this->loginAsClient();
	$this->post('/reservations/past_reservations/');
	$this->assertResponseOk();
    }

    public function testApproveReservation() {
	$this->loginAsAdmin();
	$this->post('/reservations/approve/132');
	$this->assertResponseOk();
	$this->assertRedirect('/reservations/pending');
	$status = $this->Reservations->get(132)->status;
	$this->assertEquals(1, $status);
    }

    public function testBadApprove() {
	$this->loginAsAdmin();
	$this->post('/reservations/approve/');
    	$this->assertResponseOk();
	//$this->assertRedirect('/reservations');
    }

    public function testDenyWithMissingId() {
	$this->loginAsAdmin();
	$this->post('/reservations/deny/');
	$this->assertResponseOk();
        //$this->assertRedirect('/reservations');	
    }

    public function testDenyWithInvalidId() {
        $this->loginAsAdmin();
        $this->post('reservations/deny/5912');
        $this->assertResponseError();
    }

    public function testBadEdit() {
	$this->loginAsAdmin();
	$this->post('/reservations/edit/');
	$this->assertRedirect('/reservations');
    }

    public function testEditWillCall() {
        $this->loginAsScheduler();
        $data = [
            'return_time' => '09:36:00',
            'will_call' => '1'
        ];
        $this->post('/reservations/edit/1', $data);
        $reservation = $this->Reservations->get(1);
        $this->assertEquals(null, $reservation->return_time);
        $this->assertEquals(1, $reservation->status);
    }

    public function testEditApproved() {
	$this->loginAsAdmin();
	$data = [
		'pick_up_time' => '09:36:00'
		];
	$this->post('/reservations/edit/2', $data);
	$status = $this->Reservations->get(2)->status;
	$this->assertEquals(1, $status);
    }

    public function testClientUnauthorized() {
        $this->loginAsClient();
        $this->get('/reservations/index');
        $this->assertRedirect('/');
    }
    
    public function testPendingSortBy() {
        $this->loginAsAdmin();
        $this->get('/reservations?limit=15&order=Reservations.created_time-desc');
        $this->assertResponseOk();
    }
    
    public function testApprovedSortBy() {
        $this->loginAsAdmin();
        $this->get('/reservations/approved?limit=15&order=Reservations.created_time-desc');
        $this->assertResponseOk();
    }
    
    public function testDeniedSortBy() {
        $this->loginAsAdmin();
        $this->get('/reservations/denied?limit=15&order=Reservations.created_time-desc');
        $this->assertResponseOk();
    }
    
    public function testReservationReport() {
        $this->loginAsAdmin();
        $this->get('/reservations/report/1.pdf');
        $this->assertResponseOk();
    } 
    
    public function testReservationReportNoId() {
        $this->loginAsAdmin();
        $this->get('/reservations/report');
        $this->assertRedirect('/reservations');
    }

}
