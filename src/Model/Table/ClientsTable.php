<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ClientsTable extends Table {

    public function initialize(array $config) {
        $this->table('Clients');
        $this->primaryKey('ClientId');
    }

    public static function defaultConnectionName() {
        return 'pass';
    }

}
?>