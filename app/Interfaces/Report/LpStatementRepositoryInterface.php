<?php

namespace App\Interfaces\Report;

interface LpStatementRepositoryInterface
{
    public function storeLpStatement($uid, $lp);
}
