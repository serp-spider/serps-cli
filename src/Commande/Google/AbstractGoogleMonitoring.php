<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;

use CLIFramework\Command;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\SearchEngine\Google\NaturalResultType;

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


        $data = [
            'video' => $items[0]->is(NaturalResultType::CLASSICAL_VIDEO) && $items[0]->videoLarge === true
        ];

        echo json_encode($data, JSON_PRETTY_PRINT);

    }


}