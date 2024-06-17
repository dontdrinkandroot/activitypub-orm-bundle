<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;
use Override;

class LocalObjectEntityResolver implements LocalObjectEntityResolverInterface
{
    /**
     * @param iterable<LocalObjectEntityProviderInterface> $providers
     */
    public function __construct(private readonly iterable $providers)
    {
    }

    #[Override]
    public function resolve(Uri $uri): ?StoredObject
    {
        foreach ($this->providers as $provider) {
            $result = $provider->provideEntity($uri);
            if (false !== $result) {
                return $result;
            }
        }

        return null;
    }
}
