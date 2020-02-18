<?php


namespace App\Utils\Listener;

use App\Entity\Club;
use App\Manager\ClubManager;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\VarDumper\VarDumper;

class ClubListener
{
    private $clubManager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(ClubManager $clubManager, Security $security)
    {
        $this->clubManager = $clubManager;
        $this->security = $security;
    }

    public function prePersist(Club $club, LifecycleEventArgs $event){

        $club->setCreatedBy($this->security->getUser()->getUsername());

    }

    public function preDelete(Club $club, LifecycleEventArgs $event){
        if ($this->security->getUser()->getRoles()[0] == "ROLE_ADMIN") {
            VarDumper::dump("dozvoljen upis");

            VarDumper::dump($event->getObject());exit;
        } else {
            throw new AccessDeniedHttpException('Niste ovlasteni da brisete klubove');
            VarDumper::dump("Niste ovlasteni da brisete klubove");exit;
        }
    }
}