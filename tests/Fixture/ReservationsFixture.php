<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ReservationsFixture extends TestFixture {

    public $fields = [
        'created_time' => 'datetime',
        'first_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'last_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'clientID' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'disability' => ['type' => 'string', 'length' => 50, 'null' => false],
        'doctors_appointment' => ['type' => 'integer', 'length' => 1, 'null' => false],
        'pick_up_day' => 'date',
        'pick_up_time' => 'time',
        'pick_up_address'=> ['type' =>'text','null'=>false],
        'pick_up_unit' => ['type' => 'integer'],
        'pick_up_city'=> ['type' =>'text','null'=>false],
        'pick_up_zip' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'drop_off_address'=> ['type' =>'text','null'=>false],
        'drop_off_unit' => ['type' => 'integer'],
        'drop_off_city'=> ['type' =>'text','null'=>false],
        'drop_off_zip' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'return_time'=> 'time',
        'bookingID' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'bookingNum' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'status' => ['type' => 'integer', 'length' => 2, 'null' => false],
        'comments' => ['type' => 'text'],
        'physicians' => ['type' => 'integer', 'length' => 1, 'null' => false],
        'children' => ['type' => 'integer', 'length' => 1, 'null' => false],
	'one_way' => ['type' => 'integer', 'length' => 1, 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['bookingNum']],
        ]
    ];

    public $records = [
        [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'clientID' => 2,
            'disability' => 'none',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=>'08:36:00',
            'pick_up_address'=>'JPA',
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=>'010:36:00',
            'bookingID'=> 0,
            'bookingNum'=> 1,
	    'status'=> 0,
	    'comments'=> 'this is a comment',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0
        ],
        [
            'created_time' => '2014-11-11 00:00:00',
            'first_name' => 'Testor',
            'last_name' => '1',
            'clientID' => 1,
            'disability' => 'none',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=>'08:36:00',
            'pick_up_address'=>'JPA',
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
	    'drop_off_address'=> 'rice hall',
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
	    'return_time'=>'010:36:00',
            'bookingID'=> 0,
            'bookingNum'=> 2,
	    'status'=> 1,
	    'comments'=> 'this is a comment',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0           
        ],
        [
            'created_time' => '2014-11-03 00:00:00',
            'first_name' => 'Testor',
            'last_name' => '2',
            'clientID' => 2,
            'disability' => 'blind',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-12',
            'pick_up_time'=>'09:30:00',
            'pick_up_address'=>'rice hall',
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'olsson',
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=>'011:30:00',
            'bookingID'=> 0,
            'bookingNum'=> 3,
	    'status'=> 2,
	    'comments'=> 'this is a comment',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0   
        ],
        [
            'created_time' => '2014-11-03 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'clientID' => 2,
            'disability' => null,
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-12',
            'pick_up_time'=>'09:30:00',
            'pick_up_address'=>'rice hall',
            'pick_up_city'=> 'Charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Olsson',
            'drop_off_city'=> 'Charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=>'011:30:00',
            'bookingID'=> 0,
            'bookingNum'=> 4,
            'status'=> 1,
            'comments'=> 'this is a comment',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0
        ],
	[
            'created_time' => '2014-11-11 00:00:03',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'clientID' => 2,
            'disability' => 'none',
            'doctors_appointment' => 0,
            'pick_up_day' => '2011-01-11',
            'pick_up_time'=>'06:36:00',
            'pick_up_address'=>'JPA',
            'pick_up_unit' => 100,
            'pick_up_city'=> 'charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'rice hall',
            'drop_off_unit' => 200,
            'drop_off_city'=> 'charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=>'010:36:00',
            'bookingID'=> 0,
            'bookingNum'=> 132,
	    'status'=> 0,
	    'comments'=> 'this is a comment',
            'physicians'=> 0,
            'children'=> 0,
	    'one_way'=> 0
        ],
        [
            'created_time' => '2015-03-25 00:00:00',
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'clientID' => 2,
            'disability' => 'none',
            'doctors_appointment' => 0,
            'pick_up_day' => '2015-04-01',
            'pick_up_time'=>'08:36:00',
            'pick_up_address'=>'100 Jefferson Park Ave',
            'pick_up_city'=> 'Charlottesville',
            'pick_up_zip'=> 22903,
            'drop_off_address'=> 'Rice Hall',
            'drop_off_city'=> 'Charlottesville',
            'drop_off_zip'=> 22902,
            'return_time'=> null,
            'bookingID'=> 0,
            'bookingNum'=> 5,
            'status'=> 0,
            'physicians'=> 0,
            'children'=> 0,
            'one_way'=> 1
        ]
    ];
}