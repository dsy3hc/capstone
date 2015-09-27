<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Email\Email;
use Cake\Core\Configure;
use DateTime;
use Exception;

class User extends Entity {
    
    var $name = 'User';
    var $actsAs = array ('Searchable');

    var $validate = array(
        'email' => array(
            'rule' => array('minLength', 1)
        ),
        'clientID' => array(
            'rule' => array('minLength', 1)
        )
    );
    protected $_accessible = ['*' => false];
    protected $_virtual = ['is_email_confirmed', 'is_client_id_confirmed', 'full_name'];
    protected $_hidden = ['password'];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    protected function _getIsEmailConfirmed() {
        return !is_null($this->_properties['email_confirm_date']);
    }

    protected function _getIsClientIdConfirmed() {
        return !is_null($this->_properties['clientID']) || $this->_properties['role_id'] != 2;
    }

    public function _getFullName() {
        return $this->_properties['first_name'] . ' ' . $this->_properties['last_name'];
    }

    /**
     * @param string $comparison Operator for comparing the expiration date to
     * the date {30|60|90} days from now.
     * @param bool $send_email Whether or not to send an email notification to
     * let the user know of their impending expiration date.
     *
     * @return bool Whether or not the user's certification is soon-to-expire
     * @throws Exception
     */
    public function checkCATCertification($comparison = '=', $send_email = false) {
        $expiration = $this->_properties['expiration_date'];
        if ($expiration == null) {
            return false;
        }

        $intervals = ['30 days', '60 days', '90 days'];
        $today = new DateTime('today');

        foreach ($intervals as $interval) {
            $interval = $this->todayPlus($interval);

            if ($comparison == '<=') {
                $expiring = ($expiration <= $interval);
            }
            else if ($comparison == '=') {
                $expiring = ($expiration == $interval);
            }
            else {
                throw new Exception('Invalid comparison operator');
            }

            if ($expiring) {
                // get time interval between the expiration date and today
                $diff = $expiration->diff($today);
                // get the number of days remaining
                $days = $diff->format('%a');

                if ($send_email) {
                    $this->sendReminderEmail($days);
                }
                return true;
            }
        }
        return false;
    }

    private function todayPlus($addition) {
        $today = new DateTime('today');
        date_add($today, date_interval_create_from_date_string($addition));
        return $today;
    }

    private function sendReminderEmail($days) {
        $email = new Email('default');
        $email->template('cat_certification_expiring')
            ->emailFormat('html')
            ->viewVars( array(
                'first_name' => $this->_properties['first_name'],
                'last_name' => $this->_properties['last_name'],
                'days' => $days
            ))
            ->from(['noreply@ridejaunt.org' => 'JAUNT'])
            ->to($this->_properties['email'])
            ->subject('CAT Paratransit Certification Expiration Notice');

        // @codeCoverageIgnoreStart
        if (!Configure::read('PHPUNIT')) {
            $email->send();
        }
        // @codeCoverageIgnoreEnd
    }
}
?>