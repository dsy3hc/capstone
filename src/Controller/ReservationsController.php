<?php

namespace App\Controller;

use Cake\Network\Email\Email;
use Cake\Core\Configure;
use DateTime;

class ReservationsController extends AppController {
    public $components = ['Flash', 'RequestHandler'];
    public $paginate = [
        'limit' => 15,
        'order' => [
            'Reservations.pick_up_day' => 'asc'
        ]
    ];

    public function isAuthorized($user) {

        $capabilities = [
            2 => [
                'reserve',
                'upcoming_reservations',
                'past_reservations',
                'report'
            ],
            3 => [
                'index',
                'approve',
                'deny',
                'edit',
                'approved',
                'denied',
                'pending'
            ],
            5 => [
                'index',
                'approve',
                'deny',
                'edit',
                'approved',
                'denied',
                'pending',
                'report'
            ]
        ];

        $role = $this->Auth->user('role_id');
        if (array_key_exists($role, $capabilities)) {
            if (in_array($this->request->action, $capabilities[$role])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }
       
    //function used to add a reservation from the given form
    public function reserve(){

        $clientID = $this->Auth->user('clientID');     
        $firstname = $this->Auth->user('first_name');
        $lastname = $this->Auth->user('last_name');
        $role_id = $this->Auth->user('role_id');
        $this->set('firstname',$firstname);
        $this->set('lastname',$lastname);
        $this->set('role',$role_id);
        
        $reservation = $this->Reservations->newEntity($this->request->data);
        
        if(!array_key_exists('clientID', $this->request->data)) {
            $reservation->set('clientID',$clientID);
        }
        $reservation->set('created_time', time());

        $this->set('pickup_address', $this->Reservations->find('list', [
            'idField' => 'pickup_address_full',
            'valueField' => 'pickup_address',
            'limit' => 10,
            'order' => ['created_time' => 'DESC']])->where(['clientID' => $clientID]));

        $this->set('dropoff_address', $this->Reservations->find('list', [
            'idField' => 'dropoff_address_full',
            'valueField' => 'dropoff_address',
            'limit' => 10,
            'order' => ['created_time' => 'DESC']])->where(['clientID' => $clientID]));

        //get minimum request date from admin settings
        $this->loadModel('Settings');
        $settings = $this->Settings->get(3);
        $request_time = $settings['value'];
        $request_timestamp = strtotime('+'.$request_time);
        $this->set('request_date',date("m/d/Y",$request_timestamp));
        $min_time = new DateTime(date("Y-m-d H:i:s", $request_timestamp));

        $error = [];
        $resub = False;
        if ($this->request->is('post')) {
            if(array_key_exists('return_time', $this->request->data)) {
                if(strlen($this->request->data['return_time']['hour']) > 0) {
                    $string = $this->request->data['return_time']['hour'] . ":" . $this->request->data['return_time']['minute'] . " " . $this->request->data['return_time']['meridian'];

                    $endTime = new DateTime($string);
                    $reservation['return_time'] = $endTime;
                }  
            }
            if(array_key_exists('willcall', $this->request->data)) {
                if($this->request->data['willcall'] == 1) {
                    // if will call, no return time
                    $reservation->set('return_time', null);
                }
            }
	       elseif(array_key_exists('one_way', $this->request->data)) {
                if($this->request->data['one_way'] == 1) {
                    // if one-way, no return time
                    $reservation->set('return_time', null);
                }
            }
            else {
                if($this->formToDatetime($this->request->data['return_time']) < $this->formToDatetime($this->request->data['pick_up_time'])) {
                    $this->set('old_data', $this->request->data);
                    $this->set('submitted', True);
                    $resub = True;
                    $error[] = __("Return time must occur after pickup time.");
                }
            }
            // construct a DateTime for the pickup up time
            $trip_time = $this->formToDatetime($this->request->data['pick_up_time'], $this->request->data['pick_up_day']);
            if ($trip_time < $min_time) {
                $this->set('old_data', $this->request->data);
                $this->set('submitted', True);
                $resub = True;
                $error[] = __("Trip day must be at least $request_time in the future.");
            }
            if (isset($resub) and !$resub and $this->Reservations->save($reservation)) {
                $this->Flash->success(__('Your reservation has been saved.'));

                //modify redirect so that it redirects client and admin to the different places
                //if admin send to index (view all reservations)
                if($role_id == 1){
                    return $this->redirect(['action' => 'index']);
                }
                //if client send to general page
                else {
                    return $this->redirect('/');
                }                   
                
            }
        }
        $this->set('error', $error); 
        $this->set('reservation', $reservation);
    }

    //function used to display all reservations
    public function index() {
        $this->getPendingReservations();
        $this->render('index');
    }
    //missing clientID field
    public function past_reservations(){
        $clientID = $this->Auth->user('clientID'); 
        $past_reservations = $this->Reservations
            ->find()
            ->where(['status =' => 1])  // approved
            ->andWhere([
                    'clientID ='=> $clientID,
                    'pick_up_day <' => time()
                ])
            ->order(['pick_up_day' => 'DESC']);
        $this->set('reservations', $this->paginate($past_reservations));
    }

    public function upcoming_reservations(){
        $clientID = $this->Auth->user('clientID'); 
        $upcoming_reservations = $this->Reservations
            ->find()
            ->where(['status =' => 1])  // approved
            ->andWhere([
                    'clientID =' => $clientID,
                    'pick_up_day >' => time()
                ])
            ->order(['pick_up_day' => 'ASC']);
        $this->set('reservations', $this->paginate($upcoming_reservations));
    }

    //function used to approve reservations
    public function approve($bookingNum=null){
        if (!$bookingNum) {
            $this->Flash->error(__('Something went wrong'));
            return $this->redirect(['action' => 'index']);
        }
        //get reservation with the given id
        $reservation = $this->Reservations->get($bookingNum);

        //set status = 1 to make a reservation approved
        $reservation->set('status', 1);
        $this->Reservations->save($reservation);
        $this->Flash->success(__('Reservation approved'));

        // retrieve email preferences from Settings
        $this->loadModel('Settings');
        $settings = $this->Settings->get(1);
        $send_email = $settings['value'];

        //send email when approved (depending on admin settings)
        if($send_email === true || $send_email === 'yes' || $send_email === 'true'){
            $settings = $this->Settings->get(4);
            $email_template = $settings['value'];

            $this->sendApprovalEmail($reservation, $email_template);

            $this->set('reservation_info', $reservation);
        }
        return $this->redirect(['action' => 'pending']);

    }

    /*
     * The pickup and return times for pending requests can be edited. The
     * request is assumed to be 'approved' once the request has been edited
     */
    function edit($id = null) {

        if (is_null($id)) {
            return $this->redirect(['action' => 'index']);
        }

        $reservation = $this->Reservations->get($id);

        // can only edit pending reservations
        if ($reservation->status != 0) {
            $this->Flash->error("Approved reservations cannot be edited");
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $this->Reservations->patchEntity($reservation, $this->request->data, [
                'fieldList' => ['pick_up_day', 'pick_up_time', 'return_time']
            ]);

            if (array_key_exists('will_call', $this->request->data)) {
                $willCall = $this->request->data['will_call'];
                if ($willCall == '1') {
                    $reservation->return_time = null;
                }
            }

            // approve the reservation
            $reservation->status = 1;

            $this->Reservations->save($reservation);

            $this->sendApprovalEmail($reservation);

            $this->Flash->success('Reservation updated and approved');
            $this->redirect(['action' => 'pending']);
        }

        $this->set('reservation', $reservation);
    }

    //function used to deny reservations
    public function deny($bookingNum = null){
        
        if (!$bookingNum) {
            $this->Flash->error(__('Something went wrong'));
            return $this->redirect(['action' => 'index']);
        }
        //get reservation with the given id
        $reservation = $this->Reservations->get($bookingNum);
        //set status = 2 to make a reservation denied
        $reservation->set('status',2);

        $this->Reservations->save($reservation);
        $this->Flash->success(__('The reservation by client with bookingNum '.$bookingNum. ' has been denied!'));
        return $this->redirect(['action' => 'denied']);
    }

    public function approved() {
        if(!isset($this->request->query) or empty($this->request->query)) {
            $query = "Reservations.pick_up_day-asc";
        } else {
            $query = $this->request->query['order'];
        }
        $order = split("-", $query);

        $this->paginate = array(
            'paramType' => 'querystring',
            'limit' => 15,
            'maxLimit' => 100
        );
        //find all approved reservations (status = 1) and order by created time
        $reservations_approved = $this->Reservations
            ->find()
            ->where(['status =' => 1])
            ->order([$order[0] => $order[1]]);  
        $this->set('reservations', $this->paginate($reservations_approved)); 
        $this->set('title', 'Approved'); 
        $this->set('order', $query);
        $this->render('index');
        
    }

    public function denied() {
        if(!isset($this->request->query) or empty($this->request->query)) {
            $query = "Reservations.pick_up_day-asc";
        } else {
            $query = $this->request->query['order'];
        }
        $order = split("-", $query);

        $this->paginate = array(
            'paramType' => 'querystring',
            'limit' => 15,
            'maxLimit' => 100
        );
        //find all denied reservations (status = 2) and order by created time
        $reservations_denied = $this->Reservations
            ->find()
            ->where(['status' => 2])
            ->order([$order[0] => $order[1]]);  
        $this->set('reservations', $this->paginate($reservations_denied));
        
        $this->set('title', 'Denied'); 
        $this->set('order', $query);
        $this->render('index');
        
    }

    public function pending() {        
        $this->getPendingReservations();
        $this->render('index');
    }

    private function getPendingReservations() {
        if(!isset($this->request->query) or empty($this->request->query)) {
            $query = "Reservations.pick_up_day-asc";
        } else {
            $query = $this->request->query['order'];
        }
        $order = split("-", $query);
        $this->paginate = array(
            'paramType' => 'querystring',
            'limit' => 15,
            'maxLimit' => 100
        );
        $pending = $this->Reservations
            ->find()
            ->where(['status =' => 0])
            ->order([$order[0] => $order[1]]);  
        $this->set('reservations', $this->paginate($pending)); 
        $this->set('title', 'Pending'); 
        $this->set('order', $query);
    }

    private function formToDatetime($time, $day='now') {
        $hour = $time['hour'];
        $min = $time['minute'];
        $meridian = $time['meridian'];
        return new DateTime("$day $hour:$min $meridian");
    }

    private function sendApprovalEmail($reservation, $template='default') {
        if(!is_null($reservation->pick_up_unit) && !empty($reservation->pick_up_unit)) {
            $reservation->pick_up_unit = " Unit $reservation->pick_up_unit";
        }

        if(!is_null($reservation->drop_off_unit) && !empty($reservation->drop_off_unit)) {
            $reservation->drop_off_unit = " Unit $reservation->drop_off_unit";
        }

        $template_vars = [
            'reservation' => $reservation,
            'pick_up_day' => $reservation->pick_up_day->format('m/d/Y'),
            'pick_up_time' => $reservation->formatted_pickup_time,
            'return_time' => $reservation->formatted_return_time,
            'app_name' => Configure::read('App.name')
        ];

        $this->loadModel('Users');
        $client = $this->Users->find('all')->where(['clientID' => $reservation->clientID])->first();

        $email = new Email($template);
        $email->template('reservation_approved')
            ->emailFormat('html')
            ->viewVars($template_vars)
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($client->email)
            ->subject('Your reservation has been approved');
        // @codeCoverageIgnoreStart
        if (!Configure::read('PHPUNIT')) {
            $email->send();
        }
        // @codeCoverageIgnoreEnd
    }

        //function used to generate pdf
    public function report($id = null) {
        if(is_null($id)) {
            return $this->redirect(['action' => 'index']);
        }

        $this->set('reservation', $this->Reservations->get($id));
        $this->set('app_name', Configure::read('App.name'));
    }
}
?>
