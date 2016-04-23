<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;


use CLIFramework\ArgInfoList;
use CLIFramework\Command;
use GetOptionKit\Option;
use GetOptionKit\OptionCollection;
use Serps\HttpClient\CurlClient;
use Serps\HttpClient\PhantomJsClient;
use Serps\HttpClient\SpidyJsClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

class Search extends Command
{

    public function brief()
    {
        return 'Make a google search.';
    }

    function init()
    {
        parent::init();

    }

    public function arguments(ArgInfoList $args)
    {
        parent::arguments($args);
        $args->add('http client')
            ->desc('http client to use for the request')
            ->isa('string')
            ->validValues(['curl', 'phantomjs', 'spidyjs']);

        $args->add('keywords')
            ->desc('keywords to search for')
            ->isa('string');
    }


    public function options(OptionCollection $opts)
    {
        parent::options($opts);
        $opts->add('tld?', 'google tld to search');
        $opts->add('lr?', 'language restriction');
    }


    public function execute($keywords, $client = 'curl'){

        $httpClient = null;

        switch($client){
            case "curl":
                $httpClient = new CurlClient();
                break;
            case "phantomjs":
                $httpClient = new PhantomJsClient();
                break;
            case "spidyjs":
                $httpClient = new SpidyJsClient();
        }


        $tld = $this->getOptionCollection()->getLongOption('tld')->getValue();
        if(!$tld){
            $tld = 'com';
        }
        $lr = $this->getOptionCollection()->getLongOption('lr')->getValue();

        $googleClient = new GoogleClient($httpClient);
        $googleClient->request->setUserAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');

        $url = new GoogleUrl("google.$tld");
        $url->setSearchTerm($keywords);
        if($lr){
            $url->setLanguageRestriction($lr);
        }


        $response = $googleClient->query($url);

        $evaluated = $response->javascriptIsEvaluated();


        file_put_contents('/tmp/google-search.html', $response->getDom()->saveHTML());
        $naturalResults = $response->getNaturalResults();
        $items = $naturalResults->getItems();


        $data = [
            'url' => $response->getUrl()->buildUrl(),
            'evaluated' => $evaluated,
            'natural-results-count' => 0,
            'total-count' => $response->getNumberOfResults(),
            'natural-results' => []
        ];

        foreach($items as $item){
            $data['natural-results'][] = [
                'types' => $item->getTypes(),
                'title' => $item->title
            ];
        }

        $data['natural-results-count'] = count($data['natural-results']);

        echo json_encode($data, JSON_PRETTY_PRINT);



    }

}