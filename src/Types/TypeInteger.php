<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeInteger
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeInteger extends Type
{
    /**
     * @var int
     */
    public int $max_value;

    /**
     * @var int
     */
    public int $min_value;

    /**
     * @var bool
     */
    static protected bool $save_examples = true;

    /**
     * Integer constructor.
     *
     * @param FieldsScanner $fields_scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $fields_scanner, $value)
    {
        parent::__construct($fields_scanner, $value);
        $this->max_value = $value;
        $this->min_value = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        return is_int($value);
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_INTEGER;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        if ($value > $this->max_value) {
            $this->max_value = $value;
        }

        if ($value < $this->min_value) {
            $this->min_value = $value;
        }

        $this->saveExample($value);
        $this->number++;
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string
    {
        $result = str_repeat($tab_symbol, $tabs) . "Values: {$this->min_value} - {$this->max_value}\n";
        if ($this->examples !== []) {
            $result .= str_repeat($tab_symbol, $tabs) . "Examples:\n";
            foreach ($this->examples as $example => $example_number) {
                $result .= str_repeat($tab_symbol, $tabs + 1) . "$example - $example_number\n";
            }
        }
        return $result;
    }
}
