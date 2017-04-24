<?php
/**
 * @license see LICENSE
 */

namespace SerpsCli\Commande\Google;


use CLIFramework\ArgInfoList;
use CLIFramework\Command;
use GetOptionKit\Option;
use GetOptionKit\OptionCollection;
use Serps\Core\Http\Proxy;
use Serps\Exception;
use Serps\HttpClient\CurlClient;
use Serps\HttpClient\PhantomJsClient;
use Serps\HttpClient\SpidyJsClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\SearchEngine\Google\NaturalResultType;

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

    public function arguments($args)
    {
        /* @var $args ArgInfoList */
        parent::arguments($args);
        $args->add('keywords')
            ->desc('keywords to search for')
            ->isa('string');
    }


    public function options($opts)
    {
        /* @var $opts OptionCollection */
        parent::options($opts);
        $opts->add('tld?', 'google tld to search, e.g "--tld=co.uk" to search google.co.uk');
        $opts->add('lr?', 'language restriction');
        $opts->add('http-client?')
            ->isa('string')
            ->validValues(['curl', 'phantomjs', 'spidyjs'])
            ->defaultValue("curl")
            ->desc('http client to use (default curl)');
        $opts->add('proxy?')
            ->isa('string')
            ->desc('use the given proxy, e.g "--tld=http://my-proxy-host:8080"');
        $opts->add('page?', 'The google page number')->isTypeNumber();
        $opts->add('dump?', 'dump the dom into a file. Useful for debuging purpose.')
            ->isa('string');
        $opts->add('force-dump?', 'Force the dump option to overide if the file exists.')
            ->isa('boolean');
        $opts->add('res-per-page?', 'the number of results per page (max 100)')->isTypeNumber();
    }


    public function execute($keywords){

        // PARSE OPTIONS

        $client = $this->getOptionCollection()->getLongOption('http-client')->getValue();
        switch($client){
            case "phantomjs":
                $httpClient = new PhantomJsClient();
                break;
            case "spidyjs":
                $httpClient = new SpidyJsClient();
                break;
            default:
                $client = "curl";
                $httpClient = new CurlClient();
        }

        $tld = $this->getOptionCollection()->getLongOption('tld')->getValue();
        if(!$tld){
            $tld = 'com';
        }
        $lr = $this->getOptionCollection()->getLongOption('lr')->getValue();

        $proxyString = $this->getOptionCollection()->getLongOption('proxy')->getValue();
        $proxy = null;
        if($proxyString){
            $proxy = Proxy::createFromString($proxyString);
        }

        $page = $this->getOptionCollection()->getLongOption('page')->getValue();
        if(!$page){
            $page = 1;
        }
        $resPerPage = $this->getOptionCollection()->getLongOption('res-per-page')->getValue();
        if(!$resPerPage){
            $resPerPage = 10;
        }
        // Allow to dump html response in a file
        $dump = $this->getOptionCollection()->getLongOption('dump')->getValue();
        if($dump){

            $forceDump = $this->getOptionCollection()->getLongOption('force-dump')->getValue();

            if(!$forceDump && file_exists($dump)){
                throw new \Exception('file ' . $dump . ' already exists. Use --force-dump to allow file override.');
            } elseif (!is_writable(dirname($dump))) {
                throw new \Exception('file ' . $dump . ' cannot be written');
            }
        }

        // PREPARE CLIENT

        $googleClient = new GoogleClient($httpClient);
        $googleClient->request->setUserAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');

        $url = new GoogleUrl("google.$tld");
        $url->setSearchTerm($keywords);
        $url->setPage($page);
        $url->setResultsPerPage($resPerPage);
        if($lr){
            $url->setLanguageRestriction($lr);
        }


        // QUERY

        $response = $googleClient->query($url, $proxy);


        // DUMP IF ASKED

        if($dump){
            $done = file_put_contents($dump, $response->getDom()->saveHTML());
            if(!$done){
                throw new \Exception('An error happened while dumping the file ' . $dump);
            }
        }



        // OUTPUT RESULTS

        $evaluated = $response->javascriptIsEvaluated();

        $naturalResults = $response->getNaturalResults();
        $items = $naturalResults->getItems();


        $data = [
            'initial-url' => (string) $url,
            'url' => (string) $response->getUrl(),
            'http-client' => $client,
            'evaluated' => $evaluated,
            'natural-results-count' => 0,
            'total-count' => $response->getNumberOfResults(),
            'natural-results' => [],
            'related-searches' => []
        ];

        // Feed natural-results
        foreach($items as $item){

            $r = [
                'types' => $item->getTypes()
            ];

            if ($item->is(NaturalResultType::CLASSICAL)) {
                $r['title'] = $item->title;
                $r['url'] = (string) $item->url;
            } elseif ($item->is(NaturalResultType::TOP_STORIES)){
                $r['is_carousel'] = $item->is_carousel;
                $news = [];
                foreach($item->news as $newsItem){
                    $news[] = [
                        'title' => $newsItem->title,
                        'url'   => $newsItem->url
                    ];

                }
                $r['news'] = $news;
            }

            $data['natural-results'][] = $r;
        }

        // Feed related-searches
        foreach($response->getRelatedSearches() as $search){
            $data['related-searches'][] = [
                'title' => $search->title,
                'url' => $search->url
            ];
        }

        $data['natural-results-count'] = count($data['natural-results']);

        echo json_encode($data, JSON_PRETTY_PRINT);



    }

}
