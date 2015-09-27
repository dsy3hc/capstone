<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SettingsFixture extends TestFixture {
    public $fields = [
        'name'=> ['type' => 'string', 'length' => 30, 'null' => false],
        'value'=> ['type' => 'string', 'length' => 30, 'null' => false],
		'id' =>['type' => 'integer', 'length' => 3, 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
            ]

        ];
    
    public $records = [
        [
        	'id'=> 1,
            'name' => 'send_email',
            'value' => 'yes',
        ],
        [
        	'id'=> 2,
            'name' => 'active_time',
            'value' => '2 months',
        ],
        [
        	'id'=> 3,
            'name' => 'request_time',
            'value' => '1 day',
        ],
        [
        	'id'=> 4,
            'name' => 'email_template',
            'value' => 'default',
        ],
        [
        'id'=> 5,
        'name' => 'time_off_request_notification',
        'value' => 'no',
    ]
    ];
}
?>