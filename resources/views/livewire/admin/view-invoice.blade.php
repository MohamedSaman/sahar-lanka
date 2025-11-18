<div class="container-fluid py-4">
    <!-- Page Header with Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <div class="card-header text-white p-5 rounded-t-4 d-flex align-items-center"
                    style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-receipt-cutoff text-white fs-4" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold tracking-tight text-white">Bills & Invoices</h3>
                        <p class="text-white opacity-80 mb-0 text-sm">View and manage all sales invoices</p>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="row g-4">

                        <!-- Stats Cards -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="bi bi-receipt text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Total Sales</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">{{ number_format($totalSales) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                                            <i class="bi bi-calendar-check text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Today's Sales</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">{{ number_format($todaySales) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                                            <i class="bi bi-currency-dollar text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Total Revenue</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">Rs.{{ number_format($totalRevenue, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                                            <i class="bi bi-cash-coin text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Today's Revenue</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">Rs.{{ number_format($todayRevenue, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
        <!-- Search & Filter Bar -->
        <div class="card-header p-4" style="background-color: #eff6ff;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="input-group shadow-sm rounded-full overflow-hidden" style="max-width: 400px;">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-search text-blue-600" aria-hidden="true"></i>
                    </span>
                    <input type="text"
                        class="form-control border-0 py-2.5 bg-white text-gray-800"
                        placeholder="Search by invoice number or customer..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off"
                        aria-label="Search by invoice number or customer">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                            type="button" id="filterDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-funnel me-1"></i> Filters
                        </button>
                        <div class="dropdown-menu p-4 shadow-lg border-0 rounded-4" style="width: 300px;"
                            aria-labelledby="filterDropdown">
                            <h6 class="dropdown-header bg-light rounded py-2 mb-3 text-center text-sm fw-semibold" style="color: #1e3a8a;">Filter Options</h6>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Date From</label>
                                <input type="date" class="form-control form-control-sm rounded-full shadow-sm" wire:model.live="dateFrom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Date To</label>
                                <input type="date" class="form-control form-control-sm rounded-full shadow-sm" wire:model.live="dateTo">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Payment Type</label>
                                <select class="form-select form-select-sm rounded-full shadow-sm" wire:model.live="paymentType">
                                    <option value="">All</option>
                                    <option value="full">Full Payment</option>
                                    <option value="partial">Partial Payment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Customer Type</label>
                                <select class="form-select form-select-sm rounded-full shadow-sm" wire:model.live="customerType">
                                    <option value="">All</option>
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                                    wire:click="resetFilters">
                                    <i class="bi bi-x-circle me-1"></i> Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #eff6ff;">
                        <tr>
                            <th class="ps-4 text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Invoice #</th>
                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Customer</th>
                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Date</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Items</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-end" style="color: #1e3a8a;">Total Amount</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Payment</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Status</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td class="fw-bold ps-4">#{{ $sale->invoice_number }}</td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                                    <div class="text-xs text-gray-600">{{ $sale->customer->phone ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $sale->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-600">{{ $sale->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                    {{ $sale->items->count() }} Items
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rs.{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-center">
                                @if($sale->payment_type === 'full')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Full</span>
                                @else
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">Partial</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($sale->payment_status === 'paid')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Paid</span>
                                @elseif($sale->payment_status === 'partial')
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">Partial</span>
                                @else
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">Unpaid</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white rounded-pill px-3" wire:click="viewInvoice({{ $sale->id }})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-600">No invoices found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
            <div class="card-footer p-4 bg-white border-top rounded-b-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="text-sm text-gray-600">
                        Showing <span class="fw-semibold text-gray-800">{{ $sales->firstItem() }}</span>
                        to <span class="fw-semibold text-gray-800">{{ $sales->lastItem() }}</span> of
                        <span class="fw-semibold text-gray-800">{{ $sales->total() }}</span> results
                    </div>
                    <div>
                        {{ $sales->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Invoice Details Modal -->
    @if($saleDetails)
    <div wire:ignore.self class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 shadow-xl"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #FFFFFF, #F8F9FA);">
                <div class="modal-header"
                    style="background-color: #233D7F; color: #FFFFFF; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                    <h5 class="modal-title fw-bold tracking-tight">
                        <i class="bi bi-receipt me-2"></i>Sales Receipt
                    </h5>
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-sm rounded-full px-3 transition-all hover:shadow"
                            onclick="printInvoice()" style="background-color: #233D7F;border-color:#fff; color: #fff;">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4" id="invoiceContent">
                    <div class="receipt-container">
                        <div class="text-center mb-4">
                            <h3 class="mb-1 fw-bold tracking-tight" style="color: #233D7F;">SAHAR LANKA</h3>
                            <h5 class="mb-1 fw-medium" style="color: #233D7F;">Importers & Retailers of Genuine Spares for <br> MARUTI-LEYLAND - MAHINDRA-TATA-ALTO</h5>
                            <p class="mb-0 text-muted small" style="color: #6B7280;">NO. 397/, DUNU ELA, THIHARIYA, KALAGEDIHENA</p>
                            <p class="mb-0 text-muted small" style="color: #6B7280;">Phone: 077 6718838</p>
                            <hr style="border: 2px solid #233D7F;">
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                @if ($saleDetails['sale']->customer)
                                <p class="mb-1" style="color: #233D7F;"><strong>Customer Name:</strong> {{ $saleDetails['sale']->customer->name }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Address:</strong> {{ $saleDetails['sale']->customer->address ?? 'N/A' }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Phone:</strong> {{ $saleDetails['sale']->customer->phone ?? 'N/A' }}</p>
                                @else
                                <p class="text-muted" style="color: #6B7280;">Walk-in Customer</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1" style="color: #233D7F;"><strong>Invoice Number:</strong> {{ $saleDetails['sale']->invoice_number }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Date:</strong> {{ $saleDetails['sale']->created_at->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }}</p>
                                <p class="mb-1"><strong>Payment Status:</strong>
                                    @if(ucfirst($saleDetails['sale']->payment_status) == 'Paid')
                                    <span class="badge" style="background-color: #0F5132; color: #FFFFFF;">Paid</span>
                                    @else
                                    <span class="badge" style="background-color: #842029; color: #FFFFFF;">Credit</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm border-1" style="border-color: #233D7F;">
                                <thead style="background-color: #233D7F; color: #FFFFFF;">
                                    <tr>
                                        <th scope="col" class="text-center py-2">No</th>
                                        <th scope="col" class="text-center py-2">Code</th>
                                        <th scope="col" class="text-center py-2">Product Name</th>
                                        <th scope="col" class="text-center py-2">Qty</th>
                                        <th scope="col" class="text-center py-2">Unit Price</th>
                                        <th scope="col" class="text-center py-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody style="color: #233D7F;">
                                    @foreach ($saleDetails['items'] as $index => $item)
                                    <tr>
                                        <td class="text-center py-1">{{ $index + 1 }}</td>
                                        <td class="text-center py-1">{{ $item->product->product_code ?? 'N/A' }}</td>
                                        <td class="text-left py-1">{{ $item->product->product_name ?? 'N/A' }}</td>
                                        <td class="text-center py-1">{{ $item->quantity }}</td>
                                        <td class="text-right py-1">{{ number_format($item->price, 2) }}</td>
                                        <td class="text-right py-1">{{ number_format(($item->price * $item->quantity) - ($item->discount * $item->quantity), 2) }}</td>
                                    </tr>
                                    @endforeach
                                    
                                    <!-- Summary rows -->
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold" style="font-size: 14px;">Amount (LKR):</td>
                                        <td class="text-right py-2 fw-bold" style="font-size: 14px;">{{ number_format($saleDetails['sale']->subtotal, 2) }}</td>
                                    </tr>
                                    @if($saleDetails['sale']->discount_amount > 0)
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Total Discount:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">({{ number_format($saleDetails['sale']->discount_amount, 2) }})</td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">{{ number_format($saleDetails['adjustedGrandTotal'], 2) }}</td>
                                    </tr>
                                    @else
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Total Discount:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;"></td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;"></td>
                                    </tr>
                                    @endif
                                    
                                    @if($saleDetails['totalReturnAmount'] > 0)
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Returns:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">({{ number_format($saleDetails['totalReturnAmount'], 2) }})</td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">After Return Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;"></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Return Items Section (if any) -->
                        @if($saleDetails['returnItems']->count() > 0)
                        <div class="return-items-section mb-4">
                            <h6 class="fw-bold mb-3 text-danger">
                                <i class="bi bi-arrow-return-left me-2"></i>Returned Items Details
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-danger">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Total</th>
                                            <th>Notes</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saleDetails['returnItems'] as $index => $return)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $return->product->product_name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $return->return_quantity }}</td>
                                            <td class="text-right">Rs.{{ number_format($return->selling_price, 2) }}</td>
                                            <td class="text-right fw-bold">Rs.{{ number_format($return->total_amount, 2) }}</td>
                                            <td>{{ $return->notes ?? '-' }}</td>
                                            <td class="text-center">{{ $return->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-4 pt-3 border-top" style="border-color: #233D7F;">
                            <p class="mb-0 text-muted small" style="color: #6B7280;">Thank you for your purchase!</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3" style="border-color: #233D7F; background: #F8F9FA;">
                    <button type="button"
                        class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        data-bs-dismiss="modal"
                        style="background-color: #6B7280; border-color: #6B7280; color: #FFFFFF;">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        color: #1f2937;
    }

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .transition-transform {
        transition: transform 0.2s ease;
    }

    .hover\:scale-105:hover {
        transform: scale(1.05);
    }

    .icon-shape {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-shape.icon-lg {
        width: 3rem;
        height: 3rem;
    }

    .icon-shape.icon-md {
        width: 2.5rem;
        height: 2.5rem;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th,
    .table td {
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .rounded-full {
        border-radius: 9999px;
    }

    .rounded-4 {
        border-radius: 1rem;
    }

    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .shadow-sm {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .btn-light {
        background-color: #ffffff;
        border-color: #ffffff;
        color: #1e3a8a;
    }

    .btn-light:hover {
        background-color: #f1f5f9;
        border-color: #f1f5f9;
        color: #1e3a8a;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1e40af;
        box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    .text-gray-800 {
        color: #1f2937;
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    .bg-opacity-25 {
        --bs-bg-opacity: 0.25;
    }

    .opacity-80 {
        opacity: 0.8;
    }

    /* Pagination Styles */
    .pagination-container .pagination {
        margin: 0;
    }

    .pagination-container .page-link {
        color: #1e40af;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin: 0 2px;
        padding: 8px 12px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination-container .page-link:hover {
        background-color: #eff6ff;
        border-color: #1e40af;
        color: #1d4ed8;
    }

    .pagination-container .page-item.active .page-link {
        background-color: #1e40af;
        border-color: #1e40af;
        color: white;
    }

    .pagination-container .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
    function printInvoice() {
        const modal = document.getElementById('invoiceModal');
        if (!modal) {
            alert('Please open an invoice first.');
            return;
        }

        const modalBody = modal.querySelector('#invoiceContent');
        
        // Extract customer information
        const leftCol = modalBody.querySelector('.col-md-6:first-child');
        let customerName = 'Walk-in Customer';
        let customerAddress = '';
        let customerPhone = '';

        if (leftCol) {
            const paragraphs = leftCol.querySelectorAll('p');
            if (paragraphs.length >= 3) {
                customerName = paragraphs[0].textContent.replace('Customer Name:', '').trim();
                customerAddress = paragraphs[1].textContent.replace('Address:', '').trim();
                customerPhone = paragraphs[2].textContent.replace('Phone:', '').trim();
            }
        }

        // Extract invoice information
        const rightCol = modalBody.querySelector('.col-md-6:last-child');
        let invoiceNumber = '';
        let date = '';

        if (rightCol) {
            const paragraphs = rightCol.querySelectorAll('p');
            if (paragraphs.length >= 2) {
                invoiceNumber = paragraphs[0].textContent.replace('Invoice Number:', '').trim();
                date = paragraphs[1].textContent.replace('Date:', '').trim();
            }
        }

        // Get the main items table
        const itemsTable = modalBody.querySelector('.table-bordered');
        
        // Check for return items section
        const returnSection = modalBody.querySelector('.return-items-section');
        let returnTableHTML = '';
        if (returnSection) {
            const returnTable = returnSection.querySelector('.table');
            if (returnTable) {
                returnTableHTML = `
                    <h6 style="color: #000; font-weight: bold; margin-top: 20px; margin-bottom: 10px; font-size: 12px;">
                        RETURNED ITEMS DETAILS
                    </h6>
                    ${returnTable.outerHTML}
                `;
            }
        }

        const printWindow = window.open('', '_blank', 'height=600,width=800');

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Sales Receipt - ${invoiceNumber}</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    @page { size: A4; margin: 1cm; }
                    body { 
                        font-family: 'Courier New', monospace !important; 
                        padding: 20px;
                        font-size: 12px;
                        line-height: 1.4;
                        color: #000;
                        font-weight: bold;
                    }
                    .receipt-container {
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 0;
                    }
                    .company-header {
                        text-align: center;
                        margin-bottom: 5px;
                        border-bottom: 2px solid #000;
                        padding-bottom: 15px;
                        font-weight: bold;
                    }
                    .company-name {
                        font-size: 24px;
                        font-weight: bold;
                        color: #000;
                        margin-bottom: 5px;
                    }
                    .company-address {
                        font-size: 16px;
                        color: #000;
                        margin: 3px 0;
                    }
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 2px;
                    }
                    .info-section {
                        width: 48%;
                        padding: 8px;
                    }
                    .info-section table {
                        width: 100%;
                        font-size: 11px;
                        border: none;
                    }
                    .info-section td {
                        padding: 2px 0;
                        color: #000 !important;
                        border: none;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 5px 0 20px;
                    }
                    table th, table td {
                        border: 1px solid #000;
                        padding: 3px;
                        text-align: left;
                        font-family: 'Courier New', monospace !important;
                        color: #000 !important;
                        font-size: 11px;
                    }
                    table th {
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-left { text-align: left; }
                    
                    /* Footer Styling */
                    .footer {
                        text-align: center;
                        margin-top: 30px;
                        padding-top: 20px;
                        color: #000;
                    }
                    
                    .signature-row {
                        display: flex;
                        justify-content: space-around;
                        margin-bottom: 5px;
                        text-align: center;
                    }
                    
                    .check {
                        flex: 1;
                        padding: 0 10px;
                    }
                    
                    .check .signature-line {
                        padding-bottom: 2px;
                        min-height: 30px;
                    }
                    
                    .check .label {
                        font-size: 11px;
                        font-weight: bold;
                        color: #000 !important;
                    }
                    
                    .footer p {
                        margin: 0;
                        font-size: 11px;
                        color: #000 !important;
                        font-weight: bold;
                    }
                    
                    .footer .original {
                        font-size: 12px;
                        font-weight: bold;
                        margin: 25px 0 0;
                        letter-spacing: 2px;
                    }
                    
                    .footer .bank-info {
                        font-size: 11px;
                        margin: 2px 0;
                    }
                    
                    .footer .return-policy {
                        font-size: 11px;
                        margin-top: 5px;
                        padding: 3px 0;
                        font-weight: bold;
                    }
                    
                    h3, h4, h5, h6, p, strong, span, td, th, div {
                        font-family: 'Courier New', monospace !important;
                        color: #000 !important;
                    }
                    
                    @media print { 
                        .no-print { display: none; }
                        body { padding: 10px; }
                        * { color: #000 !important; }
                    }
                </style>
            </head>
            <body>
                <div class="receipt-container">
                    <div class="company-header">
                        <div class="company-name">SAHAR LANKA</div>
                        <div class="company-address">Importers & Retailers of Genuine Spares for</div>
                        <div class="company-address">MARUTI - LEYLAND - MAHINDRA - TATA - ALTO</div>
                        <div class="company-address">Phone: 077 6718838 | Address: No. 397/3, Dunu Ela, Thihariya, Kalagedihena.</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-section">
                            <table>
                                <tr><td>Name:</td><td>${customerName}</td></tr>
                                <tr><td>Address:</td><td>${customerAddress}</td></tr>
                                <tr><td>Phone:</td><td>${customerPhone}</td></tr>
                            </table>
                        </div>
                        <div class="info-section">
                            <table>
                                <tr><td>Invoice Number:</td><td>${invoiceNumber}</td></tr>
                                <tr><td>Date:</td><td>${date}</td></tr>
                            </table>
                        </div>
                    </div>
                    
                    ${itemsTable ? itemsTable.outerHTML : ''}
                    
                    ${returnTableHTML}
                    
                    <div class="footer">
                        <div class="signature-row">
                            <div class="check">
                                <div class="signature-line">...........................</div>
                                <div class="label">Receiver's Signature</div>
                            </div>
                            <div class="check">
                                <div class="signature-line">...........................</div>
                                <div class="label">Check By</div>
                            </div>
                            <div class="check">
                                <div class="signature-line">...........................</div>
                                <div class="label">Authorized Signature</div>
                            </div>
                        </div>
                        
                        <p class="original">*****ORIGINAL*****</p>
                        <p>Please draw the cheque in favor of M.A.Z Ahamed</p>
                        <p class="bank-info">Peoples Bank Acc No: 278100102421207</p>
                        <p class="return-policy">||RETURN GOODS WILL BE ACCEPTED WITHIN 30 DAYS ONLY||</p>
                    </div>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    }

    document.addEventListener('livewire:initialized', () => {
        @this.on('openInvoiceModal', () => {
            let modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            modal.show();
        });
    });
</script>
@endpush