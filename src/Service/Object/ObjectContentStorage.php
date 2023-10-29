<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\ObjectContent;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ObjectContentRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredObjectRepository;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectContentStorage implements ObjectContentStorageInterface
{
    public function __construct(
        private readonly ObjectContentRepository $objectContentRepository,
        private readonly StoredObjectRepository $storedObjectRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function store(Uri|StoredObject $object, CoreObject|string $content): void
    {
        $uri = $object instanceof StoredObject ? $object->uri : $object;

        $stringContent = is_string($content)
            ? $content
            : $this->serializer->serialize($content, ActivityStreamEncoder::FORMAT);

        $objectContent = $this->objectContentRepository->findOneByUri($uri);
        if (null !== $objectContent) {
            $objectContent->content = $stringContent;
            $this->objectContentRepository->update($objectContent);

            return;
        }

        $resolvedObject = $object instanceof StoredObject
            ? $object
            : $this->storedObjectRepository->findOneByUri($uri)
            ?? throw new RuntimeException('Object not found');
        $objectContent = new ObjectContent(
            $resolvedObject,
            $stringContent
        );
        $this->objectContentRepository->create($objectContent);
    }

    /**
     * {@inheritdoc}
     */
    public function find(Uri $uri, string $type = CoreObject::class): ?CoreObject
    {
        $content = $this->findContent($uri);
        if (null === $content) {
            return null;
        }

        $object = $this->serializer->deserialize($content, CoreType::class, ActivityStreamEncoder::FORMAT);
        return Asserted::instanceOf($object, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function findContent(Uri $uri): ?string
    {
        return $this->objectContentRepository->findOneByUri($uri)?->content;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Uri $uri, string $type = CoreObject::class): CoreObject
    {
        return $this->find($uri, $type) ?? throw new RuntimeException('Object not found');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchContent(Uri $uri): string
    {
        return $this->findContent($uri) ?? throw new RuntimeException('Object not found');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Uri $uri): void
    {
        $objectContent = $this->objectContentRepository->findOneByUri($uri);
        if (null !== $objectContent) {
            $this->objectContentRepository->delete($objectContent);
        }
    }
}
