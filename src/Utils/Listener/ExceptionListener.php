<?php


namespace App\Utils\Listener;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\VarDumper\VarDumper;

class ExceptionListener
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedHttpException && $exception->getMessage() === "No delete"){

            $r = "No permission to delete performances!!!";
            $response = new Response($r);
            $event->setResponse($response);
        } else if ($exception instanceof AccessDeniedHttpException){
            $route = 'exception';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }
            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }



        // Customize your response object to display the exception details
//        $response->setContent($message);
//
//        // HttpExceptionInterface is a special type of exception that
//        // holds status code and header details
//        if ($exception instanceof HttpExceptionInterface) {
//            $response->setStatusCode($exception->getStatusCode());
//            $response->headers->replace($exception->getHeaders());
//        } else {
//            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
//        }

        // sends the modified response object to the event
    }

}