<?php

namespace ADMIN\AdminBundle\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class SillyDateTimeType extends DateTimeType {

    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $val = \DateTime::createFromFormat('d/m/Y', $value);
        if (!$val instanceof \DateTime) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $val;
    }

}
