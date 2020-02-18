<?php

namespace App\Utils\Validation;

use App\Entity\BaseEntityInterface;
use App\Entity\Game;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\VarDumper;

class EntityValidator
{
    /** @var ValidatorInterface  */
    private $validator;

    /**
     * EntityValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return bool|array
     */
    public function validate(BaseEntityInterface $entity){
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0){
            $errorMessages = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error){
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            if (count($errorMessages) > 0){
                return false;
            } else {
                return $errorMessages;
            }
        }

        if ($entity instanceof Game){
            if ($entity->getGuestClub() === $entity->getHomeClub()){
                return false;
            }
        }

        return true;
    }

}