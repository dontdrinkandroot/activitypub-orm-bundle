<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use Override;

class PlainJsonType extends Type
{
    public const string NAME = 'plain_json';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
            if ($value === false) {
                throw ValueNotConvertible::new($value, self::NAME);
            }
        }

        return $value;
    }
}
