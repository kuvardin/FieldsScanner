<?php

declare(strict_types=1);

namespace Kuvardin\FieldsScanner\Types;

use Kuvardin\FieldsScanner\FieldsScanner;
use Kuvardin\FieldsScanner\Type;

/**
 * Class TypeBool
 *
 * @package Kuvardin\FieldsScanner\Types
 * @author Maxim Kuvardin <maxim@kuvard.in>
 */
class TypeBool extends Type
{
    public static bool $save_examples = true;

    /**
     * @param mixed $value
     * @return bool
     */
    public static function checkValue($value): bool
    {
        return is_bool($value);
    }

    /**
     * @return string
     */
    public static function getCode(): string
    {
        return FieldsScanner::TYPE_BOOL;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value): void
    {
        $this->saveExample($value);
    }

    /**
     * @param int $tabs
     * @param string $tab_symbol
     * @return string
     */
    public function getInfo(int $tabs = 0, string $tab_symbol = "\t"): string
    {
        $result = '';
        if ($this->examples !== []) {
            $result .= str_repeat($tab_symbol, $tabs) . "Examples:\n";
            foreach ($this->examples as $example => $example_number) {
                $result .= str_repeat($tab_symbol, $tabs + 1) . "$example - $example_number\n";
            }
        }
        return $result;
    }
}
