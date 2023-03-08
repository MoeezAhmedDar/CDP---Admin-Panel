<?php

namespace App\Traits;

trait GlobalVariables
{
    public function getCanadaCities()
    {
        return  \App\Models\CanadaCities::select('province_name', 'province_id')
            ->distinct()
            ->get();
    }
}
