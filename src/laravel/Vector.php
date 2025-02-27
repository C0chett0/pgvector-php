<?php

namespace Pgvector\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Vector extends \Pgvector\Vector implements Castable
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class ($arguments) implements CastsAttributes {
            public function __construct(array $arguments)
            {
                // no need for dimensions
            }

            public function get(Model $model, string $key, mixed $value, array $attributes): ?Vector
            {
                if (is_null($value)) {
                    return null;
                }

                // return Vector instead of array
                // since Vector needed for orderByRaw and selectRaw
                return new Vector($value);
            }

            public function set(Model $model, string $key, mixed $value, array $attributes): ?string
            {
                if (is_null($value)) {
                    return null;
                }

                if (!($value instanceof Vector)) {
                    $value = new Vector($value);
                }

                return (string) $value;
            }
        };
    }
}
