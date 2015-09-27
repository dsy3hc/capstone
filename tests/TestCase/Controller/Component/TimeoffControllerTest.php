<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TimeoffController;
use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\Core\Configure;

use DateTime;

class TimeoffControllerTest extends IntegrationTestCase {
    public $fixtures = [
        'app.timeoff',
        'app.users',
        'app.settings'
    ];
    public $components = ['Auth'];

    public function setUp() {
        parent::setUp();
        $this->Timeoff = TableRegistry::get('Timeoff');
        $this->Users = TableRegistry::get('Users');
        $user = $this->Users->get(1);
        $this->session(['Auth.User'=> $user->toArray()]);
        $start = new DateTime('now + 3 days');
        $end = new DateTime('now + 5 days');
        $this->start = $start->format('Y-m-d');
        $this->end = $end->format('Y-m-d');

    }

    public function testGettingRequestForm() {
        $this->get('/timeoff/request');
        $this->assertResponseOk();
    }

    public function testTimeoffWithValidRequest() {
        $request = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => $this->start,
            'end_date_1' => $this->end,
            'start_date_2' => '',
            'end_date_2' => '',
            'start_date_3' => '',
            'end_date_3' => '',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        $this->assertResponseOk();
        $this->assertRedirect('/timeoff');

        //check that value is inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(1, $query->count());
    }

