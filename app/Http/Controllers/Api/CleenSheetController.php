<?php

namespace App\Http\Controllers\Api;

use App\Exports\CleanSheetsExport;
use App\Exports\LpStatementExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ManitobaReportUpload;
use Exception;
use App\Exports\RetailerStatementExport;
use App\Http\Controllers\Controller;
use App\Imports\CovaDiagnosticReportImport;
use App\Imports\CovaSalesSummaryReportImport;
use App\Imports\GreenlineReportImport;
use App\Imports\TechPosReportImport;
use App\Models\RetailerReportSubmission;
use Carbon\Carbon;
use App\Models\Retailer;
use Illuminate\Support\Facades\Session;
use App\Models\Activity;
use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\CleanSheet;
use App\Models\CovaDiagnosticReport;
use App\Models\Lp;
use App\Models\LpFixedFeeStructure;
use App\Models\LpStatement;
use App\Models\LpVariableFeeStructure;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\RetailerAddress;
use App\Models\RetailerStatement;
use App\Models\SaskatchewanProvincialCatalog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\PennyLaneReportImport;
use App\Imports\DuctieDiagnosticReportImport;
use App\Imports\DuctieSalesSummaryReportImport;
use App\Imports\ProfitTechReportImport;
use App\Imports\GobatellDiagnosticReportImport;
use App\Imports\GobatellSalesSummaryReportImport;
use App\Jobs\SendNotification;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CleenSheetController extends BaseController
{
    public function clean_report(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'retailer_id' => 'required',
            'api_key' => 'required',
            'date' => 'required'
        ]);
        $retailer_id = $request->retailer_id;
        if ($request->api_key == 'AIzaSyCiSwHoCr13O6AVKbUSFQ5gjzy7IUTRCDF') {
            try {
                DB::beginTransaction();

                $retailerReportSubmission = RetailerReportSubmission::where('location', $request->location)->whereMonth('date', Carbon::parse($request->date)->format('m'))->whereYear('date', Carbon::parse($request->date)->format('Y'))->first();
                if ($retailerReportSubmission) {
                    $checkCleanSheet = CleanSheet::where('retailerReportSubmission_id', $retailerReportSubmission->id)->first();

                    if (!$checkCleanSheet) {
                        $variable = date('d-m-y h:i:s');
                        Session::put('variable', $variable);
                        $this->checkPos($variable, $retailerReportSubmission, $retailer_id);
                        $retailer = Retailer::where('id', $retailer_id)->first();
                        DB::commit();
                    } else {
                        $retailer = Retailer::where('id', $retailer_id)->first();

                        Session::put('variable', $checkCleanSheet->variable);
                        DB::commit();
                    }
                    $path = preg_replace('/[^A-Za-z0-9\-]/', '', $retailer->user->name) . preg_replace('/[^A-Za-z0-9\-]/', '', $retailerReportSubmission->location) . Carbon::parse($retailerReportSubmission->date)->format('M') . '.csv';

                    $cleanSheetss = CleanSheet::where('variable', Session::get('variable'))->select("retailer_name", "location", "province", "sku", "brand", "product_name", "category", "sold", "purchased", "average_price", "average_cost", "flag", "comments")->get();

                    if ($cleanSheetss->first()->retailer_name != $retailer->user->name) {
                        $errors = new Exception();
                        return $this->responseApi('No Report Found against this retailer', false, $errors, 404);
                    }
                    $file = fopen(public_path('/clean_sheet/' . $path), "w");
                    $i = 1;
                    foreach ($cleanSheetss as $key => $cleanSheet) {
                        if ($i == 1) {
                            fputcsv($file, array("Retailer Name", "Location", "Province", 'SKU', "Brand", "product Name", "Category", "Sold", "Purchased", "Average Price", "Average Cost", "Flag", "Comments"));
                        } else {
                            fputcsv($file, $cleanSheet->toArray());
                        }
                        $i++;
                    }
                    fclose($file);

                    $retailer_info = new Retailer();
                    $retailer_info->id = $retailer->id;
                    $retailer_info->name = $retailer->user->name;
                    $retailer_info->province = $retailerReportSubmission->province;
                    $retailer_info->pos_name = $retailerReportSubmission->pos;

                    $name1 = '';
                    $name2 = '';
                    if ($retailerReportSubmission->pos == 'cova' || $retailerReportSubmission->pos == 'gobatell') {
                        $name1 = 'Diagnostic Report';
                        $name2 = 'Sales Summary Report';
                    } elseif ($retailerReportSubmission->pos == 'greenline' || $retailerReportSubmission->pos == 'TechPOS' || $retailerReportSubmission->pos == 'PennyLane') {
                        $name1 = 'Inventory Log Summary';
                        $name2 = 'Inventory Log Summary';
                    } elseif ($retailerReportSubmission->pos == 'ductie') {
                        $name1 = 'Inventory Receipt Detail';
                        $name2 = 'Roll Forward';
                    }

                    $path = Storage::disk('s3')->put('clean_sheet', new File(public_path('/clean_sheet/' . $path)));
                    $path = Storage::disk('s3')->url($path);

                    $data = new CleanSheet();
                    $data->clean_sheet_url = $path;

                    $data->source_files =
                        [
                            [
                                'name' => $name1,
                                'URL' => URL::to('/') . '/' . 'reports/' . rawurlencode(basename($retailerReportSubmission->file1))

                            ],
                            [
                                'name' => $name2,
                                'URL' => URL::to('/') . '/' . 'reports/' . rawurlencode(
                                    basename($retailerReportSubmission->file2)
                                )
                            ]
                        ];
                    $success = 'true';
                    $meta = new BaseController();
                    $meta->static_response = 'true';

                    return $this->responseSuccessApi($retailer_info, $data, $meta, $success, 'Clean Sheet Fetched Successfully', 200);
                } else {
                    $errors = new Exception();
                    return $this->responseApi('Invalid Location or Date', false, $errors, 404);
                }
            } catch (Exception $e) {
                DB::rollback();
                Session::forget('variable');
                Session::forget('variable');
                return $this->responseApi('Error', false, $e->getMessage(), 417);
            }
        } else {
            return $this->responseUnauthorizedApi('You must use valid API key to authenticate this request', 401);
        }
    }

    private function greenline_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'greenlineReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->greenlineReports as $greenlineReport) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $ocsProvincialCatalog = new OcsProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'ocs_variant_number', $Barcode = 'gtin', $Product_name = 'product_name', $greenlineReport->sku, $greenlineReport->barcode, $greenlineReport->name, $comments);
                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($greenlineReport->average_cost == '$0.00') $comments = $comments . ', Average cost is not correct';

                    $this->greenlineVariableIntialize($greenlineReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = $this->averageCostPos($greenlineReport, $provincialCatalog->unit_price, $total_cost = null, $net_qty = null);

                    $sku = $provincialCatalog->ocs_variant_number ? $provincialCatalog->ocs_variant_number : $greenlineReport->sku;
                    $product_name = $greenlineReport->name ? $greenlineReport->name :  $provincialCatalog->product_name;
                    $category = $greenlineReport->compliance_category ? $greenlineReport->compliance_category : $provincialCatalog->category;
                    $brand = $greenlineReport->brand ? $greenlineReport->brand : $provincialCatalog->brand;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $greenlineReport->barcode;
                    $average_price = $greenlineReport->average_price;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $ocsProvincialCatalog = new MbllProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'skumbll_item_number', $Barcode = 'upcgtin', $Product_name = 'description1', $greenlineReport->sku, $greenlineReport->barcode, $greenlineReport->name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($greenlineReport->average_cost == '$0.00') $comments = $comments . ', Average cost not correct';

                    $this->greenlineVariableIntialize($greenlineReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = $this->averageCostPos($greenlineReport, $provincialCatalog->unit_price, $total_cost = null, $net_qty = null);

                    $sku = $provincialCatalog->skumbll_item_number ? $provincialCatalog->skumbll_item_number : $greenlineReport->sku;
                    $product_name = $greenlineReport->name ? $greenlineReport->name : $provincialCatalog->description1;
                    $category = $greenlineReport->compliance_category ? $greenlineReport->compliance_category : $provincialCatalog->type;
                    $brand = $greenlineReport->brand ? $greenlineReport->brand : $provincialCatalog->brand;
                    $barcode = $provincialCatalog->upcgtin ? $provincialCatalog->upcgtin : $greenlineReport->barcode;
                    $average_price = $greenlineReport->average_price;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $ocsProvincialCatalog = new BritishColumbiaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'su_code', $Product_name = 'product_name', $greenlineReport->sku, $greenlineReport->barcode, $greenlineReport->name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($greenlineReport->average_cost == '$0.00') $comments = $comments . ', Average cost not correct';

                    $this->greenlineVariableIntialize($greenlineReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $greenlineReport->sku;
                    $product_name = $greenlineReport->name ? $greenlineReport->name : $provincialCatalog->product_name;
                    $category = $greenlineReport->compliance_category ? $greenlineReport->compliance_category : $provincialCatalog->class;
                    $brand = $greenlineReport->brand ? $greenlineReport->brand : $provincialCatalog->brand_name;
                    $barcode = $provincialCatalog->su_code ? $provincialCatalog->su_code : $greenlineReport->barcode;
                    $average_price = $greenlineReport->average_price;
                    $average_cost = $greenlineReport->average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $ocsProvincialCatalog = new AlbertaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'aglc_sku', $Barcode = 'gtin', $Product_name = 'product_name', $greenlineReport->sku, $greenlineReport->barcode, $greenlineReport->name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($greenlineReport->average_cost == '$0.00') $comments = $comments . ', Average cost is not correct';

                    $this->greenlineVariableIntialize($greenlineReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = $this->averageCostPos($greenlineReport, $provincialCatalog->sell_price_per_unit, $total_cost = null, $net_qty = null);

                    $average_price = '';
                    if (trim($greenlineReport->average_price) != '$0.00') {
                        $average_price = $greenlineReport->average_price;
                    } else {
                        $average_price = $provincialCatalog->msrp ? $provincialCatalog->msrp : '$0.00';
                    }
                    $sku = $provincialCatalog->aglc_sku ? $provincialCatalog->aglc_sku : $greenlineReport->sku;
                    $product_name = $greenlineReport->name ? $greenlineReport->name : $provincialCatalog->product_name;
                    $category = $greenlineReport->compliance_category ? $greenlineReport->compliance_category : $provincialCatalog->format;
                    $brand = $greenlineReport->brand ? $greenlineReport->brand : $provincialCatalog->brand_name;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $greenlineReport->barcode;
                    $average_price = $average_price;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $ocsProvincialCatalog = new SaskatchewanProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'gtin', $Product_name = 'product_name', $greenlineReport->sku, $greenlineReport->barcode, $greenlineReport->name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($greenlineReport->average_cost == '$0.00') $comments = $comments . ', Average cost not correct';

                    $this->greenlineVariableIntialize($greenlineReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = $this->averageCostPos($greenlineReport, $provincialCatalog->per_unit_cost, $total_cost = null, $net_qty = null);

                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $greenlineReport->sku;
                    $product_name = $greenlineReport->name ? $greenlineReport->name : $provincialCatalog->product_name;
                    $category = $greenlineReport->compliance_category ? $greenlineReport->compliance_category : $provincialCatalog->type;
                    $brand = $greenlineReport->brand ? $greenlineReport->brand : $provincialCatalog->brand;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $greenlineReport->barcode;
                    $average_price = $greenlineReport->average_price;
                    $average_cost = $average_cost;
                }
            }

            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode = $barcode;
            $cleanSheet->sold = $greenlineReport->sold;
            $cleanSheet->purchased = $greenlineReport->purchased;
            $cleanSheet->average_price = $average_price;
            $cleanSheet->average_cost = $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->flag = $flag;
            $cleanSheet->comments = $comments;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function cova_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'covaDaignosticReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->covaDaignosticReports as $covaDaignosticReport) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $ocsProvincialCatalog = new OcsProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'ocs_variant_number', $Barcode = 'gtin', $Product_name = 'product_name', $covaDaignosticReport->ocs_sku, $covaDaignosticReport->ontario_barcode_upc, $covaDaignosticReport->product_name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = $this->averageCostPos($greenlineReport = null, $provincialCatalog = null, (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$'), $covaDaignosticReport->CovaSalesSummaryReport->net_qty);
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $this->covaVariableIntialize($covaDaignosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$') / (int)$covaDaignosticReport->CovaSalesSummaryReport->net_qty;
                        } else {
                            $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '0.00';
                        }
                    } else {
                        $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '0.00';
                    }

                    $sku = $provincialCatalog->ocs_variant_number ? $provincialCatalog->ocs_variant_number : $covaDaignosticReport->ocs_sku;
                    $product_name = $covaDaignosticReport->product_name ? $covaDaignosticReport->product_name :  $provincialCatalog->product_name;
                    $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : $provincialCatalog->category;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $covaDaignosticReport->ontario_barcode_upc;
                    $brand = $provincialCatalog ? $provincialCatalog->brand : 'Null';
                    $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : '$0.00';
                    $average_cost = '$' . $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $ocsProvincialCatalog = new MbllProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'skumbll_item_number', $Barcode = 'upcgtin', $Product_name = 'description1', $covaDaignosticReport->ocs_sku, $covaDaignosticReport->manitoba_barcode_upc, $covaDaignosticReport->product_name, $comments);
                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = $this->averageCostPos($greenlineReport = null, $provincialCatalog = null, (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$'), $covaDaignosticReport->CovaSalesSummaryReport->net_qty);
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $this->covaVariableIntialize($covaDaignosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty != '0') {
                            $average_cost = (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$') / (int)$covaDaignosticReport->CovaSalesSummaryReport->net_qty;
                        } else {
                            $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '0.00';
                        }
                    } else {
                        $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '0.00';
                    }
                    $sku = $provincialCatalog->skumbll_item_number ? $provincialCatalog->skumbll_item_number : $covaDaignosticReport->ocs_sku;
                    $product_name = $covaDaignosticReport->product_name ? $covaDaignosticReport->product_name : $provincialCatalog->description1;
                    $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : $provincialCatalog->type;
                    $brand = $provincialCatalog ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->upcgtin ? $provincialCatalog->upcgtin : $covaDaignosticReport->manitoba_barcode_upc;
                    $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : '$0.00';
                    $average_cost = '$' . $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $ocsProvincialCatalog = new BritishColumbiaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'su_code', $Product_name = 'product_name', $covaDaignosticReport->new_brunswick_sku, $covaDaignosticReport->manitoba_barcode_upc, $covaDaignosticReport->product_name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = $this->averageCostPos($greenlineReport = null, $provincialCatalog = null, (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$'), $covaDaignosticReport->CovaSalesSummaryReport->net_qty);
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $this->covaVariableIntialize($covaDaignosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty) {
                            $average_cost = (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$') / (int)$covaDaignosticReport->CovaSalesSummaryReport->net_qty;
                        } else {
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $covaDaignosticReport->new_brunswick_sku;
                    $product_name = $covaDaignosticReport->product_name ? $covaDaignosticReport->product_name : $provincialCatalog->product_name;
                    $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : $provincialCatalog->class;
                    $brand = $provincialCatalog ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->su_code ? $provincialCatalog->su_code : $covaDaignosticReport->manitoba_barcode_upc;
                    $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : '$0.00';
                    $average_cost = '$' . $average_cost ? $average_cost : '$0.00';
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {

                $ocsProvincialCatalog = new AlbertaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'aglc_sku', $Barcode = 'gtin', $Product_name = 'product_name', $covaDaignosticReport->aglc_sku, $covaDaignosticReport->manitoba_barcode_upc, $covaDaignosticReport->product_name, $comments);
                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = $this->averageCostPos($greenlineReport = null, $provincialCatalog = null, (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$'), $covaDaignosticReport->CovaSalesSummaryReport->net_qty);
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $this->covaVariableIntialize($covaDaignosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty) {
                            $average_cost = (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$') / (int)$covaDaignosticReport->CovaSalesSummaryReport->net_qty;
                        } else {
                            $average_cost = $provincialCatalog->sell_price_per_unit ? $provincialCatalog->sell_price_per_unit : '0.00';
                        }
                    } else {
                        $average_cost = $provincialCatalog->sell_price_per_unit ? $provincialCatalog->sell_price_per_unit : '0.00';
                    }

                    $sku = $provincialCatalog->aglc_sku ? $provincialCatalog->aglc_sku : $covaDaignosticReport->aglc_sku;
                    $product_name = $covaDaignosticReport->product_name ? $covaDaignosticReport->product_name : $provincialCatalog->product_name;
                    $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : $provincialCatalog->format;
                    $brand = $provincialCatalog ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $covaDaignosticReport->manitoba_barcode_upc;
                    $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : $provincialCatalog->msrp;
                    $average_cost = '$' . $average_cost ? $average_cost : $provincialCatalog->sell_price_per_unit;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $ocsProvincialCatalog = new SaskatchewanProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'gtin', $Product_name = 'product_name', $covaDaignosticReport->new_brunswick_sku, $covaDaignosticReport->saskatchewan_barcode_upc, $covaDaignosticReport->product_name, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty  != '0') {
                            $average_cost = $this->averageCostPos($greenlineReport = null, $provincialCatalog = null, (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$'), $covaDaignosticReport->CovaSalesSummaryReport->net_qty);
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                    } else {
                        $comments = $comments . ', Average Cost not found';
                        $average_cost = '0.00';
                    }
                    $this->covaVariableIntialize($covaDaignosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($covaDaignosticReport->CovaSalesSummaryReport) {
                        if ($covaDaignosticReport->CovaSalesSummaryReport->total_Cost && $covaDaignosticReport->CovaSalesSummaryReport->net_qty) {
                            $average_cost = (int)trim($covaDaignosticReport->CovaSalesSummaryReport->total_Cost, '$') / (int)$covaDaignosticReport->CovaSalesSummaryReport->net_qty;
                        } else {
                            $average_cost = '$' . ($provincialCatalog->per_unit_cost ? $provincialCatalog->per_unit_cost : '0.00');
                        }
                    } else {
                        $average_cost = '$' . ($provincialCatalog->per_unit_cost ? $provincialCatalog->per_unit_cost : '0.00');
                    }

                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $covaDaignosticReport->new_brunswick_sku;
                    $product_name = $covaDaignosticReport->product_name ? $covaDaignosticReport->product_name : $provincialCatalog->product_name;
                    $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : $provincialCatalog->type;
                    $brand = $provincialCatalog ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $covaDaignosticReport->saskatchewan_barcode_upc;
                    $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : '$0.00';
                    $average_cost = '$' . $average_cost ? $average_cost : $provincialCatalog->per_unit_cost;
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode = $barcode;
            $cleanSheet->sold = $covaDaignosticReport->quantity_sold_units;
            $cleanSheet->purchased = $covaDaignosticReport->quantity_purchased_units;
            $cleanSheet->average_price = $average_price;
            $cleanSheet->average_cost = $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->flag = $flag;
            $cleanSheet->comments = $comments;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function pennylane_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'pennylaneReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->pennylaneReports as $pennylaneReports) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $ocsProvincialCatalog = new OcsProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'ocs_variant_number', $Barcode = 'gtin', $Product_name = 'product_name', $pennylaneReports->product_sku, '', $pennylaneReports->description, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $comments = $comments . ', Average Cost not Found.';
                    }

                    if ($pennylaneReports->quantity_purchased_units) {
                        $comments = $comments . ', Quantity Purchased is Null.';
                    }

                    $this->pennylaneVariableIntialize($pennylaneReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $average_cost = $provincialCatalog->unit_price ?? '0';
                    }

                    $sku = $pennylaneReports->product_sku ? $pennylaneReports->product_sku : $provincialCatalog->ocs_variant_number;
                    $product_name = $pennylaneReports->description ? $pennylaneReports->description : $provincialCatalog->product_name;
                    $category = $pennylaneReports->category ? $pennylaneReports->category : $provincialCatalog->category;
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = '0';
                    $average_cost = $average_cost ? $average_cost : '0';
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $ocsProvincialCatalog = new MbllProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'skumbll_item_number', $Barcode = 'upcgtin', $Product_name = 'description1', $pennylaneReports->product_sku, '', $pennylaneReports->description, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    $average_cost = '';
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $comments = $comments . ', Average Cost not Found.';
                    }

                    if ($pennylaneReports->quantity_purchased_units) {
                        $comments = $comments . ', Quantity Purchased is Null.';
                    }
                    $this->pennylaneVariableIntialize($pennylaneReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = '';
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)$pennylaneReports->opening_inventory_units;
                    } else {
                        $average_cost = $provincialCatalog->unit_price ?? '0';
                    }

                    $sku = $pennylaneReports->product_sku ? $pennylaneReports->product_sku : $provincialCatalog->skumbll_item_number;
                    $product_name = $pennylaneReports->description ? $pennylaneReports->description : $provincialCatalog->description1;
                    $category = $pennylaneReports->category ? $pennylaneReports->category : $provincialCatalog->type;
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->upcgtin;
                    $average_price = '0';
                    $average_cost = $average_cost ? $average_cost : '0';
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $ocsProvincialCatalog = new BritishColumbiaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'su_code', $Product_name = 'product_name', $pennylaneReports->product_sku, '', $pennylaneReports->description, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $comments = $comments . ', Average Cost not Found.';
                    }

                    if ($pennylaneReports->quantity_purchased_units) {
                        $comments = $comments . ', Quantity Purchased is Null.';
                    }
                    $this->pennylaneVariableIntialize($pennylaneReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $average_cost = '';
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_units != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $average_cost =  '0';
                    }

                    $sku = $pennylaneReports->product_sku ? $pennylaneReports->product_sku : $provincialCatalog->sku;
                    $product_name = $pennylaneReports->description ? $pennylaneReports->description : $provincialCatalog->product_name;
                    $category = $pennylaneReports->category ? $pennylaneReports->category : $provincialCatalog->class;
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->su_code;
                    $average_price = '0';
                    $average_cost = $average_cost ? $average_cost : '0';
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $ocsProvincialCatalog = new AlbertaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'aglc_sku', $Barcode = 'gtin', $Product_name = 'product_name', $pennylaneReports->product_sku, '', $pennylaneReports->description, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    $average_cost = '';
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $comments = $comments . ', Average Cost not Found.';
                    }

                    if ($pennylaneReports->quantity_purchased_units) {
                        $comments = $comments . ', Quantity Purchased is Null.';
                    }
                    $this->pennylaneVariableIntialize($pennylaneReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $average_cost = $provincialCatalog->sell_price_per_unit ?? '0';
                    }

                    $sku = $pennylaneReports->product_sku ? $pennylaneReports->product_sku : $provincialCatalog->aglc_sku;
                    $product_name = $pennylaneReports->description ? $pennylaneReports->description : $provincialCatalog->product_name;
                    $category = $pennylaneReports->category ? $pennylaneReports->category : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = $provincialCatalog->msrp;
                    $average_cost = $average_cost ? $average_cost : '0';
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $ocsProvincialCatalog = new SaskatchewanProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'gtin', $Product_name = 'product_name', $pennylaneReports->product_sku, '', $pennylaneReports->description, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the Provincial Catalog';

                    $average_cost = '';
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_value != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $comments = $comments . ', Average Cost not Found.';
                    }

                    if ($pennylaneReports->quantity_purchased_units) {
                        $comments = $comments . ', Quantity Purchased is Null.';
                    }
                    $this->pennylaneVariableIntialize($pennylaneReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if (trim($pennylaneReports->opening_inventory_units) != '0' && $pennylaneReports->opening_inventory_units != null) {
                        $average_cost = (int)(trim($pennylaneReports->opening_inventory_value, '$')) / (int)(trim($pennylaneReports->opening_inventory_units));
                    } else {
                        $average_cost = $provincialCatalog->per_unit_cost ? $provincialCatalog->per_unit_cost : '0';
                    }

                    $sku = $pennylaneReports->product_sku ? $pennylaneReports->product_sku : $provincialCatalog->sku;
                    $product_name = $pennylaneReports->description ? $pennylaneReports->description : $provincialCatalog->product_name;
                    $category = $pennylaneReports->category ? $pennylaneReports->category : $provincialCatalog->type;
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = '0';
                    $average_cost = $average_cost ? $average_cost : '0';
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode = $barcode;
            $cleanSheet->sold = $pennylaneReports->quantity_sold_units;
            $cleanSheet->purchased = $pennylaneReports->quantity_purchased_units;
            $cleanSheet->average_price = $average_price;
            $cleanSheet->average_cost = $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->flag = $flag;
            $cleanSheet->comments = $comments;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function techpos_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'techposReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->techposReports as $techposReport) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $provincialCatalog = OcsProvincialCatalog::where('ocs_variant_number', $techposReport->sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku not found in the provincial catalog';
                    $comments = $comments . ', Barcode and Product name are Null';

                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }

                    $this->techposVariableIntialize($techposReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $average_cost = $provincialCatalog->unit_price ?? '0.00';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }

                    $sku = $techposReport->sku ? $techposReport->sku : $provincialCatalog->ocs_variant_number;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->category ? $provincialCatalog->category : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = $techposReport->closinginventoryvalue / $value ?? '$0.00';
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $provincialCatalog = MbllProvincialCatalog::where('skumbll_item_number', $techposReport->sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku not found in the provincial catalog';
                    $comments = $comments . ', Barcode and Product name are Null';

                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $this->techposVariableIntialize($techposReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    $average_cost = '';
                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $average_cost = $provincialCatalog->unit_price ?? '0.00';
                    }
                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $sku = $techposReport->sku ? $techposReport->sku : $provincialCatalog->skumbll_item_number;
                    $product_name = $provincialCatalog->description1 ? $provincialCatalog->description1 : 'Null';
                    $category = $provincialCatalog->type ? $provincialCatalog->type : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = $techposReport->closinginventoryvalue / $value ?? '$0.00';
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $provincialCatalog = BritishColumbiaProvincialCatalog::where('sku', $techposReport->sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku not found in the provincial catalog';
                    $comments = $comments . ', Barcode and Product name are Null';
                    $average_cost = '';

                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }

                    $this->techposVariableIntialize($techposReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    }
                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $sku = $techposReport->sku ? $techposReport->sku : $provincialCatalog->sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->class ? $provincialCatalog->class : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->su_code;
                    $average_price = $techposReport->closinginventoryvalue / $value ?? '$0.00';
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $provincialCatalog = AlbertaProvincialCatalog::where('aglc_sku', $techposReport->sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku not found in the provincial catalog';
                    $comments = $comments . ', Barcode and Product name are Null';
                    $average_cost = '';

                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }

                    $this->techposVariableIntialize($techposReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $average_cost = $provincialCatalog->sell_price_per_unit ?? '0.00';
                    }
                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $sku = $techposReport->sku ? $techposReport->sku : $provincialCatalog->aglc_sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->format ? $provincialCatalog->format : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = $techposReport->closinginventoryvalue / $value ? $techposReport->closinginventoryvalue / $value : $provincialCatalog->msrp;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $provincialCatalog = SaskatchewanProvincialCatalog::where('sku', $techposReport->sku)->first();
                if ($provincialCatalog == null) {
                    $comments = 'Sku not found in the provincial catalog';
                    $comments = $comments . ', Barcode and Product name are Null';

                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }

                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $this->techposVariableIntialize($techposReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (trim($techposReport->costperunit) != '0') {
                        $average_cost = $techposReport->costperunit;
                    } else {
                        $average_cost = $provincialCatalog->per_unit_cost ?? '0.00';
                    }
                    $value = '';
                    if (trim($techposReport->closinginventoryunits) != '0') {
                        $value = trim($techposReport->closinginventoryunits);
                    } else {
                        $value = 1;
                    }
                    $sku = $techposReport->sku ? $techposReport->sku : $provincialCatalog->sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->type ? $provincialCatalog->type : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin;
                    $average_price = $techposReport->closinginventoryvalue / $value ?? '$0.00';
                    $average_cost = $average_cost;
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode =  $barcode;
            $cleanSheet->sold = $techposReport->quantitysoldunits;
            $cleanSheet->purchased = $techposReport->quantitypurchasedunits;
            $cleanSheet->average_price = $average_price;
            $cleanSheet->average_cost = $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->flag = $flag;
            $cleanSheet->comments = $comments;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function profittech_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'profittechReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->profittechReports as $profittechReports) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $provincialCatalog = OcsProvincialCatalog::where('ocs_variant_number', $profittechReports->product_sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku,Barcode,Product not found in the provincial catalog';
                    if ($profittechReports->quantity_purchased_value != null && $profittechReports->quantity_purchased_units != '0') {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $this->profitechVariableIntialize($profittechReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if ($profittechReports->quantity_purchased_value != null && $profittechReports->quantity_purchased_units != '0') {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '$0.00';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }

                    $sku = $profittechReports->product_sku ? $profittechReports->product_sku : $provincialCatalog->ocs_variant_number;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->category ? $provincialCatalog->category : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : 'Null';
                    $average_price = (int)$profittechReports->opening_inventory_value / (int)$value;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $provincialCatalog = MbllProvincialCatalog::where('skumbll_item_number', $profittechReports->product_sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku,Barcode,Product Name not found in the provincial catalog';

                    if ($profittechReports->quantity_purchased_value != null && $profittechReports->quantity_purchased_units != '0') {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $this->profitechVariableIntialize($profittechReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (
                        $profittechReports->quantity_purchased_units != '0' &&
                        $profittechReports->quantity_purchased_value != null
                    ) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '$0.00';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }

                    $sku = $profittechReports->product_sku ? $profittechReports->product_sku : $provincialCatalog->skumbll_item_number;
                    $product_name = $provincialCatalog->description1 ? $provincialCatalog->description1 : 'Null';
                    $category = $provincialCatalog->type ? $provincialCatalog->type : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->upcgtin ? $provincialCatalog->upcgtin : 'Null';
                    $average_price = (int)$profittechReports->opening_inventory_value / (int)$value;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $provincialCatalog = BritishColumbiaProvincialCatalog::where('sku',  $profittechReports->product_sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku,Barcode,Product Name not found in the provincial catalog';

                    if (
                        $profittechReports->quantity_purchased_units != '0' &&
                        $profittechReports->quantity_purchased_value != null
                    ) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $this->profitechVariableIntialize($profittechReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (
                        $profittechReports->quantity_purchased_units != '0' &&
                        $profittechReports->quantity_purchased_value != null
                    ) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $average_cost = '$0.00';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $sku = $profittechReports->product_sku ? $profittechReports->product_sku : $provincialCatalog->sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->class ? $provincialCatalog->class : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->su_code ? $provincialCatalog->su_code : 'Null';
                    $average_price = (int)$profittechReports->opening_inventory_value / (int)$value;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $provincialCatalog = AlbertaProvincialCatalog::where('aglc_sku', $profittechReports->product_sku)->first();
                if ($provincialCatalog == null) {
                    $comments = 'Sku,Barcode,Product Name not found in the provincial catalog';

                    if (
                        $profittechReports->quantity_purchased_units != '0'
                        && $profittechReports->quantity_purchased_value != null
                    ) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $this->profitechVariableIntialize($profittechReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if (
                        $profittechReports->quantity_purchased_units != '0' &&
                        $profittechReports->quantity_purchased_value != null
                    ) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $average_cost = $provincialCatalog->sell_price_per_unit ? $provincialCatalog->sell_price_per_unit : '$0.00';
                    }
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                        $average_price = (int)$profittechReports->opening_inventory_value / (int)$value;
                    } else {
                        $average_price = $provincialCatalog->msrp ?? '$0.00';
                    }

                    $sku = $profittechReports->product_sku ? $profittechReports->product_sku : $provincialCatalog->aglc_sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->format ? $provincialCatalog->format : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : 'Null';
                    $average_price = $average_price;
                    $average_cost = $average_cost;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $provincialCatalog = SaskatchewanProvincialCatalog::where('sku', $profittechReports->product_sku)->first();

                if ($provincialCatalog == null) {
                    $comments = 'Sku,Barcode,Product Name not found in the provincial catalog';

                    $average_cost = '';
                    if ($profittechReports->quantity_purchased_units != '0' && $profittechReports->quantity_purchased_value != null) {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $comments = $comments . ', Average Cost not found';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }
                    $this->profitechVariableIntialize($profittechReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost, $value);
                } else {
                    if ($profittechReports->quantity_purchased_units != '0') {
                        $average_cost = (int) $profittechReports->quantity_purchased_value / (int) $profittechReports->quantity_purchased_units;
                    } else {
                        $average_cost = $provincialCatalog->per_unit_cost ? $provincialCatalog->per_unit_cost : '$0.00';
                    }
                    $value = '';
                    if (trim($profittechReports->opening_inventory_units != '0')) {
                        $value = trim($profittechReports->opening_inventory_units);
                    } else {
                        $value = 1;
                    }

                    $sku = $profittechReports->product_sku ? $profittechReports->product_sku : $provincialCatalog->sku;
                    $product_name = $provincialCatalog->product_name ? $provincialCatalog->product_name : 'Null';
                    $category = $provincialCatalog->format ? $provincialCatalog->format : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : 'Null';
                    $average_price = (int)$profittechReports->opening_inventory_value / (int)$value;
                    $average_cost = $average_cost;
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode =  $barcode;
            $cleanSheet->sold = $profittechReports->quantity_sold_instore_units;
            $cleanSheet->purchased = $profittechReports->quantity_purchased_units;
            $cleanSheet->average_price = $average_price;
            $cleanSheet->average_cost = $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->flag = $flag;
            $cleanSheet->comments = $comments;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function gobatell_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'gobatellDiagnosticReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->gobatellDiagnosticReports as $gobatellDiagnosticReport) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $ocsProvincialCatalog = new OcsProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'ocs_variant_number', $Barcode = 'gtin', $Product_name = 'product_name', $gobatellDiagnosticReport->supplier_sku, $gobatellDiagnosticReport->compliance_code, $gobatellDiagnosticReport->product, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the provincial catalog';

                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $this->globaltellVariableIntialize($gobatellDiagnosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $average_cost = $provincialCatalog->unit_price ? $provincialCatalog->unit_price : '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }

                    $sku = $provincialCatalog->ocs_variant_number ? $provincialCatalog->ocs_variant_number : $gobatellDiagnosticReport->supplier_sku;
                    $product_name = $gobatellDiagnosticReport->product ? $gobatellDiagnosticReport->product : $provincialCatalog->product_name;
                    $category = $provincialCatalog->category ? $provincialCatalog->category : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $gobatellDiagnosticReport->compliance_code;
                    $average_cost = $average_cost ? $average_cost : $provincialCatalog->unit_price;
                    $average_price = $average_price ? $average_price : '$0.00';
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $ocsProvincialCatalog = new MbllProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'skumbll_item_number', $Barcode = 'upcgtin', $Product_name = 'description1', $gobatellDiagnosticReport->supplier_sku, $gobatellDiagnosticReport->compliance_code, $gobatellDiagnosticReport->product, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the provincial catalog';

                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $this->globaltellVariableIntialize($gobatellDiagnosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $average_cost = $provincialCatalog->unit_price ?? '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $sku = $provincialCatalog->skumbll_item_number ? $provincialCatalog->skumbll_item_number : $gobatellDiagnosticReport->supplier_sku;
                    $product_name = $gobatellDiagnosticReport->product ? $gobatellDiagnosticReport->product : $provincialCatalog->description1;
                    $category = $provincialCatalog->type ? $provincialCatalog->type : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->upcgtin ? $provincialCatalog->upcgtin : $gobatellDiagnosticReport->compliance_code;
                    $average_cost = $average_cost ? $average_cost : $provincialCatalog->unit_price;
                    $average_price = $average_price ? $average_price : '$0.00';
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $ocsProvincialCatalog = new BritishColumbiaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'su_code', $Product_name = 'product_name', $gobatellDiagnosticReport->supplier_sku, $gobatellDiagnosticReport->compliance_code, $gobatellDiagnosticReport->product, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the provincial catalog';

                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $this->globaltellVariableIntialize($gobatellDiagnosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $gobatellDiagnosticReport->supplier_sku;
                    $product_name = $gobatellDiagnosticReport->product ? $gobatellDiagnosticReport->product : $provincialCatalog->product_name;
                    $category = $provincialCatalog->class ? $provincialCatalog->class : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->su_code ? $provincialCatalog->su_code : $gobatellDiagnosticReport->compliance_code;
                    $average_cost = $average_cost ? $average_cost : '$0.00';
                    $average_price = $average_price ? $average_price : '$0.00';
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $ocsProvincialCatalog = new AlbertaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'aglc_sku', $Barcode = 'gtin', $Product_name = 'product_name', $gobatellDiagnosticReport->supplier_sku, $gobatellDiagnosticReport->compliance_code, $gobatellDiagnosticReport->product, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the provincial catalog';

                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $this->globaltellVariableIntialize($gobatellDiagnosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {

                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $average_cost = $provincialCatalog->sell_price_per_unit ? $provincialCatalog->sell_price_per_unit : '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $sku = $provincialCatalog->aglc_sku ? $provincialCatalog->aglc_sku : $gobatellDiagnosticReport->supplier_sku;
                    $product_name = $gobatellDiagnosticReport->product ? $gobatellDiagnosticReport->product : $provincialCatalog->product_name;
                    $category = $provincialCatalog->format ? $provincialCatalog->format : 'Null';
                    $brand = $provincialCatalog->brand_name ? $provincialCatalog->brand_name : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $gobatellDiagnosticReport->compliance_code;
                    $average_cost = $average_cost ? $average_cost : $provincialCatalog->sell_price_per_unit;
                    $average_price = $average_price ? $average_price : $provincialCatalog->msrp;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $ocsProvincialCatalog = new SaskatchewanProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'gtin', $Product_name = 'product_name', $gobatellDiagnosticReport->supplier_sku, $gobatellDiagnosticReport->compliance_code, $gobatellDiagnosticReport->product, $comments);

                if ($provincialCatalog == null) {
                    $comments = $comments . ', Product not found in the provincial catalog';

                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $comments = $comments . ', Average Cost not found';
                            $average_cost = '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }
                    $this->globaltellVariableIntialize($gobatellDiagnosticReport, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    if ($gobatellDiagnosticReport->GobatellSalesSummaryReport) {
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value && $gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions != '0') {
                            $average_cost = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_value / (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->purchases_from_suppliers_additions;
                        } else {
                            $average_cost = $provincialCatalog->per_unit_cost ? $provincialCatalog->per_unit_cost : '0.00';
                        }
                        if ($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions && $gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value) {
                            $average_price = (int)$gobatellDiagnosticReport->GobatellSalesSummaryReport->sold_retail_value / (int)($gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions != '0' ? $gobatellDiagnosticReport->GobatellSalesSummaryReport->sales_reductions : '1');
                        }
                    }

                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $gobatellDiagnosticReport->supplier_sku;
                    $product_name = $gobatellDiagnosticReport->product ? $gobatellDiagnosticReport->product : $provincialCatalog->product_name;
                    $category = $provincialCatalog->type ? $provincialCatalog->type : 'Null';
                    $brand = $provincialCatalog->brand ? $provincialCatalog->brand : 'Null';
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $gobatellDiagnosticReport->compliance_code;
                    $average_cost = $average_cost ? $average_cost : $provincialCatalog->per_unit_cost;
                    $average_price = $average_price ? $average_price : '0.00';
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode = $barcode;
            $cleanSheet->sold = $gobatellDiagnosticReport->sales_reductions ? $gobatellDiagnosticReport->sales_reductions : '0';
            $cleanSheet->purchased = $gobatellDiagnosticReport->purchases_from_suppliers_additions ? $gobatellDiagnosticReport->purchases_from_suppliers_additions : '0';
            $cleanSheet->average_price = '0';
            $cleanSheet->average_cost = '$' . $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->comments = $comments;
            $cleanSheet->flag = $flag;

            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function ductie_clean_report($variable, $retailerReportSubmission, $retailer_id)
    {
        $retailer = $this->getRetailerEntries($relation = 'DuctieDaignosticReports', $retailer_id, $retailerReportSubmission);

        $data = [];
        foreach ($retailer->DuctieDaignosticReports as $DuctieDaignosticReports) {
            $cleanSheet = new CleanSheet();
            [$provincialCatalog, $sku, $product_name, $category, $brand, $barcode, $average_cost, $comments, $flag, $average_price] = ['', '', '', '', '', '', '', '', '0', ''];

            if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
                $ocsProvincialCatalog = new OcsProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'ocs_variant_number', $Barcode = 'gtin', $Product_name = 'product_name', $DuctieDaignosticReports->provincial_sku, $DuctieDaignosticReports->upcgtin, $DuctieDaignosticReports->product, $comments);

                if ($provincialCatalog == null) {
                    $this->ductieVariableIntialize($DuctieDaignosticReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->ocs_variant_number ? $provincialCatalog->ocs_variant_number : $DuctieDaignosticReports->provincial_sku;
                    $product_name = $DuctieDaignosticReports->product ? $DuctieDaignosticReports->product : $provincialCatalog->product_name;
                    $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : $provincialCatalog->category;
                    $brand = $provincialCatalog->brand;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $DuctieDaignosticReports->upcgtin;
                    $average_cost = '$' . $DuctieDaignosticReports->unit_cost ? $DuctieDaignosticReports->unit_cost : $provincialCatalog->unit_price;
                    $average_price = '0';
                }
            } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
                $ocsProvincialCatalog = new MbllProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'skumbll_item_number', $Barcode = 'upcgtin', $Product_name = 'description1', $DuctieDaignosticReports->provincial_sku, $DuctieDaignosticReports->upcgtin, $DuctieDaignosticReports->product, $comments);

                if ($provincialCatalog == null) {
                    $this->ductieVariableIntialize($DuctieDaignosticReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->skumbll_item_number ? $provincialCatalog->skumbll_item_number : $DuctieDaignosticReports->provincial_sku;
                    $product_name = $DuctieDaignosticReports->product ? $DuctieDaignosticReports->product : $provincialCatalog->description1;
                    $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : $provincialCatalog->type;
                    $brand = $provincialCatalog->brand;
                    $barcode = $provincialCatalog->upcgtin ? $provincialCatalog->upcgtin : $DuctieDaignosticReports->upcgtin;
                    $average_cost = '$' . $DuctieDaignosticReports->unit_cost ? $DuctieDaignosticReports->unit_cost : $provincialCatalog->unit_price;
                    $average_price = '0';
                }
            } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
                $ocsProvincialCatalog = new BritishColumbiaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'su_code', $Product_name = 'product_name', $DuctieDaignosticReports->provincial_sku, $DuctieDaignosticReports->upcgtin, $DuctieDaignosticReports->product, $comments);

                if ($provincialCatalog == null) {
                    $this->ductieVariableIntialize($DuctieDaignosticReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $DuctieDaignosticReports->provincial_sku;
                    $product_name = $DuctieDaignosticReports->product ? $DuctieDaignosticReports->product : $provincialCatalog->product_name;
                    $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : $provincialCatalog->class;
                    $brand = $provincialCatalog->brand_name;
                    $barcode = $provincialCatalog->su_code ? $provincialCatalog->su_code : $DuctieDaignosticReports->upcgtin;
                    $average_cost = '$' . $DuctieDaignosticReports->unit_cost ? $DuctieDaignosticReports->unit_cost : '0';
                    $average_price = '0';
                }
            } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
                $ocsProvincialCatalog = new AlbertaProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'aglc_sku', $Barcode = 'gtin', $Product_name = 'product_name', $DuctieDaignosticReports->provincial_sku, $DuctieDaignosticReports->upcgtin, $DuctieDaignosticReports->product, $comments);

                if ($provincialCatalog == null) {
                    $this->ductieVariableIntialize($DuctieDaignosticReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->aglc_sku ? $provincialCatalog->aglc_sku : $DuctieDaignosticReports->provincial_sku;
                    $product_name = $DuctieDaignosticReports->product ? $DuctieDaignosticReports->product : $provincialCatalog->product_name;
                    $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : $provincialCatalog->format;
                    $brand = $provincialCatalog->brand_name;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $DuctieDaignosticReports->upcgtin;
                    $average_cost = '$' . $DuctieDaignosticReports->unit_cost ? $DuctieDaignosticReports->unit_cost : $provincialCatalog->sell_price_per_unit;
                    $average_price = $provincialCatalog->msrp;
                }
            } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
                $ocsProvincialCatalog = new SaskatchewanProvincialCatalog();
                $provincialCatalog = $this->getProvincialCatalog($ocsProvincialCatalog, $Sku = 'sku', $Barcode = 'gtin', $Product_name = 'product_name', $DuctieDaignosticReports->provincial_sku, $DuctieDaignosticReports->upcgtin, $DuctieDaignosticReports->product, $comments);

                if ($provincialCatalog == null) {
                    $this->ductieVariableIntialize($DuctieDaignosticReports, $flag, $sku, $product_name, $category, $brand, $barcode, $average_price, $average_cost);
                } else {
                    $sku = $provincialCatalog->sku ? $provincialCatalog->sku : $DuctieDaignosticReports->provincial_sku;
                    $product_name = $DuctieDaignosticReports->product ? $DuctieDaignosticReports->product : $provincialCatalog->product_name;
                    $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : $provincialCatalog->type;
                    $brand = $provincialCatalog->brand;
                    $barcode = $provincialCatalog->gtin ? $provincialCatalog->gtin : $DuctieDaignosticReports->upcgtin;
                    $average_cost = '$' . $DuctieDaignosticReports->unit_cost ? $DuctieDaignosticReports->unit_cost : $provincialCatalog->per_unit_cost;
                    $average_price = '0';
                }
            }
            $cleanSheet->retailer_name = $retailer->user->name;
            $cleanSheet->location = $retailerReportSubmission->location;
            $cleanSheet->province = $retailerReportSubmission->province;
            $cleanSheet->sku = $sku;
            $cleanSheet->product_name = $product_name;
            $cleanSheet->category = $category;
            $cleanSheet->brand = $brand;
            $cleanSheet->barcode = $barcode;
            $cleanSheet->sold = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->sold : '0';
            $cleanSheet->purchased = $DuctieDaignosticReports->quantity ? $DuctieDaignosticReports->quantity : '0';
            $cleanSheet->average_price = '0';
            $cleanSheet->average_cost = '$' . $average_cost;
            $cleanSheet->variable = $variable;
            $cleanSheet->retailerReportSubmission_id = $retailerReportSubmission->id;
            $cleanSheet->comments = $comments;
            $cleanSheet->flag = $flag;
            $data[] = $cleanSheet->attributesToArray();
        }
        CleanSheet::insert($data);
        return;
    }

    private function greenlineVariableIntialize($greenlineReport, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost)
    {
        $flag = '1';
        $sku = $greenlineReport->sku;
        $product_name = $greenlineReport->name;
        $category = $greenlineReport->compliance_category;
        $brand = $greenlineReport->brand;
        $barcode = $greenlineReport->barcode;
        $average_price = $greenlineReport->average_price;
        $average_cost = $greenlineReport->average_cost;

        return;
    }

    private function covaVariableIntialize($covaDaignosticReport, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost)
    {
        $sku = $covaDaignosticReport->ocs_sku;
        $product_name = $covaDaignosticReport->product_name;
        $category = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->category : '';
        $barcode = $covaDaignosticReport->ontario_barcode_upc;
        $average_price = $covaDaignosticReport->CovaSalesSummaryReport ? $covaDaignosticReport->CovaSalesSummaryReport->average_retail_price : '$0.00';
        $average_cost = '$' . $average_cost;
        $flag = '1';

        return;
    }

    private function pennylaneVariableIntialize($pennylaneReports, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost)
    {
        $sku = $pennylaneReports->product_sku;
        $product_name = $pennylaneReports->description;
        $category = $pennylaneReports->category;
        $average_price = '0';
        $average_cost = $average_cost ? $average_cost : '0';
        $flag = '1';

        return;
    }

    private function techposVariableIntialize($techposReport, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost, $value)
    {
        $flag = '1';
        $sku = $techposReport->sku;
        $average_price = $techposReport->closinginventoryvalue / $value ?? '$0.00';
        $average_cost = $average_cost ? $average_cost : '0';

        return;
    }

    private function profitechVariableIntialize($profittechReports, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost, $value)
    {
        $flag = '1';
        $sku = $profittechReports->product_sku;
        $average_price = (int)$profittechReports->opening_inventory_value / ((int)$value != 0 ? $value : '1');
        $average_cost = '$' . ($average_cost ? $average_cost : '0.00');

        return;
    }

    private function globaltellVariableIntialize($gobatellDiagnosticReport, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost)
    {
        $sku = $gobatellDiagnosticReport->supplier_sku;
        $product_name = $gobatellDiagnosticReport->product;
        $category = 'Null';
        $brand = 'Null';
        $barcode = $gobatellDiagnosticReport->compliance_code;
        $average_cost = $average_cost ? $average_cost : '0.00';
        $average_price = $average_price ? $average_price : '$0.00';
        $flag = '1';

        return;
    }

    private function ductieVariableIntialize($DuctieDaignosticReports, &$flag, &$sku, &$product_name, &$category, &$brand, &$barcode, &$average_price, &$average_cost)
    {
        $sku = $DuctieDaignosticReports->provincial_sku;
        $product_name = $DuctieDaignosticReports->product;
        $barcode = $DuctieDaignosticReports->upcgtin;
        $category = $DuctieDaignosticReports->DuctieSalesSummaryReport ? $DuctieDaignosticReports->DuctieSalesSummaryReport->mastercategory : '';
        $average_cost = '$' . $DuctieDaignosticReports->unit_cost;
        $average_price = '0';
        $flag = '1';

        return;
    }

    private function getProvincialCatalog($ocsProvincialCatalog, $sku, $barcode, $product_name, $checkSku, $checkBarcode, $checkProductName, &$comments)
    {
        $provincialCatalog = $ocsProvincialCatalog->where($sku, $checkSku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the Provincial Catalog';
            $provincialCatalog = $ocsProvincialCatalog->where($barcode, $checkBarcode)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the Provincial Catalog';
            if ($checkBarcode != null) {
                $provincialCatalog = $ocsProvincialCatalog->where($product_name, $checkProductName)->first();
            }
        }
        return $provincialCatalog;
    }

    private function averageCostPos($greenlineReport, $provincialCatalogAverageCost, $total_cost, $net_qty)
    {
        if ($greenlineReport != null) {
            if (trim($greenlineReport->average_cost) != '$0.00') {
                $average_cost = $greenlineReport->average_cost;
            } else {
                $average_cost = '$' . ($provincialCatalogAverageCost ? $provincialCatalogAverageCost : '0.00');
            }
        } elseif ($total_cost != null && $net_qty != null && trim($net_qty) != '0') {
            $average_cost = $total_cost / $net_qty;
        }
        return $average_cost;
    }

    private function getProvincialCatalogCova($ocsProvincialCatalog, $sku, $barcode, $product_name, $covaDaignosticReport, &$comments)
    {
        $provincialCatalog = OcsProvincialCatalog::where('ocs_variant_number', $covaDaignosticReport->ocs_sku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the Provincial Catalog';
            $provincialCatalog = OcsProvincialCatalog::where('gtin', $covaDaignosticReport->ontario_barcode_upc)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the Provincial Catalog';
            $provincialCatalog = OcsProvincialCatalog::where('product_name', $covaDaignosticReport->product_name)->first();
        }

        return $provincialCatalog;
    }

    private function getRetailerEntries($relation, $retailer_id, $retailerReportSubmission)
    {
        if ($relation == 'DuctieDaignosticReports') {
            $retailer = Retailer::where('id', $retailer_id)->with(['DuctieDaignosticReports' => function ($q) use ($retailerReportSubmission) {
                $q->whereMonth('date', Carbon::parse($retailerReportSubmission->date)->format('m'));
                $q->whereYear('date', Carbon::parse($retailerReportSubmission->date)->format('Y'));
                $q->where('province', $retailerReportSubmission->province);
                $q->where('ductie_diagnostic_report_retailers.location', $retailerReportSubmission->location);
                return $q;
            }])->first();
        } else {
            $retailer = Retailer::where('id', $retailer_id)->with([$relation => function ($q) use ($retailerReportSubmission) {
                $q->whereMonth('date', Carbon::parse($retailerReportSubmission->date)->format('m'));
                $q->whereYear('date', Carbon::parse($retailerReportSubmission->date)->format('Y'));
                $q->where('province', $retailerReportSubmission->province);
                $q->where('location', $retailerReportSubmission->location);
                return $q;
            }])->first();
        }
        return $retailer;
    }

    private function checkPos($variable, $retailerReportSubmission, $retailer_id)
    {
        $posCleanSheet = $retailerReportSubmission->pos . '_clean_report';
        $this->$posCleanSheet($variable, $retailerReportSubmission, $retailer_id);

        return;
    }
}
