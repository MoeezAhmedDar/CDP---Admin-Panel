<?php

namespace App\Interfaces\User;

interface LpRepositoryInterface
{
    public function store($request, $lp);
    public function index($request, $status = null);
    public function show($lp);
    public function offers($lp);
    public function edit($lp);
    public function update_offer($request, $offer);
    public function update($request, $lp);
    public function variable_fee_store($request, $lp);
    public function upload_individual_csv($request);
    public function add($request);
}
