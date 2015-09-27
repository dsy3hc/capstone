<?php

	namespace App\Controller;

	class AjaxController extends AppController {
      public $components = ['Flash', 'RequestHandler'];

	  	var $name = 'Ajax';
	   	var $uses = NULL;

	   	public function lifetimeMetrics() {
	       	$this->layout='ajax';

	       	$this->loadModel('Users');

	   	    $this->set('numUsers', $this->Users->find('all')->count());
	        $this->loadModel('Reservations');
	        $this->set('numReservations', $this->Reservations->find('all')->count());
	        $this->set('pendingReservations', $this->Reservations->find('all')
	                                                ->where(['Reservations.status' => 0])
	                                                ->count()
	                                            );
	        $this->set('approvedReservations', $this->Reservations->find('all')
	                                                ->where(['Reservations.status' => 1])
	                                                ->count());
	        $this->set('deniedReservations', $this->Reservations->find('all')
	                                              ->where([ 'Reservations.status' => 2 ])
	                                              ->count());
	   	}

      public function lifetime_report() {

          $this->loadModel('Users');

          $this->set('numUsers', $this->Users->find('all')->count());
          $this->loadModel('Reservations');
          $this->set('numReservations', $this->Reservations->find('all')->count());
          $this->set('pendingReservations', $this->Reservations->find('all')
                                                  ->where(['Reservations.status' => 0])
                                                  ->count()
                                              );
          $this->set('approvedReservations', $this->Reservations->find('all')
                                                  ->where(['Reservations.status' => 1])
                                                  ->count());
          $this->set('deniedReservations', $this->Reservations->find('all')
                                                ->where([ 'Reservations.status' => 2 ])
                                                ->count());
      }

	   	public function userLifetimeMetrics() {
	       	$this->layout='ajax';
	       	$this->loadModel('Users');
	       	$this->loadModel('Reservations');

            $userId = $this->request->data['user_id'];
            $this->set('userReservations', $this->Reservations->find('all')
                                                    ->where([
                                                            'Reservations.clientID' => $userId
                                                            ])
                                                    ->count()
                                                  );
            $userLoginActivity = $this->Users->find('all')
                                                    ->where([
                                                            'Users.clientID' => $userId
                                                            ])
                                                    ->order(['Users.modified' => 'DESC'])
                                                    ->first()['modified'];
            $userReservationActivity = $this->Reservations->find('all')
                                                    ->where([
                                                            'Reservations.clientID' => $userId
                                                            ])
                                                    ->order(['Reservations.created_time' => 'DESC'])
                                                    ->first()['created_time'];
            if($userLoginActivity < $userReservationActivity) {
                $userActivity = $userReservationActivity;
            } else {
                $userActivity = $userLoginActivity;
            }

            $this->set('userActivity', $userActivity);
            $this->set('userName', $this->Users->find('all')
                                                    ->where([
                                                            'Users.clientID' => $userId
                                                            ])
                                                    ->first()['full_name']);	
	   	}

		public function dateMetrics() {
	       	$this->layout='ajax';
	       	$this->loadModel('Users');

	       	$startDate = $this->request->data['start_day_formatted'];
           	$endDate = $this->request->data['end_day_formatted'];
            
           	$this->set('numUsers', $this->Users->find('all')
                                              ->where([
                                              	        'Users.created >= ' => $startDate,
                                                        'Users.created <= ' => $endDate
                                                      ])
                                              ->count());
           	$this->loadModel('Reservations');
           	$this->set('numReservations', $this->Reservations->find('all')
											  ->where([
                                                    'Reservations.created_time >= ' => $startDate,
                                                    'Reservations.created_time <= ' => $endDate
                                                ])
                                               ->count());
           	$this->set('pendingReservations', $this->Reservations->find('all')
                                                  ->where(['Reservations.status' => 0])
                                                  ->andWhere([
                                                          'Reservations.created_time >= ' => $startDate,
                                                          'Reservations.created_time <= ' => $endDate
                                                  	      ])
                                                  ->count()
                                                );
            $this->set('approvedReservations', $this->Reservations->find('all')
                                                    ->where(['Reservations.status' => 1])
                                                    ->andWhere([
                                                            'Reservations.created_time >= ' => $startDate,
                                                            'Reservations.created_time <= ' => $endDate
                                                        ])
                                                    ->count());
            $this->set('deniedReservations', $this->Reservations->find('all')
                                                  ->where([ 'Reservations.status' => 2 ])
                                                  ->andWhere([
                                                            'Reservations.created_time >= ' => $startDate,
                                                            'Reservations.created_time <= ' => $endDate
                                                        ])
                                                  ->count()
                                                  );
	   	}

	   	public function userDateMetrics() {
	       $this->layout='ajax';
	       $this->loadModel('Users');
	       $this->loadModel('Reservations');

            $startDate = $this->request->data['user_start_day_formatted'];
            $endDate = $this->request->data['user_end_day_formatted'];

            $userId = $this->request->data['user_id'];
            $this->set('userReservations', $this->Reservations->find('all')
                                                    ->where([
                                                            'Reservations.created_time >= ' => $startDate,
                                                            'Reservations.created_time <= ' => $endDate,
                                                            'Reservations.clientID' => $userId
                                                            ])
                                                    ->count()
                                                  );
            $userLoginActivity = $this->Users->find('all')
                                                    ->where([
                                                            'Users.clientID' => $userId
                                                            ])
                                                    ->where([
															'Users.modified >= ' => $startDate,
                                                            'Users.modified <= ' => $endDate,
                                                    		])
                                                    ->order(['Users.modified' => 'DESC'])
                                                    ->first()['modified'];
            $userReservationActivity = $this->Reservations->find('all')
                                                    ->where([
                                                            'Reservations.clientID' => $userId
                                                            ])
                                                    ->where([
															'Reservations.created_time >= ' => $startDate,
                                                            'Reservations.created_time <= ' => $endDate,
                                                    		])
                                                    ->order(['Reservations.created_time' => 'DESC'])
                                                    ->first()['created_time'];
            if($userLoginActivity < $userReservationActivity) {
                $userActivity = $userReservationActivity;
            } else {
                $userActivity = $userLoginActivity;
            }

            $this->set('userActivity', $userActivity);
            $this->set('userStartDate', $startDate);
            $this->set('userEndDate', $endDate);
            $this->set('userName', $this->Users->find('all')
                                                    ->where([
                                                            'Users.clientID' => $userId
                                                            ])
                                                    ->first()['full_name']);	   
	   	}
	}
?>
