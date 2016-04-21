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
        $googleClient->request->setUserAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');

        $url = new GoogleUrl("google.fr");
        $url->setSearchTerm("simpsons the movie trailer");

        $response = $googleClient->query($url);

        $evaluated = $response->javascriptIsEvaluated();


        file_put_contents('/tmp/google.html', $response->getDom()->saveHTML());
        $naturalResults = $response->getNaturalResults();
        $items = $naturalResults->getItems();


        $data = [
            'evaluated' => $evaluated,
            'results' => [
                'video' => $items[0]->is(NaturalResultType::CLASSICAL_VIDEO) && $items[0]->videoLarge === true
            ]
        ];

        echo json_encode($data, JSON_PRETTY_PRINT);

    }


}
