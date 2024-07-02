<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

class UriType extends Type
{
    public const string NAME = 'uri';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Uri) {
            throw InvalidType::new($value, self::NAME, ['null', Uri::class]);
        }

        return $value->__toString();
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uri
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw InvalidType::new($value, self::NAME, ['null', 'string']);
        }

        return Uri::fromString($value);
    }
}
