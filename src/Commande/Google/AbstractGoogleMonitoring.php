<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;

use CLIFramework\Command;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

abstract class AbstractGoogleMonitoring extends Command
{

    abstract public function getHttpClient();


    public function execute(){
        $googleClient = new GoogleClient($this->getHttpClient());

        $url = new GoogleUrl("google.fr");
        $url->setSearchTerm("simpsons the movie trailer");

        $response = $googleClient->query($url);

        $naturalResults = $response->getNaturalResults();
        $items = $naturalResults->getItems();

        var_dump($items[0]->getData());
    }


}