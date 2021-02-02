<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner;

use Error;

/**
 * Class Field
 *
 * @package Kuvardin\FieldsScanner
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class Field
{
    /**
     * @var FieldsScanner
     */
    protected FieldsScanner $scanner;

    /**
     * @var Type[]
     */
    public array $types = [];

    /**
     * @var int
     */
    public int $null_number = 0;

    /**
     * @var int
     */
    public int $not_exists_number = 0;

    /**
     * @var int
     */
    public int $exists_number = 0;

    /**
     * Field constructor.
     *
     * @param FieldsScanner $scanner
     * @param mixed $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        $this->scanner = $scanner;
        $this->addValue($value);
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        $this->exists_number++;

        if ($value === null) {
            $this->null_number++;
        } else {
            foreach (FieldsScanner::TYPES as $type_code => $type_class) {
                if ($type_class::checkValue($value)) {
                    if (array_key_exists($type_code, $this->types)) {
                        $this->types[$type_code]->addValue($value);
                    } else {
                        $this->types[$type_code] = new $type_class($this->scanner, $value);
                    }
                    return;
                }
            }

            $type = gettype($value);
            throw new Error("Unknown field typed $type with value: " . print_r($value, true));
        }
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->null_number !== 0;
    }

    /**
     * @return bool
     */
    public function isAlwaysNull(): bool
    {
        return $this->null_number === $this->exists_number;
    }

    /**
     * @return bool
     */
    public function mayNotExists(): bool
    {
        return $this->not_exists_number !== 0;
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string
    {
        $types = [];
        if ($this->types !== []) {
            foreach ($this->types as $type) {
                $types[] = "{$type::getCode()} ({$type->number})";
            }
        }

        if ($this->isNullable()) {
            $types[] = "null ({$this->null_number})";
        }

        if ($this->mayNotExists()) {
            $types[] = "not_exists ({$this->not_exists_number})";
        }

        $result = str_repeat($tab_symbol, $tabs) . 'Types: ' . implode(', ', $types) . "\n";
        if ($this->types !== []) {
            foreach ($this->types as $type) {
                $result .= str_repeat($tab_symbol, $tabs) . "Type {$type::getCode()}:\n" .
                    $type->getInfo($tabs + 1, $tab_symbol);
            }
        }

        return $result;
    }

}
