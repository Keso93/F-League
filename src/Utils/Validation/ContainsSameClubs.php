<?php


namespace App\Utils\Validation;


use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainsSameClubs extends  Constraint
{
    public $message = "Clubs cannot be the same";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}