<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Psr\Log\InvalidArgumentException;

class Timeoff extends Entity {

    protected $_virtual = ['full_name', ];

    public function _getFullName() {
        return $this->_properties['first_name'] . ' ' . $this->_properties['last_name'];
    }

    public function _getTextStatus() {
        $status = $this->_properties['status'];
        if ($status == 0) {
            return 'Pending';
        }
        if ($status == 1) {
            return 'Approved';
        }
        return 'Denied';
    }

    public function _getComments() {
        if (array_key_exists('comments', $this->_properties)) {
            return $this->_properties['comments'] == null ? "None" : $this->_properties['comments'];
        }
    }

    public function getOptions($option = null) {
        $options = null;
        if ($option) {
            $options = [];
            array_push($options, $this->getOption($option));
        }
        else {
            $options = [];
            for ($i = 1; $i <= 3; $i++) {
                $option = $this->getOption($i);
                if ($option) {
                    array_push($options, $option);
                }
            }
        }
        return $options;
    }

    private function getOption($option) {
        if ($this->_properties['start_date_' . $option] == null) {
            return null;
        }
        return [
            'start' => $this->_properties['start_date_' . $option],
            'end' => $this->_properties['end_date_' . $option],
            'id' => $option
        ];
    }
}
?>