<?php
namespace App\Shell;

use Cake\Console\Shell;

class CatExpirationShell extends Shell {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function main() {
        $users = $this->Users->find();

        foreach ($users as $user) {
            $expiring = $user->checkCATCertification('=', true);
            if ($expiring) {
                $this->out("Notified " . $user->email);
            }
        }
    }
}

