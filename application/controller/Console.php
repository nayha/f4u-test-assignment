<?php

require_once "./application/model/Client.php";
require_once "./application/model/Address.php";

use Ddd\Project\Client;
use Ddd\Project\Address;

Class Console {

    public function __construct () {
        
    }

    private function printMenu () {
        echo "===========================" . PHP_EOL .
            "Client Shipping Address Menu" . PHP_EOL .
            "===========================" . PHP_EOL .
            "1. Add new address" . PHP_EOL .
            "2. Update address" . PHP_EOL .
            "3. Delete an address" . PHP_EOL .
            "4. Retrieve address" . PHP_EOL .
            "5. Exit" . PHP_EOL .
            "-----------------------" . PHP_EOL .
            "Please enter your selection [1-5]: ";
    }

    private function requestInput ($lblQuestion) {
        echo $lblQuestion;
        return trim(fgets(STDIN));
    }

    private function selectClientId () {
        $client = new Client();
        $clientList = $client->list();

        print_r($clientList);
        echo "Enter client ID: ";

        getInputId:
        $clientId = (int)trim(fgets(STDIN));

        // check if client ID exists
        if (!$client->idExists($clientId, $clientList)) {
            echo "ID does not exist. Please enter a valid client ID: ";
            goto getInputId;
        }

        return $clientId;
    }

    private function selectAddressId ($addressList) {
        print_r($addressList);
        echo "Enter address Index number to update: ";

        getAddressId:
        $addressId = (int)trim(fgets(STDIN));

        // check if client ID exists
        if (!isset($addressList[$addressId])) {
            echo "ID does not exist. Please enter a valid client ID: ";
            goto getAddressId;
        }

        return $addressId;
    }

    private function askAddressDetails () {
        $inputCountry = $this->requestInput("Enter country: ");
        $inputCity = $this->requestInput("Enter city: ");
        $inputZip = $this->requestInput("Enter zip: ");
        $inputStreet = $this->requestInput("Enter street: ");
        $inputDefault = $this->requestInput("Set it to default address [y/n]: ");

        return array(
            $inputCountry,
            $inputCity,
            $inputZip,
            $inputStreet,
            $inputDefault
        );
    }

    private function addAddress () {
        $clientId = $this->selectClientId();

        list($inputCountry, $inputCity, $inputZip, $inputStreet, $inputDefault) = $this->askAddressDetails();

        $address = new Address();
        $clientAddress = $address->add($clientId, array(
            "country" => $inputCountry,
            "city" => $inputCity,
            "zip" => $inputZip,
            "street" => $inputStreet,
            "default" => ($inputDefault === "y" ? 1 : 0)
        ));

        print_r($clientAddress);
        echo PHP_EOL . 
            "--------------------" . PHP_EOL . 
            "Successfully added !" . PHP_EOL . 
            "--------------------" . PHP_EOL;
    }

    private function updateAddress () {
        $clientId = $this->selectClientId();

        $address = new Address();

        // display all addresses belong to $clientId
        $addressList = $address->ofClientId($clientId);

        // select ID to update
        $addressId = $this->selectAddressId($addressList);

        list($inputCountry, $inputCity, $inputZip, $inputStreet, $inputDefault) = $this->askAddressDetails();

        $clientAddress = $address->update($clientId, $addressId, array(
            "country" => $inputCountry,
            "city" => $inputCity,
            "zip" => $inputZip,
            "street" => $inputStreet,
            "default" => ($inputDefault === "y" ? 1 : 0)
        ));

        print_r($clientAddress);
        echo PHP_EOL . 
            "--------------------" . PHP_EOL . 
            "Successfully updated !" . PHP_EOL . 
            "--------------------" . PHP_EOL;
    }

    private function deleteAddress () {
        $clientId = $this->selectClientId();

        $address = new Address();

        // display all addresses belong to $clientId
        $addressList = $address->ofClientId($clientId);

        // select ID to update
        $addressId = $this->selectAddressId($addressList);

        $clientAddress = $address->delete($clientId, $addressId);

        print_r($clientAddress);
        echo PHP_EOL . 
            "--------------------" . PHP_EOL . 
            "Successfully deleted !" . PHP_EOL . 
            "--------------------" . PHP_EOL;
    }

    private function getAddress () {
        $clientId = $this->selectClientId();

        $address = new Address();

        // display all addresses belong to $clientId
        $addressInfo = $address->ofClientId($clientId, true);

        echo PHP_EOL . PHP_EOL;
        print_r($addressInfo);
        echo PHP_EOL . PHP_EOL;
    }

    public function init () {
        while (true) {
            $this->printMenu();

            // read user input
            $inputChoice = (int)trim(fgets(STDIN));

            if (5 === $inputChoice) {
                break;
            }

            switch ($inputChoice) {
                case 1:
                    $this->addAddress();
                    break;
                case 2:
                    $this->updateAddress();
                    break;
                case 3:
                    $this->deleteAddress();
                    break;
                case 4:
                    $this->getAddress();
                    break;
                default:
                    echo PHP_EOL . "Invalid selection. Please enter 1-5." . PHP_EOL . PHP_EOL;
            }
        }
    }

}