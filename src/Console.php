<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus;

use CLIFramework\Application;

class Console extends Application{

    public function init()
    {
        parent::init();
        $this->command( 'google', '\SerpsStatus\Commande\Google' );
    }
}
