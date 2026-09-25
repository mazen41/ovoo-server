<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PlanPurchase;
use App\Models\PricingPlan;
use Barryvdh\DomPDF\Facade\Pdf;

class SubscriptionController extends Controller
{
    public function index()
    {
        $pageTitle          = 'Manage Subscription';
        $user               = getParentUser();
        $pricingPlans       = PricingPlan::active()->orderBy('monthly_price')->get();
        $plan               = @$user->plan;
        $subscriptions      = PlanPurchase::where('user_id', $user->id)->filter(['payment_method'])->orderBy('id', 'desc')->with('gateway')->paginate(getPaginate());
        $activeSubscription = $subscriptions->first();

    return view('Template::user.subscription.index', compact('pageTitle', 'pricingPlans', 'user', 'activeSubscription', 'subscriptions', 'plan'));
    }

    public function invoice($subscriptionId)
    {
        $pageTitle    = "Subscription Invoice";
        $user         = getParentUser();
        $subscription = PlanPurchase::where('user_id', $user->id)->with(['plan', 'user'])->findOrFail($subscriptionId);
        return view("Template::user.whatsapp.invoice", compact('pageTitle', 'subscription'));
    }

    public function downloadInvoice($subscriptionId)
    {
        $pageTitle = "Download Invoice";
        $user      =   getParentUser();
        $subscription = PlanPurchase::where('user_id', $user->id)->with(['plan', 'user'])->findOrFail($subscriptionId);
        $pdf      = Pdf::loadView('Template::user.whatsapp.print-invoice', compact('subscription', 'pageTitle'));
        $fileName = 'invoice.pdf';

        return $pdf->stream($fileName);
    }

    public function printInvoice($subscriptionId)
    {
        $user = getParentUser();
        $subscription = PlanPurchase::where('user_id', $user->id)->with(['plan', 'user'])->find($subscriptionId);
        $pageTitle = "Print invoice";
        $html = view('Template::user.whatsapp.print-invoice', compact('subscription', 'pageTitle'))->render();
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function autoRenewal()
    {
        $user         = getParentUser();
        $subscription = PlanPurchase::where('user_id', $user->id)->orderBy('id', 'desc')->first();

        if (!$subscription) {
            $notify[] = ['error', 'Subscription not found.'];
            return apiResponse('subscription_not_found', 'error', ['The subscription not found']);
        }

        $subscription->auto_renewal = !$subscription->auto_renewal;
        $subscription->save();

        return apiResponse('subscription_updated', 'success', ['Subscription auto renewal updated successfully']);
    }
}
