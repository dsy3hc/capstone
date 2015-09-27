<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TimeoffFixture extends TestFixture {

    public $table = "timeoff";
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'user_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'first_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'last_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'start_date_1' => 'datetime',
        'end_date_1' => 'datetime',
        'start_date_2' => 'datetime',
        'end_date_2' => 'datetime',
        'start_date_3' => 'datetime',
        'end_date_3' => 'datetime',
        'status' => ['type' => 'integer', 'length' => 2, 'default' => 0, 'null' => false],
        'request_type' => ['type' => 'string', 'length' => 16],
        'time_selected' => ['type' => 'integer', 'length' => 2, 'null' => true],
        'comments' => 'text',
        'created' => 'datetime',
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']]
        ]
    ];

    public $records = [
        [
            'id' => 1,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-11-11 12:00:00',
            'end_date_1' => '2014-12-12 12:00:00',
            'start_date_2' => '2014-11-11 12:00:00',
            'end_date_2' => '2014-12-12 12:00:00',
            'start_date_3' => '2014-11-11 12:00:00',
            'end_date_3' => '2014-12-12 12:00:00',
            'status' => 0,
            'time_selected' => 1,
            'comments' => 'intentionally left blank'
        ],
        [
            'id' => 2,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-09-09',
            'end_date_1' => '2014-10-10',
            'start_date_2' => '2014-11-11',
            'end_date_2' => '2014-12-12',
            'start_date_3' => '2014-11-11',
            'end_date_3' => '2014-12-12',
            'status' => 1,
            'time_selected' => 1,
            'comments' => 'intentionally left blank'
        ],
        [
            'id' => 3,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-09-09',
            'end_date_1' => '2014-10-10',
            'start_date_2' => '2014-11-11',
            'end_date_2' => '2014-12-12',
            'start_date_3' => '2014-11-11',
            'end_date_3' => '2014-12-12',
            'status' => 2,
            'time_selected' => 1,
            'comments' => 'intentionally left blank'
        ],
        [
            'id' => 4,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'request_type' => 'annual',
            'start_date_1' => '2014-12-20',
            'end_date_1' => '2015-1-3',
            'start_date_2' => null,
            'end_date_2' => null,
            'start_date_3' => null,
            'end_date_3' => null,
            'status' => 0,
            'time_selected' => null,
            'comments' => 'Vacation'
        ]
    ];
}
