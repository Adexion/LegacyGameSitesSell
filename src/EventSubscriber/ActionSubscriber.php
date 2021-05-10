<?php

namespace MNGame\EventSubscriber;

use MNGame\Database\Repository\ModuleEnabledRepository;
use MNGame\Service\Route\ModuleProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ActionSubscriber implements EventSubscriberInterface
{
    private ModuleEnabledRepository $repository;
    private RouterInterface $router;

    public function __construct(ModuleEnabledRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (Request::METHOD_OPTIONS === $event->getRequest()->getRealMethod()) {
            $event->setResponse(new Response());
        }
    }

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if ($this->shouldConnectionBeRedirectToHomepage($request->get('_route'))) {
            $url = $this->router->generate('index');
            $event->setController(function () use ($url) {
                return new RedirectResponse($url);
            });
        }

        $setCookie = $request->query->get('new');
        if (isset($setCookie)) {
            $request->cookies->set('new', $setCookie);
        }

        if (strpos($request->getContentType(), 'json') === false) {
            return;
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Request is not valid JSON');
        }

        $request->request->replace(is_array($data) ? $data : []);
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
        $event->getResponse()->headers->set('Access-Control-Allow-Methods', '*');
        $event->getResponse()->headers->set('Access-Control-Allow-Headers', 'x-auth-token, Content-Type');
    }

    private function shouldConnectionBeRedirectToHomepage(?string $route): bool
    {
        $moduleProvider = new ModuleProvider();
        $moduleName = $moduleProvider->getModuleNameByRoute($route);

        $moduleEnabled = $this->repository->findOneBy(['name' => $moduleName ?? '']);

        return $moduleEnabled ? !$moduleEnabled->isActive() : false;
    }
}
