<?php
/**
 * @license see LICENSE
 */

namespace SerpsStatus\Commande\Google;


use Serps\HttpClient\PhantomJsClient;

class PhantomJs extends AbstractGoogleMonitoring
{
    public function getHttpClient()
    {
        return new PhantomJsClient();
    }


}