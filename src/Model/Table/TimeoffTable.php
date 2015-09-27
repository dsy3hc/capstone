<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TimeoffTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->validatePresence('first_name')
            ->notEmpty('first_name')
            ->validatePresence('last_name')
            ->notEmpty('last_name')
            ->validatePresence('start_date_1')
            ->notEmpty('start_time_1')
            ->validatePresence('end_date_1')
            ->notEmpty('end_time_1')
            ->validatePresence('request_type')
            ->notEmpty('request_type')
            ->add('request_type', [
                'in_list' => [
                    'rule' => ['inList', ['annual', 'sick', 'bonus']],
                    'message' => __('Request type is invalid')
                ]
            ]);

        return $validator;
    }
}
?>
