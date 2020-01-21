<?php

namespace Ddd\Project;

require_once "./application/service/Storage.php";

use Ddd\Project\Storage;

Class Client {
    private $storage = array();

    public function __construct () {
        $this->storage = new Storage;
    }

    public function list () {
        $clients = $this->storage->load("./storage/clients.json");

        return $clients;
    }

    public function idExists ($needle, $haystack) {
        if (is_array($haystack) && count($haystack)) {
            foreach ($haystack as $client) {
                if ($client["ID"] == $needle) {
                    return true;
                }
            }
        }

        return false;
    }
}