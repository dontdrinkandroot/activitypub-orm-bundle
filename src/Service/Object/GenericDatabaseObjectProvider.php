<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;
use Override;
use RuntimeException;

class GenericDatabaseObjectProvider implements ObjectProviderInterface
{
    /**
     * @param iterable<DatabaseObjectPersisterInterface> $persisters
     */
    public function __construct(
        private readonly ActivityPubClientInterface $client,
        private readonly iterable $persisters
    ) {
    }

    #[Override]
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null
    {
        $activityPubObject = $this->client->request(method: 'GET', uri: $uri, signKey: $signKey);
        if (null === $activityPubObject) {
            return null;
        }

        foreach ($this->persisters as $persister) {
            $result = $persister->persist($activityPubObject);
            if (true === $result) {
                return $activityPubObject;
            }
        }

        throw new RuntimeException('No persister could handle object');
    }
}
