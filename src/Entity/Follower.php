<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'follower')]
class Follower extends AbstractFollow
{
}
