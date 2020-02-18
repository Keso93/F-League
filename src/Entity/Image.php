<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    public const DIR_PATH = __DIR__ . "/../../public/";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    private $base64;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBase64()
    {
        return $this->base64;
    }

    /**
     * @param mixed $base64
     */
    public function setBase64($base64): void
    {
        $this->base64 = $base64;
    }

    public function saveToFile(){
        $imageName = $this->getName();

        $base_to_php = explode(',', $this->getBase64());

        $img = base64_decode($base_to_php[1]);

        $filepath = self::DIR_PATH . $this->getWebPath();

        while (file_exists($filepath)) {
            $this->setName(uniqid() . $imageName);
            $filepath = self::DIR_PATH . $this->getWebPath();
        }

        file_put_contents($filepath, $img);
    }

    public function getWebPath()
    {
        return "assets/images/" . $this->getName();
    }
//
//    /**
//     * @ORM\PostRemove()
//     */
//    public function removeUpload()
//    {
//        $file = self::DIR_PATH . $this->getWebPath();
//        if (file_exists($file)) {
//            unlink($file);
//        }
//    }

}
