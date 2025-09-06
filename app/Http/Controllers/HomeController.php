<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Billing;
use App\Models\Paket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('disabled', false)->count(),
            'total_billings' => Billing::count(),
            'unpaid_billings' => Billing::where('status', 'BL')->count(),
            'total_revenue' => Billing::where('status', 'LS')->sum('paket_price'),
            'pending_revenue' => Billing::where('status', 'BL')->sum('paket_price'),
        ];

        $recent_customers = Customer::latest()->take(5)->get();
        $recent_billings = Billing::with('customer')->latest()->take(10)->get();

        return view('dashboard', compact('stats', 'recent_customers', 'recent_billings'));
    }
}
