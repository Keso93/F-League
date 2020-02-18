<?php


namespace App\Utils\Listener;

use App\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Proxies\__CG__\App\Entity\Player;
use Symfony\Component\VarDumper\VarDumper;

class DatabaseActivitySubscriber implements EventSubscriber
{
    private $images;

    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents()
    {
        return [
            Events::preRemove,
            Events::postRemove,
            Events::preUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself


    public function postRemove(LifecycleEventArgs $args)
    {
        $this->logActivity('remove', $args);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->removeActivity('remove', $args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->editActivity('remove', $args);
    }


    private function logActivity(string $action, LifecycleEventArgs $args)
    {
//        $entity = $args->getObject();
//
//        if (!$entity instanceof Player) {
//            return;
//        }
//        $changes = $args->getEntityChangeSet();
//        $image = $changes['image'][0];
//
//        $entityManager = $args->getObjectManager();
//
//        $entityManager->remove($image);
//        $this->removeUpload($image);

    }

    private function editActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Player) {
            return;
        }
        $changes = $args->getEntityChangeSet();
        $image = $changes['image'][0];

        $entityManager = $args->getObjectManager();

        $entityManager->remove($image);
        $this->removeUpload($image);

    }


    private function removeActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Image) {
            return;
        }

        $this->removeUpload($entity);

    }

    public function removeUpload(Image $image)
    {
        $file = Image::DIR_PATH . $image->getWebPath();
        if (file_exists($file)) {
            unlink($file);
        }
    }
}