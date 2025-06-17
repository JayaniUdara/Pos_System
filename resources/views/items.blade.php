@extends('layouts.master')

@section('title', 'Items')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Item List</h2>
            @include('create')
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
                        @foreach ($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->mfd_date }}</td>
                                <td>{{ $item->exp_date }}</td>
                                <td>{{ $item->company_name }}</td>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Image" width="50">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->cost_price, 2) }}</td>
                                <td>{{ number_format($item->selling_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>

        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection