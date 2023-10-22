<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\LocalObject;

class LocalObjectEntityResolver implements LocalObjectEntityResolverInterface
{
    /**
     * @param iterable<LocalObjectEntityProviderInterface> $providers
     */
    public function __construct(private readonly iterable $providers)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasObject(Uri $uri): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->has($uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $uri): ?LocalObject
    {
        foreach ($this->providers as $provider) {
            $object = $provider->provide($uri);
            if (null !== $object) {
                return $object;
            }
        }

        return null;
    }
}
