<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AddressTable extends Table {

    public function initialize(array $config) {
        $this->table('Address');
        // cake doesn't support composite primary keys...
        // $this->primaryKey('');
    }

    public static function defaultConnectionName() {
        return 'pass';
    }

}
?>