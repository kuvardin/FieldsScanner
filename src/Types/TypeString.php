<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeString
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeString extends Type
{
    /**
     * @var bool
     */
    static protected bool $save_examples = true;

    /**
     * @var int|null
     */
    public ?int $min_length = null;

    /**
     * @var int|null
     */
    public ?int $max_length = null;

    /**
     * @var int
     */
    public int $empty_number = 0;

    /**
     * String constructor.
     *
     * @param FieldsScanner $fields_scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $fields_scanner, $value)
    {
        parent::__construct($fields_scanner, $value);
        $length = mb_strlen($value);
        if ($length === 0) {
            $this->empty_number++;
        } else {
            $this->min_length = $length;
            $this->max_length = $length;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        return is_string($value);
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_STRING;
    }

    /**
     * @return bool
     */
    public function mayBeEmpty(): bool
    {
        return $this->empty_number !== 0;
    }

    /**
     * @return bool
     */
    public function alwaysEmpty(): bool
    {
        return $this->empty_number === $this->number;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        $length = mb_strlen($value);

        if ($length === 0) {
            $this->empty_number++;
        } else {
            if ($this->min_length === null || $this->min_length > $length) {
                $this->min_length = $length;
            }

            if ($this->max_length === null || $this->max_length < $length) {
                $this->max_length = $length;
            }
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
        $result = str_repeat($tab_symbol, $tabs);
        if ($this->alwaysEmpty()) {
            $result .= "Always empty\n";
        } elseif ($this->mayBeEmpty()) {
            $result .= "Length: {$this->min_length}-{$this->max_length} (may be empty)\n";
        } else {
            $result .= "Length: {$this->min_length}-{$this->max_length} (cannot be empty)\n";
        }

        if ($this->examples !== []) {
            $result .= str_repeat($tab_symbol, $tabs) . "Examples:\n";
            foreach ($this->examples as $example => $example_number) {
                $result .= str_repeat($tab_symbol, $tabs + 1) . "$example - $example_number\n";
            }
        }

        return $result;
    }
}
