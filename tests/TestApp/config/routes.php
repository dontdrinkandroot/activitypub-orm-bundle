<?php

use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Controller\GetNoteAction;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('@DdrActivityPubCoreBundle/config/routes.php');

    $routes->add('ddr.activity_pub_orm.tests.note.get', '/notes/{uuid}')
        ->controller(GetNoteAction::class)
        ->methods(['GET']);
};
