<?php

namespace Ddd\Project\tests\units;

require_once './application/model/Address.php';

use atoum;

class Address extends atoum {
    public function testRetrieve () {
        $this
            ->given($this->newTestedInstance)
            ->then
                ->array($this->testedInstance->retrieve())
                    ->isNotEmpty()
        ;
    }

    public function testUpdate () {
        $updatedAddress = array(
            "country" => "Switzerland",
            "city" => "Bern", // updated field
            "zip" => "3812",
            "street" => "Wilderswil",
            "default" => 0
        );

        $this->newTestedInstance;
        $updatedClientAddressList = $this->testedInstance->update(1, 1, $updatedAddress);

        $this
            ->string($updatedClientAddressList[1]["city"])
                ->isEqualTo("Bern")
        ;
    }

}