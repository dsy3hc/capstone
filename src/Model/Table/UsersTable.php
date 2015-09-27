<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

class UsersTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Roles');
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('first_name')
            ->notEmpty('last_name')
            ->notEmpty('email')
            ->add('email', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __('That email is taken')
                ]
            ])
            ->add('email', 'validFormat', ['rule' => 'email',
                                           'message' => 'E-mail must be valid']);

        return $validator;
    }

    public function validationSignup(Validator $validator) {
        $this->validationDefault($validator);
        $validator
            ->notEmpty('password')
            ->notEmpty('ridden_before')
            ->add('ridden_before', '', [
                'rule' => ['comparison', '==', 'true'],
                'message' => 'You must have a preexisting account with JAUNT'
            ])
            ->add('password', 'match', [
                'rule' => function($value, $context) {
                    return (new DefaultPasswordHasher)->check(
                        $context['data']['confirm'],
                        $value
                    );
                },
                'on' => function ($context) {
                    return array_key_exists('confirm', $context['data']);
                },
                'message' => __("Those passwords don't match")
            ])
            ->add('password', 'length', [
                'rule' => ['minLength', 1],
                'message' => __('Password must not be empty')
            ]);

        return $validator;
    }

    private function validationAddEdit(Validator $validator) {
        $this->validationDefault($validator);
        $validator
            ->notEmpty('role_id')
            ->notEmpty('clientID', 'Clients must have a Client ID', function($context) {
                // client id is required when the role is set to 'client'
                return $context['data']['role_id'] == 2;
            })
            ->add('clientID', 'positive', [
                'rule' => ['comparison', '>', '0'],
                'message' => __('Client IDs must be positive')
            ])
            ->add('clientID', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __('Client ID is already associated with another account')
                ]
            ])
            ->add('role_id', [
                'in_list' => [
                    'rule' => ['inList', [1, 2, 3, 4, 5]],
                    'message' => __('You provided an invalid role')
                ]
            ]);

        return $validator;
    }

    public function validationAdd(Validator $validator) {
        $this->validationAddEdit($validator);

        return $validator;
    }

    public function validationEdit(Validator $validator) {
        $this->validationAddEdit($validator);

        return $validator;
    }
}
?>