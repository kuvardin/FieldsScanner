<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\Field;
use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeDissocArray
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeDissocArray extends Type
{
    /**
     * @var bool
     */
    static protected bool $save_examples = false;

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
     * @var Field|null
     */
    public ?Field $child = null;

    /**
     * TypeArray constructor.
     *
     * @param FieldsScanner $scanner
     * @param $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        parent::__construct($scanner, $value);
        $length = count($value);
        if ($length === 0) {
            $this->empty_number++;
        } else {
            foreach ($value as $item) {
                if ($this->child === null) {
                    $this->child = new Field($scanner, $item);
                } else {
                    $this->child->addValue($item);
                }
            }

            $this->max_length = $length;
            $this->min_length = $length;
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
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_DISSOC_ARRAY;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        $length = count($value);
        if ($length === 0) {
            $this->empty_number++;
        } else {
            foreach ($value as $item) {
                if ($this->child === null) {
                    $this->child = new Field($this->scanner, $item);
                } else {
                    $this->child->addValue($item);
                }
            }

            if ($this->max_length === null || $this->max_length < $length) {
                $this->max_length = $length;
            }

            if ($this->min_length === null || $this->min_length > $length) {
                $this->min_length = $length;
            }
        }

        if (self::$save_examples) {
            $this->saveExample($value);
        }

        $this->number++;
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

        if ($this->child !== null) {
            $result .= str_repeat($tab_symbol, $tabs) . "Child\n" .
                $this->child->getInfo($tabs + 1, $tab_symbol);
        }

        return $result;
    }
}
