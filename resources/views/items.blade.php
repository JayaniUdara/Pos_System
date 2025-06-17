@extends('layouts.master')

@section('title', 'Items')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Item List</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="itemsDataTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>M/F Date</th>
                            <th>Exp Date</th>
                            <th>Company Name</th>
                            <th>Image</th>
                            <th>Quantity</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Register New Item</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="itemForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Item Code *</label>
                                    <input type="text" name="item_code" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Item Name *</label>
                                    <input type="text" name="item_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>M/F Date</label>
                                    <input type="date" name="mfd_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Exp Date *</label>
                                    <input type="date" name="exp_date" class="form-control" required min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Company Name</label>
                                    <input type="text" name="company_name" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Quantity *</label>
                                    <input type="number" name="quantity" class="form-control" min="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Cost Price *</label>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Selling Price *</label>
                                    <input type="number" name="selling_price" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable with server-side processing
    var table = $('#itemsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('items.getItems') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'item_code', name: 'item_code'},
            {data: 'item_name', name: 'item_name'},
            {data: 'mfd_date', name: 'mfd_date'},
            {data: 'exp_date', name: 'exp_date'},
            {data: 'company_name', name: 'company_name'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'quantity', name: 'quantity'},
            {data: 'cost_price', name: 'cost_price'},
            {data: 'selling_price', name: 'selling_price'}
        ],
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });

    // Form submission handler
    $('#itemForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');

        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
        );

        $.ajax({
            url: "{{ route('items.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    $('#addItemModal').modal('hide');
                    toastr.success(response.message);
                    table.ajax.reload(null, false);
                    $('#itemForm').trigger('reset');
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Save Item');
            }
        });
    });

    // Reset form when modal is closed
    $('#addItemModal').on('hidden.bs.modal', function() {
        $('#itemForm').trigger('reset');
    });
});
</script>
@endsection
@endsection
