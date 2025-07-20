<?php

namespace Branzia\Settings\Contracts;

abstract class FormSchema
{
    public static string $tab;
    public static string $group = 'General';
    public static int $sort = 0;

    protected static array $extendedSchema = [];

    public static function extend(callable $callback): void
    {
        static::$extendedSchema[] = $callback;
    }

    public static function getFormSchema(): array
    {
        $baseSchema = static::baseSchema();

        foreach (static::$extendedSchema as $extender) {
            $extension = $extender();

            if (is_array($extension)) {
                $baseSchema = array_merge($baseSchema, $extension);
            }
        }

        return $baseSchema;
    }

    // Each subclass must implement its own base schema
    abstract protected static function baseSchema(): array;


}
