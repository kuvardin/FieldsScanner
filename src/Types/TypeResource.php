<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeResource
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeResource extends Type
{
    public static bool $save_examples = false;

    /**
     * @var int[]
     */
    public array $types = [];

    /**
     * TypeResource constructor.
     *
     * @param FieldsScanner $scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        parent::__construct($scanner, $value);
        $this->types[get_resource_type($value)] = 1;
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_RESOURCE;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        return is_resource($value);
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        $type = get_resource_type($value);
        if (array_key_exists($type, $this->types)) {
            $this->types[$type] = 1;
        } else {
            $this->types[$type]++;
        }
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string
    {
        $result = str_repeat($tab_symbol, $tabs) . "Types:\n";
        foreach ($this->types as $type => $number) {
            $result .= str_repeat($tab_symbol, $tabs + 1) . "$type - $number\n";
        }
        return $result;
    }
}
