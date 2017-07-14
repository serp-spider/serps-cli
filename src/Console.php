<?php
/**
 * @license see LICENSE
 */

namespace SerpsCli;


use SerpsCli\Commande\Google\Search as GoogleSearch;
use Symfony\Component\Console\Application;

class Console extends Application {

    public function __construct()
    {
        parent::__construct('SERPS', '1.0');
        $this->add(new GoogleSearch());
    }
}
