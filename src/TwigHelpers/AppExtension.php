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
            new TwigFunction('getDaysBetween2Dates', [$this, 'getDaysBetween2Dates']),
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

    /**
     * Retourne la différence de jours entre $date1 et $date2 ($date1 - $date2)
     * si le paramètre $absolute est faux, la valeur de retour est négative si $date2 est postérieur à $date1
     *
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @param Boolean   $absolute
     *
     * @return int
     */
    public function getDaysBetween2Dates(\DateTime $date1, \DateTime $date2, bool $absolute = true): int
    {
        $interval = $date2->diff($date1);
        // si l'on doit prendre en compte la position relative (!$absolute) et que la position relative est négative,
        // on retourne la valeur négative sinon, on retourne la valeur absolue
        return (! $absolute and $interval->invert) ? -$interval->days : $interval->days;
    }
}