<?php

namespace App\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventException
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
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $headers = $event->getRequest()->headers;
        $locale = $headers->has('content-language') ? $headers->get('content-language') : 'en';
        $session = FamaCore::getSession();
        if ($session instanceof SessionInterface) {
            $session->set('locale', $locale);
        }

        $exception = $event->getThrowable();
        $response = new FamaResponse($exception);

        $event->setResponse($response);
    }
}
