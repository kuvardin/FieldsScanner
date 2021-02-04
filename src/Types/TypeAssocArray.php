<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\Field;
use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeAssocArray
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeAssocArray extends Type
{
    /**
     * @var Field[]
     */
    public array $fields = [];

    /**
     * @var bool
     */
    public static bool $save_examples = false;

    /**
     * TypeAssocArray constructor.
     *
     * @param FieldsScanner $scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        parent::__construct($scanner, $value);
        foreach ($value as $key => $item) {
            $this->fields[$key] = new Field($scanner, $item);
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $key => $item) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_ASSOC_ARRAY;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        foreach ($value as $key => $item) {
            if (array_key_exists($key, $this->fields)) {
                $this->fields[$key]->addValue($item);
            } else {
                $this->fields[$key] = new Field($this->scanner, $item);
                $this->fields[$key]->not_exist_number = $this->number;
            }
        }

        foreach ($this->fields as $key => $field) {
            if (!array_key_exists($key, $value)) {
                $this->fields[$key]->not_exist_number++;
            }
        }

        $this->number++;
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string
    {
        $result = '';
        foreach ($this->fields as $field_name => $field) {
            $result .= str_repeat($tab_symbol, $tabs) . "[$field_name]\n" .
                $field->getInfo($tabs + 1, $tab_symbol);
        }
        return $result;
    }

}
