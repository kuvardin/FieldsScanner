<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner;

/**
 * Class FieldsScanner
 *
 * @package Kuvardin\FieldsScanner
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class FieldsScanner
{
    public const TYPE_INTEGER = 'int';
    public const TYPE_STRING = 'string';
    public const TYPE_BOOL = 'bool';
    public const TYPE_FLOAT = 'float';
    public const TYPE_ASSOC_ARRAY = 'assoc_array';
    public const TYPE_DISSOC_ARRAY = 'dissoc_array';
    public const TYPE_RESOURCE = 'resource';

    /**
     * @var string[]|Type[]
     */
    public const TYPES = [
        self::TYPE_INTEGER => Types\TypeInteger::class,
        self::TYPE_STRING => Types\TypeString::class,
        self::TYPE_BOOL => Types\TypeBool::class,
        self::TYPE_FLOAT => Types\TypeFloat::class,
        self::TYPE_ASSOC_ARRAY => Types\TypeAssocArray::class,
        self::TYPE_DISSOC_ARRAY => Types\TypeDissocArray::class,
        self::TYPE_RESOURCE => Types\TypeResource::class,
    ];

    /**
     * @var int|null Maximum of fields values number. Null for infinity
     */
    public ?int $max_examples = 10;

    /**
     * @var Field|null
     */
    public ?Field $result = null;

    /**
     * @param mixed $data
     * @return Field
     */
    public function scan($data): Field
    {
        if ($this->result === null) {
            $this->result = new Field($this, $data);
        } else {
            $this->result->addValue($data);
        }

        return $this->result;
    }
}
