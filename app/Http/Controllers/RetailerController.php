<?php

namespace App\Http\Controllers;

use App\Http\Requests\Retailer\RetailerAddCsv;
use App\Http\Requests\Retailer\RetailerAddRequest;
use App\Http\Requests\Retailer\RetailerAddressStoreRequest;
use App\Http\Requests\Retailer\RetailerUpdateRequestedRequest;
use App\Http\Requests\Retailer\UpdateRetailerRequest;
use App\Imports\RetailerImport;
use App\Traits\ReturnMessage;
use Illuminate\Http\Request;
use App\Models\Retailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\changeStatusMail;
use App\Interfaces\User\RetailerRepositoryInterface;
use App\Jobs\sendRetailerRegistrationEmailJob;
use App\Models\RetailerAddress;
use App\Models\RetailerReportSubmission;
use App\Models\User;
use App\Traits\CanadaCities;
use App\Traits\GlobalVariables;
use Maatwebsite\Excel\Facades\Excel;

class RetailerController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private RetailerRepositoryInterface $retailerRepository;

    public function __construct(RetailerRepositoryInterface $retailerRepository)
    {
        $this->retailerRepository = $retailerRepository;
    }

    public function index($status = null)
    {
        if (!Auth::user()->hasPermissionTo('retailer-list')) {
            return redirect()->route('dashboard');
        }
        $retailerData = $this->retailerRepository->index($status);
        $count = $retailerData['count'];
        $retailers = $retailerData['retailers'];

        return view(
            'admin.retailer.index',
            ['retailers' => $retailers, 'count' => $count]
        );
    }

    public function requested()
    {
        $retailers = Retailer::where('status', 'Requested')->with(['user'])->latest()->paginate(10);

        return view(
            'admin.retailer.requested',
            compact('retailers')
        );
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('retailer-create')) {
            return redirect()->route('dashboard');
        }

        return view('admin.retailer.create', ['provinces' => $this->getCanadaCities()]);
    }

    public function store(Request $request, Retailer $retailer)
    {
        try {
            DB::transaction(function () use ($request, $retailer) {
                $this->retailerRepository->store($request, $retailer);
            });

            return  response()->json();
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }

    public function show(Retailer $retailer)
    {
        if (!Auth::user()->hasPermissionTo('retailer-show')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.retailer.show',
            ['retailer' => $this->retailerRepository->getRetailer($retailer)]
        );
    }

    public function edit(Retailer $retailer)
    {
        if (Auth::user()->hasRole('Retailer')) {
            $retailer = $this->retailerRepository->getRetailer($retailer);
            return view(
                'retailers.profile.editProfile',
                [
                    'retailer' => $retailer,
                    'provinces' => $this->getCanadaCities(),
                    'statuses' => collect($retailer->status)
                ]
            );
        } else {
            if (!Auth::user()->hasPermissionTo('retailer-edit')) {
                return redirect()->route('dashboard');
            }
            return view(
                'admin.retailer.edit',
                [
                    'retailer' => $this->retailerRepository->getRetailer($retailer),
                    'provinces' => $this->getCanadaCities(),
                    'statuses' => collect(['Pending', 'Approved', 'Rejected'])
                ]
            );
        }
    }

    public function update(UpdateRetailerRequest $request, Retailer $retailer)
    {
        if (Auth::user()->hasRole('Retailer')) {
            try {
                DB::transaction(function () use ($request, $retailer) {
                    $this->retailerRepository->updateRetailer($request, $retailer);
                });
                $messages['success'] = "Profile Updated Successfully";
                return redirect()
                    ->back()
                    ->with('messages', $messages);
            } catch (\Exception $e) {
                return $this->errorMessage($e->getMessage());
            }
        } else {
            try {
                DB::transaction(function () use ($request, $retailer) {
                    $this->retailerRepository->updateRetailer($request, $retailer);
                });

                $messages['success'] = "Retailer Updated Successfully";
                return redirect()
                    ->route('retailers.index')
                    ->with('messages', $messages);
            } catch (\Exception $e) {
                return $this->errorMessage($e->getMessage());
            }
        }
    }

    public function editRequested(Retailer $retailer)
    {
        return view('admin.retailer.edit-requested', ['retailer' => $retailer->load('user')]);
    }

    public function update_Requested(RetailerUpdateRequestedRequest $request, Retailer $retailer)
    {
        try {
            DB::transaction(function () use ($request, $retailer) {
                $this->retailerRepository->update_Requested($request, $retailer);
            });

            $messages['success'] = "Retailer Updated Successfully";
            return redirect()
                ->route('retailers.requested')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }

    public function send_Email(User $user)
    {
        $this->retailerRepository->sendEmail($user);

        $messages['success'] = "Email Send Successfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }

    public function send_registration(User $user)
    {
        dispatch(new sendRetailerRegistrationEmailJob($user));
        $messages['success'] = "Retailer Added Successfully";

        return redirect()->back()
            ->with('messages', $messages);
    }

    public function destroy(Retailer $retailer)
    {
        if (!Auth::user()->hasPermissionTo('retailer-delete')) {
            return redirect()->route('dashboard');
        }
        $retailer = Retailer::find($retailer->id)->delete();

        $messages['success'] = "Retailer Deleted Sucessfully";

        return redirect()->back()->with(['messages' => $messages]);
    }

    public function add(RetailerAddRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('retailer-create')) {
            return redirect()->route('dashboard');
        } else {
            try {
                DB::transaction(function () use ($request) {
                    $this->retailerRepository->add($request);
                });

                $messages['success'] = "Retailer Added Successfully";
                return redirect()
                    ->route('retailers.requested')
                    ->with('messages', $messages);
            } catch (\Exception $e) {
                return $this->errorMessage($e->getMessage());
            }
        }
    }

    public function getRetailerForm(User $user)
    {
        if ($user->flag == 1) {
            $retailer = $this->retailerRepository->getRetailerForm($user);
            $provinces = $this->getCanadaCities();

            return view('admin.retailer.add', compact('user', 'retailer', 'provinces'));
        } else {
            abort(404);
        }
    }

    public function uploadCsv(RetailerAddCsv $request)
    {
        if (!Auth::user()->hasPermissionTo('retailer-create')) {
            return redirect()->route('dashboard');
        }
        try {
            Excel::import(new RetailerImport, request()->file('retailers_csv'));
            $messages['success'] = "Retailer Added Successfully";
            return redirect()
                ->route('retailers.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }

    public function search($status)
    {
        return view('admin.retailer.monthly_report_status', ['retailers' => $this->retailerRepository->search($status)]);
    }

    public function address_create(Retailer $retailer)
    {
        return view('admin.retailer.addresses.create', [
            'retailer' => $retailer,
            'provinces' => $this->getCanadaCities()
        ]);
    }

    public function address_store(RetailerAddressStoreRequest $request, Retailer $retailer)
    {
        try {
            DB::beginTransaction();
            $this->retailerRepository->address_store($request, $retailer);
            DB::commit();

            $messages['success'] = "Addresses Added Successfully";

            return response()->json();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }
    public function destroy_address($address)
    {
        try {
            DB::beginTransaction();
            $retailer = RetailerAddress::whereHas('report_submission')->find($address);
            if ($retailer) {
                return $this->warningMessage("You need to Remove the Report first present against this location");
            }
            RetailerAddress::find($address)->delete();

            DB::commit();

            $messages['success'] = "Address  Deleted Successfully";

            return redirect()->back()->with(['messages' => $messages]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function getRetailerReports(Retailer $retailer)
    {
        $reports = RetailerReportSubmission::where('status', 'Submited')->where('retailer_id', $retailer->id)
            ->with('retailer.user', 'address')
            ->whereHas('retailer', function ($q) {
                return $q->where('status', 'Approved');
            })
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);

        return view('admin.retailer.reports.allReportStatus', compact('reports'));
    }
}
