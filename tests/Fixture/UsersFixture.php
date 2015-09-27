<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture {

    public $fields = [
        'id' => ['type' => 'integer'],
        'first_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'last_name' => ['type' => 'string', 'length' => 256, 'null' => false],
        'email' => ['type' => 'string', 'length' => 256, 'null' => false],
        'password' => ['type' => 'string', 'length' => 128, 'null' => false],
        'clientID' => ['type' => 'integer', 'length' => 64, 'null' => true], // allows null
        'role_id' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => 2],
        'cat_disability_num' => ['type' => 'integer'],
        'expiration_date' => ['type' => 'date', 'default' => null],
        'email_confirm_key' => ['type' => 'string', 'length' => 32, 'null' => false],
        'email_confirm_date' => ['type' => 'datetime', 'null' => true],
        'email_confirm_ip' => ['type' => 'string', 'length' => 39, 'null' => true],
        'password_reset_key' => ['type'=>'string','length'=> 32,'null' => true],
        'language' => ['type' => 'string', 'length' => 256, 'default' => 'English'],
        'created' => 'datetime',
        'modified' => 'datetime',
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ]
    ];
    public $records = [
        [
            'id' => 1,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'admin@ridejaunt.org',
            'password' => '$2y$10$Kuc52DeukAywdiHph60kruva2iVXH88zNvkCKMzjhQ9mOqJ/yHlWm',
            'clientID' => 256,
            'role_id' => 1,
            'cat_disability_num' => 321,
            'expiration_date' => '2015-11-03',
	    'email_confirm_key' => 'D0vqFIOjmlgxs2BeWJ8Hhdcpv9RExBZa',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
            'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 2,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => 2,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 3,
            'first_name' => 'Jeff',
            'last_name' => 'Thomas',
            'email' => 'jt@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => 1024,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
             'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 4,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client1@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => null,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => null,
            'email_confirm_ip' => null,
             'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 5,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client2@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => null,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
             'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 6,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client3@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => 5125,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => null,
            'email_confirm_ip' => null,
             'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 7,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client4@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => 5126,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
             'password_reset_key' =>'1' ,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
    	],
        [
            'id' => 128,
            'first_name' => 'Thomas',
            'last_name' => 'Jefferson',
            'email' => 'client5@ridejaunt.org',
            'password' => '$2y$10$8WIuU5NygTgJS33yhWZOwegtIlc11cDYoA51/ya0X2ybhNGY.Brtq',
            'clientID' => 23985,
            'role_id' => 2,
            'email_confirm_key' => 'M2154Cm7hmit4aFS83qYxjDYWSpEnLjZ',
            'email_confirm_date' => null,
            'email_confirm_ip' => null,
            'password_reset_key' =>'128',
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 8,
            'first_name' => 'Hourly',
            'last_name' => 'Employee',
            'email' => 'hourly@ridejaunt.org',
            'password' => '$2y$10$Kuc52DeukAywdiHph60kruva2iVXH88zNvkCKMzjhQ9mOqJ/yHlWm',
            'clientID' => 257,
            'role_id' => 3,
            'email_confirm_key' => 'D0vqFIOjmlgxs2BeWJ8Hhdcpv9RExBZa',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
            'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ],
        [
            'id' => 9,
            'first_name' => 'Scheduler',
            'last_name' => 'Employee',
            'email' => 'scheduler@ridejaunt.org',
            'password' => '$2y$10$Kuc52DeukAywdiHph60kruva2iVXH88zNvkCKMzjhQ9mOqJ/yHlWm',
            'clientID' => 258,
            'role_id' => 5,
            'email_confirm_key' => 'D0vqFIOjmlgxs2BeWJ8Hhdcpv9RExBZa',
            'email_confirm_date' => '2014-11-04 00:00:00',
            'email_confirm_ip' => '192.168.1.1',
            'password_reset_key' => null,
            'created' => '2014-11-03 00:00:00',
            'modified' => '2014-11-03 00:00:00'
        ]
    ];
}
