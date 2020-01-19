<?php

namespace Ddd\Project\tests\units;

require_once './application/model/Client.php';

use atoum;

class Client extends atoum {
    public function testList () {
        $this
            ->given($this->newTestedInstance)
            ->then
                ->array($this->testedInstance->list())
                    ->isNotEmpty()
        ;
    }

    public function testIdExists () {
        $this
           ->given($this->newTestedInstance)
            ->then
                ->boolean($this->testedInstance->idExists(1, $this->testedInstance->list()))
                    ->isTrue()
                ->boolean($this->testedInstance->idExists("na", $this->testedInstance->list()))
                    ->isFalse()
        ;
    }
}