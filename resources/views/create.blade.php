<!-- Add Item Modal -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
    Add Item
</button>

<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Register New Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
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