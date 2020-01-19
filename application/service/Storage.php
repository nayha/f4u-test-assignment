<?php

namespace Ddd\Project;

Class Storage {
    public function __construct () {

    }

    public function load ($file) {
        $fileContent = @file_get_contents($file);

        // the file may not exist at the beginning. so set an empty array.
        if ($fileContent === false) {
            return array();
        }

        return json_decode($fileContent, true);
    }

    public function write ($file, $data) {
        return file_put_contents($file, json_encode($data));
    }
}