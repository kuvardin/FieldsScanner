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
     * @var int
     */
    public int $positive_number = 0;

    /**
     * @var int
     */
    public int $zero_number = 0;

    /**
     * @var int
     */
    public int $negative_number = 0;

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

        if ($value > 0) {
            $this->positive_number++;
        } elseif ($value < 0) {
            $this->negative_number++;
        } else {
            $this->zero_number++;
        }
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

        if ($value > 0) {
            $this->positive_number++;
        } elseif ($value < 0) {
            $this->negative_number++;
        } else {
            $this->zero_number++;
        }

        $this->saveExample($value);
        $this->number++;
    }

    /**
     * @return bool
     */
    public function mayBePositive(): bool
    {
        return $this->positive_number !== 0;
    }

    /**
     * @return bool
     */
    public function isAlwaysPositive(): bool
    {
        return $this->positive_number === $this->number;
    }

    /**
     * @return bool
     */
    public function mayBeZero(): bool
    {
        return $this->zero_number !== 0;
    }

    /**
     * @return bool
     */
    public function isAlwaysZero(): bool
    {
        return $this->zero_number === $this->number;
    }

    /**
     * @return bool
     */
    public function mayBeNegative(): bool
    {
        return $this->negative_number !== 0;
    }

    /**
     * @return bool
     */
    public function isAlwaysNegative(): bool
    {
        return $this->negative_number === $this->number;
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
