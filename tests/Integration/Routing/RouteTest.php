<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Routing;

use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class RouteTest extends WebTestCase
{
    public function testRoutes(): void
    {
        $this->markTestSkipped('Outputs the configured routes. Useful for development.');

        $router = self::getContainer()->get(RouterInterface::class);
        self::assertInstanceOf(RouterInterface::class, $router);
        /** @var array<string, Route> $routes */
        $routes = $router->getRouteCollection();
        foreach ($routes as $name => $route) {
            echo sprintf("[%s] %s : %s\n", implode(',', $route->getMethods()), $name, $route->getPath());
        }
    }
}
