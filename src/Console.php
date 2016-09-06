<?php
/**
 * @license see LICENSE
 */

namespace SerpsCli;

use CLIFramework\Application;

class Console extends Application{

    public function init()
    {
        parent::init();
        $this->command( 'google', '\SerpsCli\Commande\Google' );
    }
}
