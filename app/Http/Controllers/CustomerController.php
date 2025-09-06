<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Paket;
use App\Models\Mikrotik;
use App\Models\PppType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::with(['paket', 'mikrotik'])->select('customers.*');

            return DataTables::of($customers)
                ->addColumn('status', function ($customer) {
                    return $customer->disabled ?
                        '<span class="badge bg-danger">Inactive</span>' :
                        '<span class="badge bg-success">Active</span>';
                })
                ->addColumn('paket_name', function ($customer) {
                    return $customer->paket ? $customer->paket->name : '-';
                })
                ->addColumn('action', function ($customer) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('customers.show', $customer->slug) . '" class="btn btn-sm btn-info">View</a>';
                    $actions .= '<a href="' . route('customers.edit', $customer->slug) . '" class="btn btn-sm btn-warning">Edit</a>';
                    $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteCustomer(\'' . $customer->slug . '\')">Delete</button>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('customers.index');
    }

    public function create()
    {
        $pakets = Paket::where('disabled', false)->get();
        $mikrotiks = Mikrotik::where('disabled', false)->get();
        $ppp_types = PppType::all();

        return view('customers.create', compact('pakets', 'mikrotiks', 'ppp_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'contact' => 'required|string',
            'address' => 'required|string',
            'paket_id' => 'required|exists:pakets,id',
            'mikrotik_id' => 'required|exists:mikrotiks,id',
            'password' => 'required|min:6',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time()),
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
            'paket_id' => $request->paket_id,
            'mikrotik_id' => $request->mikrotik_id,
            'password' => $request->password,
            'username' => Str::slug($request->name),
            'disabled' => true, // Default inactive
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dibuat.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['paket', 'mikrotik', 'billings']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $pakets = Paket::where('disabled', false)->get();
        $mikrotiks = Mikrotik::where('disabled', false)->get();
        $ppp_types = PppType::all();

        return view('customers.edit', compact('customer', 'pakets', 'mikrotiks', 'ppp_types'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'contact' => 'required|string',
            'address' => 'required|string',
            'paket_id' => 'required|exists:pakets,id',
            'mikrotik_id' => 'required|exists:mikrotiks,id',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
