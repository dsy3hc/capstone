<?php

namespace App\Controller;

use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class SettingsController extends AppController {

    public $components = ['Flash'];

    public function index(){
        $settings = $this->Settings->find('all');

        $setting_info = [
            'send_email' => [
                'description' => 'Send an email to clients after their reservations are approved',
                'options' => ['yes'=>'yes','no'=>'no']
            ],
            'active_time' => [
                'description' => 'Length of time of no activity before users are considered inactive',
                'options' => ['1 month' => '1 month','2 months'=> '2 months','3 months'=>'3 months','6 months'=> '6 months', '1 year' =>'1 year']
            ],
            'request_time' => [
                'description' => 'Reservations must be made at least this amount of time into the future',
                'options' => ['1 day' => '1 day', '2 days' => '2 days', '3 days' =>'3 days']
            ],
            'email_template' => [
                'description' => 'Email template',
                'options' => ['default' => 'default', 'format A' => 'format A', 'format B' =>'format B']
            ],
            'time_off_request_notification' => [
                'description' => 'Send emails to schedulers when a Time Off request is submitted',
                'options' => ['yes'=>'yes','no'=>'no']
            ]
        ];

        $errors = false;
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->data;
            foreach ($settings as $setting) {
                if (array_key_exists($setting->name, $data)) {
                    $setting->value = $data[$setting->name];
                    if (!$this->Settings->save($setting)) {
                        $setting_info[$setting->name]['error'] = 'error';
                        $errors = true;
                    }
                }
            }
            if ($errors) {
                $this->Flash->error('Some settings could not be saved');
            }
            else {
                $this->Flash->success('Settings updated');
            }
        }

        foreach ($settings as $setting) {
            $setting_info[$setting->name]['value'] = $setting->value;
        }
        $this->set('settings',$setting_info);
    }
}   