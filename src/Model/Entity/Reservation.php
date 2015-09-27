<?php
    namespace App\Model\Entity;

    use Cake\ORM\Entity;
    use Cake\Auth\DefaultPasswordHasher;

    class Reservation extends Entity {
        
        var $name = 'Reservation';
        var $actsAs = array ('Searchable');

        // Make all fields mass assignable for now.
        protected $_accessible = ['*' => true];
        protected $_virtual = ['pickup_address',
            'pickup_address_full',
            'dropoff_address',
            'dropoff_address_full',
            'formatted_pickup_time',
            'formatted_return_time'
        ];

        public function _getPickupAddress() {
            return $this->_properties['pick_up_address'] . ' ' . $this->_properties['pick_up_unit'];
        }

        public function _getDropoffAddress() {
            return $this->_properties['drop_off_address'] . ' ' . $this->_properties['drop_off_unit'];
        }

        public function _getPickupAddressFull() {
            return $this->_properties['pick_up_address'] . ';' . $this->_properties['pick_up_unit'] . ';' . $this->_properties['pick_up_city'] . ';' . $this->_properties['pick_up_zip'];
        }

        public function _getDropoffAddressFull() {
            return $this->_properties['drop_off_address'] . ';' . $this->_properties['drop_off_unit'] . ';' . $this->_properties['drop_off_city'] . ';' . $this->_properties['drop_off_zip'];
        }

        public function _getFormattedPickupTime() {
            return $this->_properties['pick_up_time']->format('g:i a');
        }

        public function _getFormattedReturnTime() {
            $return_time = $this->_properties['return_time'];
	    if(is_null($return_time)) {
		if($this->_properties['one_way'] == 1) {
			return 'One Way';
		}
		else {
			return 'Will Call';
		}
            }
	    else {
		return $return_time->format('g:i a');
	    }
            //return is_null($return_time) ? 'Will Call' : $return_time->format('g:i a');
        }
    }
?>
