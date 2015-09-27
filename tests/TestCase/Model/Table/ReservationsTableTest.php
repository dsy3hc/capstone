<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ReservationsTableTest extends TestCase {

    public $fixtures = [
        'app.reservations',
    ];

    public function setUp() {
        parent::setUp();
        $this->Reservations = TableRegistry::get('Reservations');
    }

    public function tearDown() {
        unset($this->Reservations);

        parent::tearDown();
    }

    public function testGetPickupAddress() {
        $reservation = $this->Reservations->get(1);
        $address = $reservation->pickup_address;
        $this->assertEquals('JPA ', $address);
    }

    public function testGetDropoffAddress() {
        $reservation = $this->Reservations->get(1);
        $address = $reservation->dropoff_address;
        $this->assertEquals('rice hall ', $address);
    }

    public function testGetFullPickupAddress() {
        $reservation = $this->Reservations->get(1);
        $address = $reservation->pickup_address_full;
        $this->assertEquals('JPA;;charlottesville;22903', $address);
    }

    public function testGetFullDropoffAddress() {
        $reservation = $this->Reservations->get(1);
        $address = $reservation->dropoff_address_full;
        $this->assertEquals('rice hall;;charlottesville;22902', $address);
    }

    public function testGetFormattedReturnTime() {
        $reservation = $this->Reservations->get(1);
        $return_time = $reservation->formatted_return_time;
        $this->assertEquals('10:36 am', $return_time);

        $reservation = $this->Reservations->get(5);
        $return_time = $reservation->formatted_return_time;
        $this->assertEquals('One Way', $return_time);
    }

}
