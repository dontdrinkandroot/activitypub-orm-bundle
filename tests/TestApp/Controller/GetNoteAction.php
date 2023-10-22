<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetNoteAction extends AbstractController
{
    public function __invoke(): Response
    {
        // TODO: Implement __invoke() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
