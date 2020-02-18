<?php


namespace App\Utils\ParamConverter;


use App\Entity\Performance;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class SerializedParamConverter implements ParamConverterInterface
{
    public const CONVERTER = 'LFConverter';

    private $serializer;
    /**
     * @var PropertyInfoExtractor
     */
    private $propertyInfoExtractor;

    public function __construct(SerializerInterface $serializer, PropertyInfoExtractorInterface $propertyInfoExtractor)
    {
        $this->serializer = $serializer;
        $this->propertyInfoExtractor = $propertyInfoExtractor;
    }

    /**
     * @inheritDoc
     */
    public function supports(ParamConverter $configuration)
    {
        if (!$configuration->getClass()) {
            return false;
        }

        preg_match("/" . self::CONVERTER . "/", $configuration->getName(), $matches);

        if (count($matches) > 0){

            return true;
        }


        if (!($options = $configuration->getOptions()) || count($options) === 0) {
            return false;
        }

        return isset($options["leagueConverter"]) && $options["leagueConverter"] === true;
    }

    /**
     * @inheritDoc
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        if($class === ArrayCollection::class){

            $arr = json_decode($request->getContent());
            $entity = ucfirst(substr($configuration->getName(), 0, -strlen(self::CONVERTER)));
            $entity = sprintf('App\Entity\\%s', $entity);

            try {
                $out = new ArrayCollection();

                foreach ($arr as $key){
                    $key = json_encode($key);
                    $out->add($this->serializer->deserialize($key, $entity, 'json'));
                }
            }
            catch (JsonException $e) {
                throw new NotFoundHttpException(sprintf('Could not deserialize request content to object of type "%s"',
                    $class));
            }
            catch (\Exception $exception){
                VarDumper::dump($exception);exit;
            }
            $request->attributes->set($configuration->getName(), $out);

            return true;
        } else {

            try {
                $object = $this->serializer->deserialize(
                    $request->getContent(),
                    $class,
                    'json'
                );
            }
            catch (JsonException $e) {
                throw new NotFoundHttpException(sprintf('Could not deserialize request content to object of type "%s"',
                    $class));
            }
            catch (\Exception $exception){
                VarDumper::dump($exception);exit;
            }

            // set the object as the request attribute with the given name
            // (this will later be an argument for the action)
            $request->attributes->set($configuration->getName(), $object);

            return true;
        }

    }


}