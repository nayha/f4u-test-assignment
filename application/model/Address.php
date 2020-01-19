<?php

namespace Ddd\Project;

require_once "./application/service/Storage.php";

Class Address {
    private $storage = array();
    private $addressList = array();

    public function __construct () {
        $this->storage = new Storage;
        $this->addressList = $this->retrieve();
    }

    public function retrieve () {
        $addresses = $this->storage->load("./storage/addresses.json");

        return $addresses;
    }

    public function getAddressList () {
        return $this->addressList;
    }

    private function removeDefaultAddressOfClientId ($clientId) {
        foreach ($this->addressList[$clientId] as $addrK => $addrV) {
            $this->addressList[$clientId][$addrK]["default"] = 0;
        }
    }

    public function ofClientId ($clientId, $isDefault=false) {
        if (array_key_exists($clientId, $this->addressList)) {
            if ($isDefault) {
                // index 0 is default address
                return $this->addressList[$clientId][0];
            } else {
                return $this->addressList[$clientId];
            }
        }

        return [];
    }

    public function add ($clientId, $address) {
        // if new address is a default address, check if default address exists then update it to 0
        if (array_key_exists((string)$clientId, $this->addressList) && !empty($this->addressList[$clientId])) {
            if (!(count($this->addressList[$clientId]) < MAX_ADDRESS_NUM)) {
                throw new Exception("Exceeds maximum number of addresses allowed");
            }

            if ($address["default"] === 1) {
                $this->removeDefaultAddressOfClientId($clientId);

                // add default address to the first element
                array_unshift($this->addressList[$clientId], $address);
            }
        } else {
            $this->addressList[$clientId][] = $address;
        }

        // write to file
        $this->storage->write("./storage/addresses.json", $this->addressList);

        return $this->addressList[$clientId];
    }

    public function update ($clientId, $addressId, $address) {
        // if updated address is a default address, check if default address exists then update it to 0
        if ($address["default"] === 1 && array_key_exists($clientId, $this->addressList) && !empty($this->addressList[$clientId])) {
            $this->removeDefaultAddressOfClientId($clientId);

            $this->addressList[$clientId][$addressId] = $address;

            // add default address to the first element
            if ($addressId > 0) {
                $temp = $this->addressList[$clientId][0];
                $this->addressList[$clientId][0] = $this->addressList[$clientId][$addressId];
                $this->addressList[$clientId][$addressId] = $temp;
            }
        } else {
            $this->addressList[$clientId][$addressId] = $address;
        }

        return $this->addressList[$clientId];
    }

    public function delete ($clientId, $addressId) {
        if (isset($this->addressList[$clientId][$addressId])) {
            unset($this->addressList[$clientId][$addressId]);
        } else {
            throw new Exception("Address could not be found to delete.");
        }

        $this->addressList[$clientId] = array_values($this->addressList[$clientId]);

        return $this->addressList[$clientId];
    }
}