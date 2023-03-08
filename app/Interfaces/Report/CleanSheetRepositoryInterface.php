<?php

namespace App\Interfaces\Report;

interface CleanSheetRepositoryInterface
{
    public function dirty_rows($id, $retailer_id, $request);

    public function update($request, $id);
}
