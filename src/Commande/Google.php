<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande;

use CLIFramework\Command;
use Serps\HttpClient\CurlClient;
use Serps\HttpClient\PhantomJsClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

class Google extends Command
{

    public function brief()
    {
        return 'Execute google tests.';
    }

    function init()
    {
        parent::init();
        $this->command('curl', '\SerpsStatus\Commande\Google\Curl');
        $this->command('phantomjs', '\SerpsStatus\Commande\Google\PhantomJs');
    }

    public function execute(){}
}