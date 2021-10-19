<?php

namespace App\TwigHelpers;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gravatarUrl', [$this, 'gravatarUrl']),
            new TwigFunction('setActive', [$this, 'setActive']),
        ];
    }

    /**
     * Recuperation de l'url de l'avatars
     *
     * @param string|null $email
     * @param int         $size
     *
     * @return string
     */
    public function gravatarUrl(string $email = null, int $size = 300): string
    {
        return "https://gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    /**
     * Gere l'etat actif des liens
     *
     * @param $routes array
     *
     * @return string
     */
    public function setActive(array $routes): string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->get('_route');

        foreach ($routes as $route) {
            if ($route === $currentRoute) {
                return 'active';
            }
        }

        return '';
    }
}