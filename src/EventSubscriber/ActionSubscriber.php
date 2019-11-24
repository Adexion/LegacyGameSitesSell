<?php

namespace ModernGame\EventSubscriber;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ActionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->getContent()) {
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
    }
}
