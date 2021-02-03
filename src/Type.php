<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner;

use Error;

/**
 * Class Type
 *
 * @package Kuvardin\FieldsScanner
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
abstract class Type
{
    /**
     * @var FieldsScanner
     */
    protected FieldsScanner $scanner;

    /**
     * @var array
     */
    public array $examples = [];

    /**
     * @var int
     */
    private int $examples_number = 0;

    /**
     * @var int
     */
    public int $number = 0;

    /**
     * @var bool
     */
    static protected bool $save_examples;

    /**
     * Type constructor.
     *
     * @param FieldsScanner $scanner
     * @param $value
     */
    public function __construct(FieldsScanner $scanner, $value)
    {
        $this->scanner = $scanner;

        if (!static::checkValue($value)) {
            throw new Error("Incorrect field value: $value for type " . static::class);
        }

        if (static::$save_examples) {
            $this->saveExample($value);
        }

        $this->number++;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    abstract public static function checkValue($value): bool;

    /**
     * @return string
     */
    abstract public static function getCode(): string;

    /**
     * @param mixed $value
     */
    abstract public function addValue($value): void;

    /**
     * @param mixed $value
     */
    protected function saveExample($value): void
    {
        if (!static::$save_examples) {
            return;
        }

        $key = null;

        if (is_string($value) || is_int($value)) {
            $key = $value;
        } elseif (is_bool($value)) {
            $key = $value ? 'true' : 'false';
        } elseif (is_float($value)) {
            $key = (string)$value;
        }

        $in_limit = $this->scanner->max_examples === null || $this->examples_number++ < $this->scanner->max_examples;
        if ($key === null) {
            if ($in_limit && !in_array($value, $this->examples, true)) {
                $this->examples[] = $value;
            }
        } elseif (array_key_exists($key, $this->examples)) {
            $this->examples[$key]++;
        } elseif ($in_limit) {
            $this->examples[$key] = 1;
        }
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    abstract public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string;
}
