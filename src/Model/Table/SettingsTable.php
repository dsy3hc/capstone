<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class SettingsTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
    }

    public function getSetting($name) {
        $settings = TableRegistry::get('Settings');
        $setting = $settings->find('all')->where(['name' => $name])->first();
        return $setting->value;
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->validatePresence('name')
            ->notEmpty('name')
            ->validatePresence('value')
            ->notEmpty('value');

        $validator->add('name', 'custom', [
            'rule' => function ($name, $context) {
                $value = $context['data']['value'];
                if ($name == 'send_email') {
                    return in_array($value, ['yes', 'no']);
                }
                if ($name == 'active_time') {
                    return in_array($value, ['1 month', '2 months', '3 months', '6 months', '1 year']);
                }
                if ($name == 'time_off_request_notification') {
                    return in_array($value, ['yes', 'no']);
                }
                if ($name == 'email_template') {
                    return in_array($value, ['default', 'format A', 'format B']);
                }
                if ($name == 'request_time') {
                    return in_array($value, ['1 day', '2 days', '3 days']);
                }
                // @codeCoverageIgnoreStart
                // ignoring this line because it cannot be reached
                return false;
                // @codeCoverageIgnoreEnd
            }
        ]);

        return $validator;
    }
}
?>