<?php

namespace App\Interfaces\User;

interface RetailerRepositoryInterface
{
    public function index($status);
    public function store($request, $retailer);
    public function update($request, $retailer);
    public function update_Requested($request, $retailer);
    public function sendEmail($user);
    public function add($request);
    public function search($status);
    public function address_store($request, $retailer);
    public function getRetailerForm($user);
    public function getCanadaCities();
    public function getRetailer($retailer);
    public function updateRetailer($request, $retailer);
}
