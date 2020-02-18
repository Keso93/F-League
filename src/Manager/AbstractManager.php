<?php


namespace App\Manager;


use App\Entity\BaseEntityInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractManager
{

    /** @var ValidatorInterface  */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return BaseEntityInterface|array
     */
    protected function validate(BaseEntityInterface $entity){

        $errors = $this->validator->validate($entity);

        if (count($errors) > 0){
            $errorMessages = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error){
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $errorMessages;
        }

        return $entity;
    }
}