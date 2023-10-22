<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Deprecations\Deprecation;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

class UriType extends Type
{
    public const NAME = 'uri';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Uri) {
            throw ConversionException::conversionFailedInvalidType($value, self::NAME, ['null', Uri::class]);
        }

        return $value->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uri
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, self::NAME, ['null', 'string']);
        }

        return Uri::fromString($value);
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        Deprecation::triggerIfCalledFromOutside(
            'doctrine/dbal',
            'https://github.com/doctrine/dbal/pull/5509',
            '%s is deprecated.',
            __METHOD__,
        );

        return true;
    }
}
