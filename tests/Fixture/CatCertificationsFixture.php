<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CatCertificationsFixture extends TestFixture {

    public $fields = [
        'clientID' => ['type' => 'integer', 'null' => false],
        'cat_disability_num' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'expiration_date' => ['type' => 'date', 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['clientID']],
        ]
    ];
//    public $records = [
//        [
//            'id' => 1,
//            'name' => 'admin'
//        ],
//        [
//            'id' => 2,
//            'name' => 'client'
//        ]
//    ];
}