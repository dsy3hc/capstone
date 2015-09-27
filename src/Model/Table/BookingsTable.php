<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class BookingsTable extends Table {

    public function initialize(array $config) {
        $this->table('Booking');
        $this->primaryKey('BookingId');
    }

    public static function defaultConnectionName() {
        return 'pass';
    }

}
?>