<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;

#[ORM\Entity]
#[ORM\Table(name: 'object_content')]
class ObjectContent
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(targetEntity: CoreObject::class)]
        public CoreObject $object,

        #[ORM\Column(type: PlainJsonType::NAME)]
        public string $content
    ) {
    }
}
