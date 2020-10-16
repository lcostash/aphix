<?php

namespace App\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Exception;

class EventRequest
{
    /**
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param ContainerInterface $container
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator, ContainerInterface $container)
    {
        FamaCore::setSession($session);
        FamaCore::setTranslator($translator);
        FamaCore::setContainer($container);
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $headers = $event->getRequest()->headers;
        $locale = $headers->has('content-language') ? $headers->get('content-language') : 'en';
        $session = FamaCore::getSession();
        if ($session instanceof SessionInterface) {
            $session->set('locale', $locale);
        }

        try {
            if (!$event->isMasterRequest()) {
                return;
            }

        } catch (Exception $exception) {
            $event->setResponse(new FamaResponse($exception));
        }
    }
}