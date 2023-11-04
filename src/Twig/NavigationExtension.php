<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    public function __construct(
        protected RequestStack $stack,
        protected UrlGeneratorInterface $urlGenerator,
        protected Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('nav_item', [ $this, 'renderItem'])
        ];
    }

    /**
     * Render a navigation item from the nav_item twig using a route name and and icon
     */
    public function renderItem(string $route, string $label, string $icon = null, string $classes = 'row round'): string
    {
        $current = $this->stack->getCurrentRequest();
        $name = $current->get('_route');
        $url = $this->urlGenerator->generate($route);
        if($route === $name) {
            $classes .= ' active';
        }

        return $this->twig->render('include/nav_item.html.twig', [
            'classes' => $classes,
            'url' => $url,
            'label' => $label,
            'icon' => $icon,
        ]);
    }
}
