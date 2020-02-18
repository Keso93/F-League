<?php


namespace App\Controller;


use App\Manager\ImageManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class ImageController
{

    /**
     * @Route("/json/saveImage", name="json_save_image")
     */

    public function jsonSaveImage(Request $request){

        $data = $this->imageManager->saveImage($request);

        return new JsonResponse("dd");

    }

}