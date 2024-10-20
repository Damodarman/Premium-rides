<?php

use Jschaedl\Iban\Iban;
use Jschaedl\Iban\Validation\IbanValidator;

function validate_iban(string $iban): bool
{
    $validator = new IbanValidator();

    return $validator->validate($iban);
}
