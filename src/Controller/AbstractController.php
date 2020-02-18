<?php


namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Manager\BaseManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;


abstract class AbstractController extends BaseAbstractController
{
    abstract protected function getManager(): BaseManagerInterface;

    protected function showAll(string $template)
    {
        return $this->render($template, array('items' => $this->getManager()->findAll()));
    }

    protected function jsonAdd(Request $request)
    {
        $data = json_decode($request->getContent());
        $item = $this->getManager()->persist($data);

        return new JsonResponse($item instanceof BaseEntityInterface ? $this->formatResponse($item) : $this->formatCustomResponse($item));
    }


    protected function editShow($id, string $template)
    {
        return $this->render($template, array('item' => $this->getManager()->find($id)));
    }

    protected function jsonEdit(BaseEntityInterface $entity){
        $item = $this->getManager()->merge($entity);

        return new JsonResponse($item instanceof BaseEntityInterface ? $this->formatResponse($item) : $this->formatCustomResponse($item));
    }

    protected function jsonDelete(BaseEntityInterface $entity)
    {
        try {
            $this->getManager()->delete($entity);

            $response = [
                'code' => 200,
                'status' => 'deleted',
            ];
        } catch (\Exception $exception){
            $response = [
                'code' => 500,
                'status' => 'error',
            ];
        }
        return new JsonResponse($response);

    }


    protected function boolResponse(bool $bool)
    {
        if($bool){
            return [
                'code' => 200,
                'status' => 'success',
            ];
        } else {
            return [
                'code' => 500,
                'status' => 'error',
            ];
        }
    }

    protected function formatResponse(BaseEntityInterface $entity)
    {
        if($entity->getId()){
            return [
                'code' => 200,
                'status' => 'saved',
                'id' => $entity->getId(),
            ];
        } else {
            return [
                'code' => 500,
                'status' => 'error',
            ];
        }
    }

    protected function formatCustomResponse($data, $code = 400){
        return [
            'code' => $code,
            'data' => $data,
        ];
    }

}