<?php

namespace App\Services;

use php_iban\IBAN;  // Import the IBAN class

class IbanValidationService
{
    private $countryMap = [
        'HR' => 'Croatia',
        'DE' => 'Germany',
        'LT' => 'Lithuania',
        'FR' => 'France',
        // Add other country codes and their full names here
    ];

    // Function to validate the IBAN
    public function validate(string $iban): bool
    {
        $ibanObject = new IBAN($iban);
        return $ibanObject->Verify();
    }

    // Function to get the recipient country code from the IBAN
    public function getRecipientCountry(string $iban): string
    {
        $ibanObject = new IBAN($iban);
        return $ibanObject->Country($iban);  // Returns ISO country code
    }


    // Function to get the BIC code from the IBAN
    public function getBIC(string $iban): ?string
    {
        $ibanObject = new IBAN($iban);
        if ($ibanObject->Verify()) {
            return $ibanObject->BIC();  // Extracts BIC
        }
        return null;
    }
}
