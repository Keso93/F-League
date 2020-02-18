<?php


namespace App\Utils\Validation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\VarDumper\VarDumper;

class ContainsSameClubsValidator extends ConstraintValidator
{


    public function validate($protocol, Constraint $constraint)
    {

        if ($protocol->getHomeClub() === $protocol->getGuestClub()){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

