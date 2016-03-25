<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;


use Serps\HttpClient\CurlClient;

class Curl extends AbstractGoogleMonitoring
{
    public function getHttpClient()
    {
        return new CurlClient();
    }


}