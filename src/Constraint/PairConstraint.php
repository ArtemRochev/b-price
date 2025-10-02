<?php

namespace App\Constraint;

use App\DTO\ExchangePair;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LengthValidator;

class PairConstraint extends Length
{
    public function __construct()
    {
        parent::__construct(ExchangePair::CURRENCY_CODE_LEN * 2 + 1);
    }

    public function validatedBy(): string
    {
        return LengthValidator::class;
    }
}
