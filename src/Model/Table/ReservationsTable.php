<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ReservationsTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('first name')
            ->notEmpty('last name')
            ->notEmpty('pick up day')
            ->notEmpty('pick up time')
            ->notEmpty('pick up address')
            ->notEmpty('pick up city')
            ->notEmpty('pick up zip')
            ->notEmpty('drop off address')
            ->notEmpty('drop off city')
            ->notEmpty('drop off zip')
            ->notEmpty('return time');

        return $validator;
    }
}
?>