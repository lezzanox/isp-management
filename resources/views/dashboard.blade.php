@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row mb-4">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-3">
        <div class="card stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">{{ number_format($stats['total_customers']) }}</h3>
                        <p class="card-text mb-0">Total Customers</p>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">{{ number_format($stats['active_customers']) }}</h3>
                        <p class="card-text mb-0">Active Customers</p>
                    </div>
                    <i class="bi bi-wifi fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">{{ number_format($stats['unpaid_billings']) }}</h3>
                        <p class="card-text mb-0">Unpaid Bills</p>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">Rp {{ number_format($stats['total_revenue']) }}</h3>
                        <p class="card-text mb-0">Total Revenue</p>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Customers -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Customers</h5>
            </div>
            <div class="card-body">
                @forelse($recent_customers as $customer)
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=667eea&color=ffffff" 
                         class="rounded-circle me-3" width="40" height="40">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $customer->name }}</h6>
                        <small class="text-muted">{{ $customer->contact }}</small>
                    </div>
                    <span class="badge {{ $customer->disabled ? 'bg-danger' : 'bg-success' }}">
                        {{ $customer->disabled ? 'Inactive' : 'Active' }}
                    </span>
                </div>
                @empty
                <p class="text-muted">Belum ada customer</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Billings -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Billings</h5>
            </div>
            <div class="card-body">
                @forelse($recent_billings as $billing)
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-0">{{ $billing->customer_name }}</h6>
                        <small class="text-muted">{{ $billing->billing_number }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $billing->status == 'LS' ? 'bg-success' : 'bg-warning' }}">
                            {{ $billing->status == 'LS' ? 'Paid' : 'Unpaid' }}
                        </span>
                        <div class="small text-muted">Rp {{ number_format($billing->paket_price) }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted">Belum ada billing</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection