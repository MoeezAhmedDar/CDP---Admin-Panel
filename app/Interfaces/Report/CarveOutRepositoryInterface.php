<?php

namespace App\Interfaces\Report;

interface CarveOutRepositoryInterface
{
    public function index($retailer);
    public function store($request);
}
