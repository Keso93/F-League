<?php


namespace App\Utils\ParamConverter\Player;


use App\Entity\Player;
use App\Manager\ClubManager;
use App\Manager\LocationManager;
use App\Manager\PlayerManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\VarDumper\VarDumper;

class EditPlayerConverter implements ParamConverterInterface
{

    private $requestStack;
    /**
     * @var LocationManager
     */
    private $locationManager;
    /**
     * @var ClubManager
     */
    private $clubManager;
    /**
     * @var PlayerManager
     */
    private $playerManager;

    public function __construct(LocationManager $locationManager, ClubManager $clubManager, PlayerManager $playerManager)
    {
        $this->locationManager = $locationManager;
        $this->clubManager = $clubManager;
        $this->playerManager = $playerManager;
    }

    /**
     * @inheritDoc
     */
    public function apply(Request $request, ParamConverter $configuration)
    {

        $positions = Player::getPositions();
        $locations = $this->locationManager->findAll();
        $clubs = $this->clubManager->findAll();
        $player = $this->playerManager->find($request->get('id'));

        $object = new ArrayCollection(['player' => $player, 'locations' => $locations, 'positions' => $positions, 'clubs' => $clubs]);

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function supports(ParamConverter $configuration)
    {
        $reflectionClass = new \ReflectionClass(get_called_class());

        return strtolower($reflectionClass->getShortName()) === strtolower($configuration->getName());

    }

}