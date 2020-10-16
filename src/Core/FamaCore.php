<?php declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FamaCore
{
    /**
     * @var SessionInterface
     */
    private static $session;

    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * @var TranslatorInterface
     */
    private static $translator;


    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }


    /**
     * @param $data
     * @return mixed
     */
    private static function base64Encode($data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }


    /**
     * @param $data
     * @return false|string
     */
    private static function base64Decode($data)
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }


    /**
     * @return SessionInterface|null
     */
    public static function getSession(): ?SessionInterface
    {
        return self::$session;
    }


    /**
     * @param SessionInterface $session
     */
    public static function setSession(SessionInterface $session)
    {
        if (!isset(self::$session)) {
            self::$session = $session;
        }
    }


    /**
     * @return ContainerInterface|null
     */
    public static function getContainer(): ?ContainerInterface
    {
        return self::$container;
    }


    /**
     * @param ContainerInterface $container
     */
    public static function setContainer(ContainerInterface $container)
    {
        if (!isset(self::$container)) {
            self::$container = $container;
        }
        if ($container instanceof Container) {
            if ($container->hasParameter('token')) {
                $token = $container->getParameter('token');
                $session = self::getSession();
                if ($session instanceof SessionInterface) {
                    $session->set('tokenSecret', $token['secret']);
                    $session->set('tokenExpiresIn', (int)$token['expires_in']);
                }
            }
        }
    }


    /**
     * @return TranslatorInterface|null
     */
    public static function getTranslator(): ?TranslatorInterface
    {
        return self::$translator;
    }


    /**
     * @param TranslatorInterface $translator
     */
    public static function setTranslator(TranslatorInterface $translator)
    {
        if (!isset(self::$translator)) {
            self::$translator = $translator;
        }
    }


    /**
     * @param string $text
     * @param array $params
     * @param string|null $domain
     * @return string
     */
    public static function translate(string $text, array $params = [], string $domain = null): string
    {
        $locale = 'en';
        $session = self::getSession();
        if ($session instanceof SessionInterface) {
            $locale = $session->get('locale', 'en');
        }
        $translator = self::getTranslator();
        if ($translator instanceof TranslatorInterface) {
            $text = $translator->trans($text, $params, $domain, $locale);
        }

        return $text;
    }
}