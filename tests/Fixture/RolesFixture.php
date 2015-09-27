<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class RolesFixture extends TestFixture {

    public $fields = [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'string', 'length' => 32, 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ]
    ];
    public $records = [
        [
            'id' => 1,
            'name' => 'admin'
        ],
        [
            'id' => 2,
            'name' => 'client'
        ],
        [
        'id' => 3,
        'name' => 'hourly'
        ],
        [
            'id' => 4,
            'name' => 'scheduler'
        ],
        [
        'id' => 5,
        'name' => 'driver'
        ]
    ];
}