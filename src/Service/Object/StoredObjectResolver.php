<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredObjectRepository;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;

class StoredObjectResolver implements StoredObjectResolverInterface
{
    public function __construct(
        private readonly StoredObjectRepository $storedObjectRepository,
        private readonly ObjectResolverInterface $objectResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $uri, string $type = StoredObject::class): ?StoredObject
    {
        $storedObject = $this->storedObjectRepository->findOneByUri($uri);
        if (null !== $storedObject) {
            return Asserted::instanceOf($storedObject, $type);
        }

        $object = $this->objectResolver->resolve($uri);
        if (null === $object) {
            return null;
        }

        $storedObject = $this->storedObjectRepository->findOneByUri($uri)
            ?? throw new RuntimeException('Object not found in storage');
        return Asserted::instanceOf($storedObject, $type);
    }
}
