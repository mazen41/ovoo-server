<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function list()
    {
        $pageTitle = "Manage Coupons";
        $coupons = Coupon::orderBy('id', getOrderBy())->searchable('name', 'code')->withCount('totalUses')->paginate(getPaginate());
        return view('admin.coupon.list', compact('pageTitle', 'coupons'));
    }

    public function store(Request $request, $id = null)
    {
        if (!$request->date) {
            return back()->withErrors(['date' => 'The date field is required.'])->withInput();
        }

        $dates = explode(" to ", $request->date);

        if (count($dates) != 2) {
            return back()->withErrors(['date' => 'Date format must be start date to end date.'])->withInput();
        }

        $startDate = Carbon::parse($dates[0]);
        $endDate   = Carbon::parse($dates[1]);

        $request->merge([
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        $request->validate([
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:40|unique:coupons,code,' . $id,
            'type'                => 'required|in:' . Status::COUPON_TYPE_FIXED . ',' . Status::COUPON_TYPE_PERCENTAGE,
            'amount'              => 'required|numeric|gte:0',
            'min_purchase_amount' => 'required|numeric|gte:0',
            'use_limit'           => 'required|integer|gte:-1',
            'per_user_limit'      => 'required|integer|gte:-1',
            'start_date'          => 'required|date|before_or_equal:end_date',
            'end_date'            => 'required|date|after_or_equal:start_date',
        ]);


        if ($request->use_limit != Status::UNLIMITED) {
            if ($request->per_user_limit == -1) {
                return back()->withErrors(['per_user_limit' => 'Per user limit cannot be -1 when use limit is set.'])->withInput();
            }

            if ($request->per_user_limit > $request->use_limit) {
                return back()->withErrors(['per_user_limit' => 'Per user limit cannot be greater than use limit.'])->withInput();
            }
        }

        if (Status::COUPON_TYPE_PERCENTAGE == $request->type && $request->amount >= 100) {
            return back()->withErrors(['amount' => 'Coupon amount cannot be greater than 100%'])->withInput();
        }

        if ($id) {
            $coupon  = Coupon::findOrFail($id);
            $message = "Coupon updated successfully";
        } else {
            $coupon  = new Coupon();
            $message = "Coupon created successfully";
        }

        $coupon->name                = $request->name;
        $coupon->code                = $request->code;
        $coupon->type                = $request->type;
        $coupon->amount              = $request->amount;
        $coupon->min_purchase_amount = $request->min_purchase_amount;
        $coupon->use_limit           = $request->use_limit;
        $coupon->per_user_limit      = $request->per_user_limit;
        $coupon->start_date          = $request->start_date;
        $coupon->end_date            = $request->end_date;
        $coupon->save();

        if ($coupon->status == Status::COUPON_EXPIRED && $coupon->end_date->isFuture()) {
            $coupon->status = Status::COUPON_ACTIVE;
            $coupon->save();
        }

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        Coupon::findOrFail($id);
        Coupon::changeStatus($id);
        $notify[] = ['success', 'Coupon Status Updated Successfully'];
        return back()->withNotify($notify);
    }
}