    public function testTimeoffWithMissingFirstName() {
        $request = [
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-11-11 00:00:00',
            'end_date_1' => '2014-12-12 00:00:00',
            'start_date_2' => '2014-11-11 00:00:00',
            'end_date_2' => '2014-12-12 00:00:00',
            'start_date_3' => '2014-11-11 00:00:00',
            'end_date_3' => '2014-12-12 00:00:00',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        //check that value is not inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(0, $query->count());
    }

    public function testTimeoffWithEmtpyFirstName() {
        $request = [
            'first_name' => '',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-11-11 00:00:00',
            'end_date_1' => '2014-12-12 00:00:00',
            'start_date_2' => '2014-11-11 00:00:00',
            'end_date_2' => '2014-12-12 00:00:00',
            'start_date_3' => '2014-11-11 00:00:00',
            'end_date_3' => '2014-12-12 00:00:00',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        //check that value is not inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(0, $query->count());
    }

    public function testTimeoffWithMissingLastName() {
        $request = [
            'first_name' => 'John',
            'request_type' => 'annual',
            'start_date_1' => '2014-11-11 00:00:00',
            'end_date_1' => '2014-12-12 00:00:00',
            'start_date_2' => '2014-11-11 00:00:00',
            'end_date_2' => '2014-12-12 00:00:00',
            'start_date_3' => '2014-11-11 00:00:00',
            'end_date_3' => '2014-12-12 00:00:00',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        //check that value is not inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(0, $query->count());
    }

    public function testTimeoffWithEmptyLastName() {
        $request = [
            'first_name' => 'John',
            'last_name' => '',
            'request_type' => 'annual',
            'start_date_1' => '2014-11-11 00:00:00',
            'end_date_1' => '2014-12-12 00:00:00',
            'start_date_2' => '2014-11-11 00:00:00',
            'end_date_2' => '2014-12-12 00:00:00',
            'start_date_3' => '2014-11-11 00:00:00',
            'end_date_3' => '2014-12-12 00:00:00',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        //check that value is not inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(0, $query->count());
    }

    public function testRequestWithEndDateBeforeStartDate() {
        $request = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-12-20',
            'end_date_1' => '2014-1-3',
            'start_date_2' => null,
            'end_date_2' => null,
            'start_date_3' => null,
            'end_date_3' => null,
            'status' => 0,
            'time_selected' => null,
            'comments' => 'Start date past end date'
        ];
        $this->post('/timeoff/request', $request);
        //check that value is not inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(0, $query->count());
    }

    public function testTimeoffApproveFunction() {
        $id = 1;
        $this->post("/timeoff/approve/$id/1");
        $query = $this->Timeoff->get($id);
        $this->assertEquals(1, $query->status);
    }

    public function testApproveWithOutOfRangeOption() {
        $id = 50;
        $this->post("/timeoff/approve/$id/1");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->count());
        $this->assertRedirect(['action' => 'index']);
    }

    public function testApproveViaGet() {
        $id = 1;
        $this->get("/timeoff/approve/$id/1");
        $this->assertRedirect(['action' => 'view', $id]);
    }

    /**
     * Tries to approve choice 2 for a request where the user only specified
     * option 1.
     */
    public function testApproveWithUnspecifiedOption() {
        $id = 4;
        $this->post("/timeoff/approve/$id/2");
        $query = $this->Timeoff->get($id);
        $this->assertEquals(0, $query->status);
    }

    public function testTimeoffDenyFunction() {
        $id = 1;
        $this->post('/timeoff/deny/' . $id);
        $query = $this->Timeoff->get($id);
        $this->assertEquals(2, $query->status);
    }

    public function testDenyNoId() {
        $this->post('/timeoff/deny');
        $this->assertResponseOk();
        $this->assertRedirect(['action' => 'index']);
    }

    public function testDenyWithOutOfBoundsId() {
        $id = 50;
        $this->post("/timeoff/deny/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->count());
        $this->assertRedirect(['action' => 'index']);
    }

   /*added tests */
    public function testApproveWithNoChoice(){
        $id= 1;
        $this->post('/timeoff/approve/$id');
        $this->assertRedirect(['action' => 'index']);
    }
    public function testApproveWithSpecifiedOption() {
        $id = 1;
        $this->post("/timeoff/approve/$id/3");
        $query = $this->Timeoff->get($id);
        $this->assertEquals(1, $query->status);
        $this->assertEquals(3, $query->time_selected);
        $this->assertRedirect(['action' => 'view', $id]);
    }
    public function testUnapproveFunction(){
        $id = 1;
        $this->post('/timeoff/unapprove/'.$id);
        $query = $this->Timeoff->get($id);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->status);
    }
    public function testUnapproveNoId(){
        $this->post('timeoff/unapprove/');
        $this->assertRedirect(['action' => 'index']);
    }

    public function testUnapproveNonexistentId(){
        $id = 50;
        $this->post("/timeoff/unapprove/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->count());
        $this->assertRedirect(['action' => 'index']);
    }

    public function testUnapproveViaGet() {
        $id = 1;
        $this->get("/timeoff/unapprove/$id");
        $this->assertRedirect(['action' => 'view', $id]);
    }

    public function testValidView(){
        $id = 1;
        $this->post("/timeoff/view/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(1, $query->count());
    }
    public function testInvalidView(){
        $id = 50;
        $this->post("/timeoff/view/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->count());
        $this->assertRedirect(['action' => 'index']);
    }

    public function testViewNoId() {
        $this->post('timeoff/view/');
        $this->assertRedirect(['action' => 'index']);
    }

    public function testViewApproved() {
        $this->get('/timeoff/view/2');
        $this->assertResponseContains('Approved');
        $this->assertResponseOk();
    }

    public function testViewDenied() {
        $this->get('/timeoff/view/3');
        $this->assertResponseContains('Denied');
        $this->assertResponseOk();
    }

//    public function testValidSetRequestStatus(){
//        $id = 1;
//        $status = 2;
//        $this->post("/timeoff/setRequestStatus/$id/$status/Request_denied");
//        $query = $this->Timeoff->get($id);
//        $this->assertEquals(2,$status);
//        $this->assertResponseContains('Request_denied');
//    }
//    public function testInvalidSetRequestStatus(){
//        $id = 10;
//        $status = 2;
//        $this->post("/timeoff/setRequestStatus/$id/$status/Request_denied");
//        $this->assertResponseError();
//        //$this->assertEquals(2,$status);
//        //$this->assertResponseContains('There was an error when processing your request');
//    }
//    public function testPendingGetRequest(){
//        $request = ['status'=> 0 ];
//        $this->post("/timeoff/getRequests/$request");
//        $query = $this->Timeoff->find()->where(['status' => 0]);
//        $this->assertEquals(2,$query->count());
//
//    }
    public function testIndex(){
        $this->post("/timeoff/index");
        $this->assertResponseOk();

    }
    public function testApiPendingAll(){
        $this->get('/api/timeoff/pending');
        $this->assertResponseOk();
    }
    public function testApiPending(){
        $this->get('/api/timeoff/pending/1');
        $this->assertResponseOk();
    }
    public function testApiApprovedAll(){
        $this->get('/api/timeoff/approved');
        $this->assertResponseOk();
    }
    public function testApiApproved(){
        $this->get('/api/timeoff/approved/2');
        $this->assertResponseOk();
    }    
    public function testApiDeniedAll(){
        $this->get('/api/timeoff/denied');
        $this->assertResponseOk();
    }
    public function testApiDenied(){
        $this->get('/api/timeoff/denied/3');
        $this->assertResponseOk();
    }
    public function testApiValidView(){
        $this->get('api/timeoff/view/1');
        $this->assertResponseOk();
    }
    public function testApiInvalidView(){
        $this->get('api/timeoff/view/');
        $this->assertResponseError();
    }
    public function testHourlyAuthorization(){
        //authorization of hourly
        $user = $this->Users->get(8);
        $this->session(['Auth.User'=> $user->toArray()]);
        $request = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => $this->start,
            'end_date_1' => $this->end,
            'start_date_2' => '',
            'end_date_2' => '',
            'start_date_3' => '',
            'end_date_3' => '',
            'comments' => 'abcdefghi'
        ];
        $this->post('/timeoff/request', $request);
        $this->assertResponseOk();
        $this->assertRedirect(['controller' => 'users', 'action' => 'profile']);

        //check that value is inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(1, $query->count());

        $this->get('/api/timeoff/pending/' . $user['id']);
        $this->assertResponseOk();

        $id = 1;
        $this->post("/timeoff/view/$id");
        $this->assertResponseOk();
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertEquals(1, $query->count());

        //test view of request submitted by user
        $request_id = $this->Timeoff->find()->where(['comments' => $request['comments']])->toArray()[0]['id'];
        $this->post("/timeoff/view/$request_id");
        $this->assertResponseOk();

        $id = 50;
        $this->post("/timeoff/view/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertResponseOk();
        $this->assertEquals(0, $query->count());
        $this->assertRedirect(['action' => 'index']);
    }
    public function testSchedulerAuthorization(){
        //authorization of scheduler
        $user = $this->Users->get(9);
        $this->session(['Auth.User'=> $user->toArray()]);
        $request = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => $this->start,
            'end_date_1' => $this->end,
            'start_date_2' => '',
            'end_date_2' => '',
            'start_date_3' => '',
            'end_date_3' => '',
            'comments' => 'scheduler'
        ];
        $this->post('/timeoff/request', $request);
        $this->assertResponseOk();
        $this->assertRedirect(['controller' => 'users', 'action' => 'profile']);

        //check that value is inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(1, $query->count());

        $this->post("/timeoff/index");
        $this->assertResponseOk();

        $id = 1;
        $this->post("/timeoff/view/$id");
        $query = $this->Timeoff->find()->where(['id' => $id]);
        $this->assertEquals(1, $query->count());
    }

    public function testClientAuthorization() {
        $user = $this->Users->get(2);
        $this->session(['Auth.User'=> $user->toArray()]);

        $this->post("/timeoff/index");
        $this->assertResponseOk();
        $this->assertRedirect('/');
    }

    public function testRequestWithEmailNotification() {
        $edit = [
            'time_off_request_notification' => 'yes'
        ];

        $this->post('/settings', $edit);
        $this->assertResponseOk();

        $request = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => $this->start,
            'end_date_1' => $this->end,
            'start_date_2' => '',
            'end_date_2' => '',
            'start_date_3' => '',
            'end_date_3' => '',
            'comments' => 'abc'
        ];
        $this->post('/timeoff/request', $request);
        $this->assertResponseOk();
        $this->assertRedirect('/timeoff');

        //check that value is inserted into database
        $query = $this->Timeoff->find()->where(['comments' => $request['comments']]);
        $this->assertEquals(1, $query->count());

        $edit = [
            'time_off_request_notification' => 'no'
        ];

        $this->post('/settings', $edit);
        $this->assertResponseOk();
    }
    
    public function testTimeoffReport() {
        $this->get('/timeoff/report/1.pdf');
        $this->assertResponseOk();
    } 
    
    public function testTimeoffReportNoId() {
        $this->get('/timeoff/report');
        $this->assertRedirect('/timeoff');
    }
}
