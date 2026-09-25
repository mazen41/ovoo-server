<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;

class PricingPlanController extends Controller
{
    public function index()
    {
        $pageTitle    = "Pricing Plan";
        $pricingPlans = PricingPlan::orderBy('monthly_price')->paginate(getPaginate());
        return view('admin.plans.index', compact('pageTitle', 'pricingPlans'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'             => 'required|string|max:40|unique:pricing_plans,name,' . $id,
            'description'      => 'nullable|string|max:200',
            'monthly_price'    => 'required|numeric|gte:0|lte:yearly_price',
            'yearly_price'     => 'required|numeric|gte:0|gte:monthly_price',
            'account_limit'    => 'required|integer|gte:-1',
            'agent_limit'      => 'required|integer|gte:-1',
            'contact_limit'    => 'required|integer|gte:-1',
            'template_limit'   => 'required|integer|gte:-1',
            'flow_limit'       => 'required|integer|gte:-1',
            'campaign_limit'   => 'required|integer|gte:-1',
            'short_link_limit' => 'required|integer|gte:-1',
            'floater_limit'    => 'required|integer|gte:-1',
        ]);

        if ($id) {
            $message     = "Pricing plan updated successfully";
            $pricingPlan = PricingPlan::findOrFail($id);
        } else {
            $message     = "Pricing plan added successfully";
            $pricingPlan = new PricingPlan();
        }

        $pricingPlan->name                = $request->name;
        $pricingPlan->description         = $request->description;
        $pricingPlan->monthly_price       = $request->monthly_price;
        $pricingPlan->yearly_price        = $request->yearly_price;
        $pricingPlan->account_limit       = $request->account_limit;
        $pricingPlan->agent_limit         = $request->agent_limit;
        $pricingPlan->contact_limit       = $request->contact_limit;
        $pricingPlan->template_limit      = $request->template_limit;
        $pricingPlan->flow_limit          = $request->flow_limit;
        $pricingPlan->campaign_limit      = $request->campaign_limit;
        $pricingPlan->short_link_limit    = $request->short_link_limit;
        $pricingPlan->floater_limit       = $request->floater_limit;
        $pricingPlan->is_popular          = $request->is_popular ? Status::YES : Status::NO;
        $pricingPlan->welcome_message     = $request->welcome_message ? Status::YES : Status::NO;
        $pricingPlan->ai_assistance       = $request->ai_assistance ? Status::YES : Status::NO;
        $pricingPlan->interactive_message = $request->interactive_message ? Status::YES : Status::NO;
        $pricingPlan->ecommerce_available = $request->ecommerce_available ? Status::YES : Status::NO;
        $pricingPlan->api_available       = $request->api_available ? Status::YES : Status::NO;
        $pricingPlan->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return PricingPlan::changeStatus($id);
    }
}
