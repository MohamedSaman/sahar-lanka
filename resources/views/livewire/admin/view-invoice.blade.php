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
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header text-white p-4" style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-receipt me-2"></i> Invoice Details - #{{ $saleDetails['sale']->invoice_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row mb-4">
                        <!-- Customer Info -->
                        <div class="col-md-5">
                            <h6 class="fw-bold mb-3" style="color: #1e40af;">Customer Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Name:</td>
                                    <td class="fw-semibold">{{ $saleDetails['sale']->customer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>{{ $saleDetails['sale']->customer->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Invoice Info -->
                        <div class="col-md-5">
                            <h6 class="fw-bold mb-3" style="color: #1e40af;">Invoice Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Invoice Number:</td>
                                    <td class="fw-semibold">#{{ $saleDetails['sale']->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date:</td>
                                    <td>{{ $saleDetails['sale']->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Cashier:</td>
                                    <td>{{ $saleDetails['sale']->user->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <h6 class="fw-bold mb-3" style="color: #1e40af;">Items Purchased</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead style="background-color: #eff6ff;">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($saleDetails['items'] as $item)
                                <tr>
                                    <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if($item->product && $item->product->customer_field)
                                        {{ $item->product->customer_field['Size'] ?? '-' }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rs.{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">Rs.{{ number_format($item->discount, 2) }}</td>
                                    <td class="text-end fw-bold">Rs.{{ number_format(($item->quantity * $item->price) - $item->discount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Return Items -->
                    @if($saleDetails['returnItems']->count() > 0)
                    <h6 class="fw-bold mb-3 text-danger">
                        <i class="bi bi-arrow-return-left me-2"></i>Returned Items
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                    <th>Notes</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($saleDetails['returnItems'] as $return)
                                <tr>
                                    <td>{{ $return->product->product_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $return->return_quantity }}</td>
                                    <td class="text-end">Rs.{{ number_format($return->selling_price, 2) }}</td>
                                    <td class="text-end fw-bold">Rs.{{ number_format($return->total_amount, 2) }}</td>
                                    <td>{{ $return->notes ?? '-' }}</td>
                                    <td>{{ $return->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-danger">
                                    <td colspan="3" class="text-end fw-bold">Total Returns:</td>
                                    <td class="text-end fw-bold">Rs.{{ number_format($saleDetails['totalReturnAmount'], 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Payment Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3" style="color: #1e40af;">Payment Summary</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted">Subtotal:</td>
                                            <td class="text-end">Rs.{{ number_format($saleDetails['sale']->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Discount:</td>
                                            <td class="text-end text-danger">-Rs.{{ number_format($saleDetails['sale']->discount_amount, 2) }}</td>
                                        </tr>
                                        @if($saleDetails['totalReturnAmount'] > 0)
                                        <tr>
                                            <td class="text-muted">Returns:</td>
                                            <td class="text-end text-danger">-Rs.{{ number_format($saleDetails['totalReturnAmount'], 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="border-top">
                                            <td class="fw-bold fs-5" style="color: #1e40af;">Grand Total:</td>
                                            <td class="text-end fw-bold fs-5" style="color: #1e40af;">
                                                Rs.{{ number_format($saleDetails['adjustedGrandTotal'], 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printInvoice()" style="background-color: #1e40af; border-color: #1e40af;">
                        <i class="bi bi-printer me-2"></i>Print Invoice
                    </button>
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

        const invoiceNumber = modal.querySelector('.modal-title').textContent.split('#')[1]?.trim() || '';
        const modalBody = modal.querySelector('.modal-body');

        const customerSection = modalBody.querySelectorAll('.col-md-5')[0];
        const invoiceSection = modalBody.querySelectorAll('.col-md-5')[1];

        const itemsTable = modalBody.querySelector('.table-bordered');

        const returnSection = modalBody.querySelector('.text-danger')?.closest('div');
        let returnHTML = '';
        if (returnSection) {
            const returnTable = returnSection.querySelector('.table-bordered');
            if (returnTable) {
                returnHTML = `
                    <h6 style="color: #dc3545; font-weight: bold; margin-top: 20px; margin-bottom: 10px;">
                        <i class="bi bi-arrow-return-left"></i> Returned Items
                    </h6>
                    ${returnTable.outerHTML}
                `;
            }
        }

        const summaryCard = modalBody.querySelector('.col-md-6.offset-md-6 .card-body');
        let summaryHTML = '';
        if (summaryCard) {
            const summaryTable = summaryCard.querySelector('table');
            if (summaryTable) {
                const rows = summaryTable.querySelectorAll('tr');
                rows.forEach(row => {
                    const label = row.querySelector('td:first-child')?.textContent.trim() || '';
                    const value = row.querySelector('td:last-child')?.textContent.trim() || '';
                    const isTotal = row.classList.contains('border-top');
                    summaryHTML += `
                        <div class="summary-row ${isTotal ? 'total' : ''}">
                            <span>${label}</span>
                            <span>${value}</span>
                        </div>
                    `;
                });
            }
        }

        let printFrame = document.getElementById('printFrame');
        if (!printFrame) {
            printFrame = document.createElement('iframe');
            printFrame.id = 'printFrame';
            printFrame.style.position = 'absolute';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = 'none';
            document.body.appendChild(printFrame);
        }

        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Invoice ${invoiceNumber}</title>
                <meta charset="UTF-8">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    @page { size: A4; margin: 1cm; }
                    body {
                        font-family: 'Courier New', monospace !important;
                        font-size: 12px;
                        line-height: 1.4;
                        padding: 20px;
                    }
                    .company-header {
                        text-align: center;
                        margin-bottom: 20px;
                        border-bottom: 2px solid #000;
                        padding-bottom: 15px;
                    }
                    .company-name {
                        font-size: 20px;
                        font-weight: bold;
                        color: #000;
                        margin: 0;
                    }
                    .company-address {
                        font-size: 11px;
                        color: #000;
                        margin: 5px 0;
                    }
                    .receipt-title {
                        font-size: 18px;
                        font-weight: bold;
                        color: #000;
                        text-align: center;
                        margin: 15px 0;
                        border-bottom: 2px solid #000;
                        padding-bottom: 10px;
                    }
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 20px;
                    }
                    .info-section {
                        width: 48%;
                        border: 1px solid #000;
                        padding: 10px;
                    }
                    .info-section h6 {
                        color: #000;
                        font-weight: bold;
                        border-bottom: 1px solid #000;
                        padding-bottom: 5px;
                        margin-bottom: 10px;
                        font-size: 13px;
                    }
                    .info-section table {
                        width: 100%;
                        font-size: 12px;
                    }
                    .info-section td {
                        padding: 3px 0;
                        color: #000;
                    }
                    .items-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    .items-table th {
                        background-color: #f0f0f0;
                        color: #000;
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                        font-size: 12px;
                        font-weight: bold;
                    }
                    .items-table td {
                        padding: 6px 8px;
                        border: 1px solid #000;
                        font-size: 12px;
                        color: #000;
                    }
                    .summary-section {
                        display: flex;
                        justify-content: flex-end;
                        margin-top: 20px;
                    }
                    .summary-box {
                        width: 400px;
                        border: 2px solid #000;
                        padding: 15px;
                    }
                    .summary-row {
                        display: flex;
                        justify-content: space-between;
                        padding: 5px 0;
                        border-bottom: 1px solid #000;
                        color: #000;
                    }
                    .summary-row.total {
                        font-size: 16px;
                        font-weight: bold;
                        color: #000;
                        border-top: 2px solid #000;
                        margin-top: 10px;
                        padding-top: 10px;
                        border-bottom: none;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 40px;
                        padding-top: 20px;
                        border-top: 1px solid #000;
                        font-size: 11px;
                        color: #000;
                        clear: both;
                    }
                    h3, h4, h5, h6, p, strong, span, td, th, div {
                        margin: 0;
                        padding: 0;
                        font-family: 'Courier New', monospace !important;
                        color: #000 !important;
                    }
                    .text-muted { color: #000 !important; }
                    .table { border-collapse: collapse; }
                    .table th, .table td {
                        border: 1px solid #000;
                        padding: 8px;
                        color: #000 !important;
                    }
                    .table-light, .table thead th {
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .badge {
                        display: inline-block;
                        padding: 3px 8px;
                        border: 1px solid #000 !important;
                        background-color: transparent !important;
                        font-size: 10px;
                        font-weight: bold;
                        color: #000 !important;
                    }
                    i { display: none !important; }
                    @media print {
                        body { padding: 10px; }
                        * { color: #000 !important; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="company-header">
                    <div class="company-name">SAHAR LANKA</div>
                    <div class="company-address">Importers & Retailers of Genuine Spares for</div>
                    <div class="company-address">MARUTI-LEYLAND - MAHINDRA-TATA-ALTO</div>
                    <div class="company-address">Phone: 077 6718838 | Address: No. 397/3, Dunu Ela, Thihariya, Kalagedihena.</div>
                </div>
                
                <div class="receipt-title">SALES INVOICE</div>
                
                <div class="info-row">
                    <div class="info-section">
                        ${customerSection ? customerSection.innerHTML : ''}
                    </div>
                    <div class="info-section">
                        ${invoiceSection ? invoiceSection.innerHTML : ''}
                    </div>
                </div>
                
                <h6 style="color: #9d1c20; font-weight: bold; margin-top: 20px; margin-bottom: 10px;">Items Purchased</h6>
                ${itemsTable ? itemsTable.outerHTML : ''}
                
                ${returnHTML}
                
                <div class="summary-section">
                    <div class="summary-box">
                        <h6 style="color: #9d1c20; font-weight: bold; margin-bottom: 15px;">Payment Summary</h6>
                        ${summaryHTML}
                    </div>
                </div>
                
                <div class="footer">
                    <p><strong>Thank you for your purchase!</strong></p>
                </div>
            </body>
            </html>
        `;

        const frameDoc = printFrame.contentWindow || printFrame.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write(htmlContent);
        frameDoc.document.close();

        setTimeout(() => {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();
        }, 500);
    }

    document.addEventListener('livewire:initialized', () => {
        @this.on('openInvoiceModal', () => {
            let modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            modal.show();
        });
    });
</script>
@endpush