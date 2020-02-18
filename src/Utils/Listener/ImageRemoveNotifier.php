<?php


namespace App\Utils\Listener;

use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\VarDumper\VarDumper;


class ImageRemoveNotifier
{
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event

    /**
     * @ORM\PostRemove()
     */
    public function postRemove(Image $image, LifecycleEventArgs $event)
    {
        VarDumper::dump($event);exit;
        $this->removeUpload($image);
    }

    public function removeUpload(Image $image)
    {
        $file = Image::DIR_PATH . $image->getWebPath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

}