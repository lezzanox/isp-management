@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Customers</h5>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Customer
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="customers-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kontak</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('customers.index') }}',
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'contact', name: 'contact'},
            {data: 'paket_name', name: 'paket_name', orderable: false},
            {data: 'status', name: 'status', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
});

function deleteCustomer(slug) {
    if (confirm('Apakah Anda yakin ingin menghapus customer ini?')) {
        $.ajax({
            url: '/cpanel/customers/' + slug,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#customers-table').DataTable().ajax.reload();
                alert('Customer berhasil dihapus');
            },
            error: function() {
                alert('Terjadi kesalahan');
            }
        });
    }
}
</script>
@endpush