<?php

namespace App\Controller;

use Cake\Network\Email\Email;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\Core\Configure;

class TimeoffController extends AppController {
    public $components = ['Flash', 'RequestHandler'];

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Timeoff.start_date_1' => 'asc'
        ]
    ];

    public function isAuthorized($user) {
        $role = $this->Auth->user('role_id');
        if ($role == 3 || $role == 4) {
            // hourly & driver
            if (in_array($this->request->action, [
                'request',
                'api'
            ])) {
                return true;
            }

            if ($this->request->action == 'view') {
                if (isset($this->request->params['pass'][0])) {
                    // non-schedulers should only be able to see their own requests
                    $id = $this->request->params['pass'][0];
                    try {
                        $request = $this->Timeoff->get($id);
                    }
                    catch (RecordNotFoundException $e) {
                        return $this->redirect(['action' => 'index']);
                    }
                    if ($request->user_id == $this->Auth->user('id')) {
                        return true;
                    }
                }
            }
        } else if ($role == 5 || $role == 1){
            if (in_array($this->request->action, [
                'request',
                'index',
                'api',
                'view',
                'approve',
                'report'
            ])) {
                return true;
            }

        } else  { return $this->redirect('/'); }

        return parent::isAuthorized($user);
    }
       
    //function used to add a request from the given form
    public function request() {

        $firstname = $this->Auth->user('first_name');
        $lastname = $this->Auth->user('last_name');
        $this->set('firstname',$firstname);
        $this->set('lastname',$lastname);

        $request = $this->Timeoff->newEntity($this->request->data);
        if ($this->request->is('post')) {
            $request->user_id = $this->Auth->user('id');
            if ($request->end_date_1 > $request->start_date_1 and $request->start_date_1 > date('Y-m-d')) {
                if($this->Timeoff->save($request)) {
                    $this->Flash->success(__('Your request has been saved.'));
                    $this->loadModel('Settings');
                    if ($this->Settings->getSetting('time_off_request_notification') === 'yes') {
                        $this->notifySchedulers($request);
                    }
                    if($this->Auth->user('role_id') != 1) {
                        return $this->redirect(['controller' => 'users', 'action' => 'profile']);
                    }
                } 
                return $this->redirect(['action' => 'index']); 
            }
            $error = "";
            if($request->start_date_1 < date('Y-m-d')) {
                $error .= __("ERROR: Start date must occur in the future.");
            } 
            if($request->end_date_1 < $request->start_date_1) {
                if(strlen($error) > 0) {
                    $error .= "\n";
                }
                $error .= __("ERROR: End date must be later than start date.");
            }
            $this->Flash->error($error);
        }
        $this->set('request', $request);
    }

    //function used to display all requests
    public function index() {

        //find all pending requests (pending  = 0)
        $pending = $this->getRequests(['status' => 0]);
        $this->set('requests_pending', $this->paginate($pending));    

        //find all approved requests (status = 1)
        $requests_approved = $this->getRequests(['status' => 1]);
        $this->set('requests_approved', $requests_approved);

        //find all denied requests (status = 2)
        $requests_denied = $this->getRequests(['status' => 2]);
        $this->set('requests_denied', $requests_denied);
    }

    public function api($filter = null, $id = null) {
        $scheduler = in_array($this->Auth->user('role_id'), [1, 5]);
        $where = [];

        if (!$scheduler) {
            // if not a scheduler, only return requests belonging to current user
            $where += ['user_id' => $this->Auth->user('id')];
        }

        if ($filter == 'pending') {
            if ($id) {
                // filter on id if given
                $where += ['user_id' => $id];
            }
            $where += ['status' => 0];
        }

        if ($filter == 'approved') {
            if ($id) {
                // filter on id if given
                $where += ['user_id' => $id];
            }
            $where += ['status' => 1];
        }

        if ($filter == 'view') {
            if (!$id) {
                // id is required
                throw new BadRequestException("No id specified");
            }
            $where += ['id' => $id];
        }

        if ($filter == 'denied') {
            if ($id) {
                // filter on id if given
                $where += ['user_id' => $id];
            }
            $where += ['status' => 2];
        }

        $requests = $this->getRequests($where);
        $this->set('requests', $requests);
        $this->set('viewing', $filter == 'view');

        $this->render('index');
    }

    /**
     * Returns all requests with the given WHERE options.
     *
     * @param array $options Additional WHERE clauses for the query
     *
     * @return Query
     */
    private function getRequests($options = []) {
        $query = $this->Timeoff
            ->find()
            ->where($options)
            ->order(['created' => 'DESC']);
        return $query;
    }

    public function approve($request_id = null, $choice = null) {
        if (!$request_id || !$choice) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {
            try {
                $request = $this->Timeoff->get($request_id);
            }
            catch (RecordNotFoundException $e) {
                return $this->redirect(['action' => 'index']);
            }
            if (!$choice || $choice < 1 || $choice > 3 || $request->getOptions($choice)[0] == null) {
                $this->Flash->error(__("There was an error when processing your request"));
                return $this->redirect(['action' => 'index']);
            }
            $request->status = 1;
            $request->time_selected = $choice;
            $this->Timeoff->save($request);
            return $this->redirect(['action' => 'view', $request_id]);
        }
        return $this->redirect(['action' => 'view', $request_id]);
    }

    public function view($request_id = null) {
        if ($request_id) {
            try {
                $request = $this->Timeoff->get($request_id);
            }
            catch (RecordNotFoundException $e) {
                return $this->redirect(['action' => 'index']);
            }
            $this->set(compact('request'));
            $can_approve = in_array($this->Auth->user('role_id'), [1, 5]);
            $this->set('approve', $can_approve);
            $this->set('user_id', $this->Auth->user('id'));
        }
        else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function deny($id = null) {
        $this->request->allowMethod(['post']);
        $this->setRequestStatus($id, 2, __("Request denied"));
    }

    public function unapprove($request_id = null) {
        if (!$request_id) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {
            try {
                $request = $this->Timeoff->get($request_id);
            }
            catch (RecordNotFoundException $e) {
                return $this->redirect(['action' => 'index']);
            }
            $request->time_selected = null;  // clear time selected
            $request->status = 0;  // set back to pending
            $this->Timeoff->save($request);
            return $this->redirect(['action' => 'view', $request_id]);
        }
        return $this->redirect(['action' => 'view', $request_id]);
    }

    private function setRequestStatus($id, $status, $message) {
        if (!is_null($id)) {
            try {
                $request = $this->Timeoff->get($id);
            }
            catch (RecordNotFoundException $e) {
                $this->Flash->error(__('There was an error when processing your request'));
                return $this->redirect(['action' => 'index']);
            }
            $request->status = $status;
            if ($this->Timeoff->save($request)) {
                $this->Flash->success($message);
            }
        }
        else {
            $this->Flash->error(__('There was an error when processing your request'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param $request
     *
     * Notifies all schedulers letting them know about the new request
     */
    private function notifySchedulers($request) {
        $this->loadModel('Users');
        $schedulers = $this->Users->find('all', [
            'fields' => ['email'],
            'conditions' => ['role_id' => 5]
        ]);
        $emails = [];
        foreach ($schedulers as $scheduler) {
            $emails[] = $scheduler->email;
        }

        $template_vars = [
            'request' => $request,
            'first_name' => $this->Auth->user('first_name'),
            'last_name' => $this->Auth->user('last_name')
        ];

        $email = new Email('default');
        $email->template('scheduler_time_off', 'fancy')
            ->emailFormat('html')
            ->viewVars($template_vars)
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($emails)
            ->subject('New Time Off Request');

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

        $this->set('request', $this->Timeoff->get($id));
    }
    
}
?>
