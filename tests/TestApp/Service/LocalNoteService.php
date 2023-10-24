<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityProviderInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalNote;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository\LocalNoteRepository;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Uid\Uuid;

class LocalNoteService implements LocalObjectEntityProviderInterface
{
    public function __construct(
        private readonly LocalNoteRepository $localNoteRepository,
        private readonly UrlMatcherInterface $urlMatcher
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function has(Uri $uri): bool
    {
        $uuid = $this->findUuid($uri);
        return null !== $uuid && $this->localNoteRepository->findOneByUuid($uuid) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Uri $uri): ?LocalNote
    {
        $uuid = $this->findUuid($uri);
        return (null === $uuid) ? null : $this->localNoteRepository->findOneByUuid($uuid);
    }

    private function findUuid(Uri $uri): ?Uuid
    {
        $this->urlMatcher->getContext()->setMethod('GET');
        $parameters = $this->urlMatcher->match($uri->getPathWithQueryAndFragment() ?? '');
        if ('ddr.activity_pub_orm.tests.note.get' !== ($parameters['_route'] ?? null)) {
            return null;
        }

        return Uuid::fromString($parameters['uuid']);
    }
}
