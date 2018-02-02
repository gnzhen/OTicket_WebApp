<?php 

namespace App\Traits;

trait NullableFields {

    protected function nullIfEmpty($input)
    {
        return trim($input) == '' ? null : trim($input);
    }

}