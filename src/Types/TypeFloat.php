<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeFloat
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeFloat extends Type
{
    /**
     * @var bool
     */
    public static bool $save_examples = true;

    /**
     * @var float
     */
    public float $min_value;

    /**
     * @var float
     */
    public float $max_value;

    /**
     * TypeFloat constructor.
     *
     * @param FieldsScanner $scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        parent::__construct($scanner, $value);
        $this->min_value = $value;
        $this->max_value = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        return is_float($value);
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_FLOAT;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        if ($this->max_value < $value) {
            $this->max_value = $value;
        }

        if ($this->min_value > $value) {
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
