<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityProviderInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalNote;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository\LocalNoteRepository;
use Override;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Uid\Uuid;

class LocalNoteService implements ObjectProviderInterface, LocalObjectEntityProviderInterface
{
    public function __construct(
        private readonly LocalNoteRepository $localNoteRepository,
        private readonly UrlMatcherInterface $urlMatcher,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator
    ) {
    }

    #[Override]
    public function provideEntity(Uri $uri): LocalNote|false|null
    {
        $uuid = $this->findUuid($uri);
        return (null === $uuid) ? false : $this->localNoteRepository->findOneByUuid($uuid);
    }

    #[Override]
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null
    {
        $uuid = $this->findUuid($uri);
        if (null === $uuid) {
            return false;
        }

        $localNote = $this->localNoteRepository->findOneByUuid($uuid);
        if (null === $localNote) {
            return false;
        }

        $note = new Note();
        $note->id = $uri;
        $note->attributedTo = LinkableObjectsCollection::singleLinkFromUri(
            $this->localActorUriGenerator->generateId($localNote->attributedTo)
        );
        $note->content = $localNote->source;

        return $note;
    }

    private function findUuid(Uri $uri): ?Uuid
    {
        $this->urlMatcher->getContext()->setMethod('GET');
        try {
            $parameters = $this->urlMatcher->match($uri->getPathWithQueryAndFragment() ?? '');
        } catch (ResourceNotFoundException) {
            return null;
        }
        if ('ddr.activity_pub_orm.tests.note.get' !== ($parameters['_route'] ?? null)) {
            return null;
        }

        return Uuid::fromString($parameters['uuid']);
    }

}
