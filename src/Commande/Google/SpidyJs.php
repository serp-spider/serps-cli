<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;


use Serps\HttpClient\SpidyJsClient;

class SpidyJs extends AbstractGoogleMonitoring
{
    public function getHttpClient()
    {
        return new SpidyJsClient("spidyjs");
    }


}
