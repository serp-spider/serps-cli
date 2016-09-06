<?php
/**
 * @license see LICENSE
 */

namespace SerpsCli\Commande;

use CLIFramework\Command;
use Serps\HttpClient\CurlClient;
use Serps\HttpClient\PhantomJsClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

class Google extends Command
{

    public function brief()
    {
        return 'Google related commands. "help google" for more info';
    }

    function init()
    {
        parent::init();
        $this->command('search', '\SerpsCli\Commande\Google\Search');
    }

    public function execute(){}
}
