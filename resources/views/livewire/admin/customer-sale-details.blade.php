<div class="container-fluid py-6 bg-gray-50 min-vh-100 transition-colors duration-300">
    <div class="card border-0 ">
        <!-- Card Header -->
        <div class="card-header text-white p-5  d-flex align-items-center"
            style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); border-radius: 20px 20px 0 0;">
            <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                <i class="bi bi-people fs-4 text-white" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="mb-1 fw-bold tracking-tight text-white">Customer Sales Details</h3>
                <p class="text-white opacity-80 mb-0 text-sm">Monitor and manage your Customer Sales Records</p>
            </div>
        </div>
        <div class="card-header bg-transparent pb-4 mt-2 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <!-- Middle: Search Bar -->
            <div class="flex-grow-1 d-flex justify-content-lg">
                <div class="input-group " style="max-width: 600px;">
                    <span class="input-group-text bg-gray-100 border-0 px-3">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text"
                        class="form-control "
                        placeholder="Search customers..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off">
                </div>
            </div>

            <!-- Right: Buttons -->
            <div class="d-flex gap-2 flex-shrink-0 justify-content-lg-end">
                <button wire:click="exportToCSV"
                    class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                    aria-label="Export customer sales to CSV"
                    style="color: #fff; background-color: #233D7F; border: 1px solid #233D7F;">
                    <i class="bi bi-download me-1" aria-hidden="true"></i> Export CSV
                </button>
                <button wire:click="printData"
                    class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                    aria-label="Print customer sales details"
                    style="color: #fff; background-color: #233D7F; border: 1px solid #233D7F;">
                    <i class="bi bi-printer me-1" aria-hidden="true"></i> Print
                </button>
            </div>
        </div>


        <!-- Card Body -->
        <div class="card-body p-1  pt-5 bg-transparent">

            <!-- Sales Table or Empty State -->
            @if($customerSales->count())
            <div class="table-responsive  shadow-sm rounded-2 overflow-hidden">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-center ps-4 py-3">No</th>
                            <th class="text-center py-3">Customer Name</th>
                            <th class="text-center py-3">Invoices</th>
                            <th class="text-center py-3">Total Sales</th>
                            <th class="text-center py-3">Recieved Cheque Amount</th>
                            <th class="text-center py-3">Due Amount</th>
                            <th class="text-center py-3">Total Paid</th>
                            <th class="text-center py-3">Total Due</th>
                            <th class="text-center py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customerSales as $index => $customer)
                        <tr class="transition-all hover:bg-gray-50">
                            <td class="text-sm text-center  ps-4 py-3" >
                                {{ $customerSales->firstItem() + $index }}
                            </td>
                            <td class="text-sm text-center py-3" data-label="Customer Name">{{ $customer->name }}</td>
                            <td class="text-sm text-center py-3" >{{ $customer->invoice_count }}</td>
                            <td class="text-sm text-center py-3 text-gray-800" data-label="Total Sales">Rs.{{ number_format($customer->total_sales, 2) }}</td>
                            
                            <td class="text-sm text-center py-3">
                                <span class="badge"
                                    style="background-color: {{ $customer->received_cheque_amount > 0 ? '#f59e0b' : '#6b7280' }};
                                             color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                    Rs.{{ number_format($customer->received_cheque_amount, 2) }}
                                </span>
                            </td>
                            <td class="text-sm text-center py-3">
                                <span class="badge"
                                    style="background-color: {{ $customer->total_due > 0 ? '#ef4444' : '#22c55e' }};
                                             color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                    Rs.{{ number_format($customer->total_due, 2) }}
                                </span>
                            </td>
                            <td class="text-sm text-center py-3">
                                <span class="badge"
                                    style="background-color: {{ $customer->total_sales - ($customer->total_due + $customer->received_cheque_amount) > 0 ? '#22c55e' : '#ef4444' }};
                                             color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                    Rs.{{ number_format($customer->total_sales - ($customer->total_due + $customer->received_cheque_amount), 2) }}
                                </span>
                            </td>
                            <td class="text-sm text-center py-3">
                                <span class="badge"
                                    style="background-color: {{ $customer->total_due > 0 ? '#ef4444' : '#22c55e' }};
                                             color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                    Rs.{{ number_format(($customer->total_due + $customer->received_cheque_amount), 2) }}
                                </span>
                            </td>
                            <td class="text-sm text-center py-3">
                                <button wire:click="viewSaleDetails({{ $customer->customer_id }})"
                                    class="btn text-primary btn-sm"
                                    aria-label="View customer sales details">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-6">
                                <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                                    <i class="bi bi-person text-gray-600 fs-3"></i>
                                </div>
                                <h5 class="text-gray-600 fw-normal">No Customer Sales Found</h5>
                                <p class="text-sm text-gray-500 mb-0">No matching results found for the current search.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $customerSales->links('livewire.custom-pagination') }}
            </div>
            @else
            <div class="text-center py-6">
                <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                    <i class="bi bi-person text-gray-600 fs-3"></i>
                </div>
                <h5 class="text-gray-600 fw-normal">No Customer Sales Data Found</h5>
                <p class="text-sm text-gray-500 mb-0">All customer sales records are empty.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Sale Details Modal -->
    <div wire:ignore.self class="modal fade" id="customerSalesModal" tabindex="-1" aria-labelledby="customerSalesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header text-white p-4"
                    style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <h5 class="modal-title fw-bold tracking-tight" id="customerSalesModalLabel">
                        <i class="bi bi-person me-2"></i>
                        {{ $modalData ? $modalData['customer']->name . '\'s Sales History' : 'Sales History' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    @if($modalData)
                    <!-- Customer Information Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2 text-sm text-gray-800"><strong>Name:</strong> {{ $modalData['customer']->name }}</p>
                                    <p class="mb-2 text-sm text-gray-800"><strong>Email:</strong> {{ $modalData['customer']->email }}</p>
                                    <p class="mb-2 text-sm text-gray-800"><strong>Phone:</strong> {{ $modalData['customer']->phone }}</p>
                                    <p class="mb-2 text-sm text-gray-800"><strong>Type:</strong>
                                        <span class="badge"
                                            style="background-color: {{ $modalData['customer']->type == 'wholesale' ? '#1e40af' : '#0ea5e9' }};
                                                     color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                            {{ ucfirst($modalData['customer']->type) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2 text-sm text-gray-800"><strong>Business Name:</strong> {{ $modalData['customer']->business_name ?? 'N/A' }}</p>
                                    <p class="mb-2 text-sm text-gray-800"><strong>Address:</strong> {{ $modalData['customer']->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-4">
                                    <h6 class="text-sm fw-semibold text-gray-800 mb-2" style="color: #1e3a8a;">Total Sales Amount</h6>
                                    <h3 class="fw-bold text-gray-800">Rs.{{ number_format($modalData['salesSummary']->total_amount, 2) }}</h3>
                                    <p class="text-sm text-gray-500 mb-0">Across {{ count($modalData['invoices']) }} invoices</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-4">
                                    <h6 class="text-sm fw-semibold text-gray-800 mb-2" style="color: #22c55e;">Amount Paid</h6>
                                    <h3 class="fw-bold" style="color: #22c55e;">Rs.{{ number_format($modalData['salesSummary']->total_paid, 2) }}</h3>
                                    <p class="text-sm text-gray-500 mb-0">
                                        {{ round(($modalData['salesSummary']->total_paid / $modalData['salesSummary']->total_amount) * 100) }}% of total
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-4">
                                    <h6 class="text-sm fw-semibold text-gray-800 mb-2" style="color: #ef4444;">Amount Due</h6>
                                    <h3 class="fw-bold" style="color: #ef4444;">Rs.{{ number_format($modalData['salesSummary']->total_due, 2) }}</h3>
                                    <p class="text-sm text-gray-500 mb-0">
                                        {{ round(($modalData['salesSummary']->total_due / $modalData['salesSummary']->total_amount) * 100) }}% outstanding
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Progress Bar -->
                    @php
                    $paymentPercentage = $modalData['salesSummary']->total_amount > 0
                    ? round(($modalData['salesSummary']->total_paid / $modalData['salesSummary']->total_amount) * 100)
                    : 0;
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <p class="fw-bold mb-2 text-sm text-gray-800" style="color: #1e3a8a;">Payment Progress</p>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        style="background-color: #1e40af; width: {{ $paymentPercentage }}%;"
                                        aria-valuenow="{{ $paymentPercentage }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="ms-3 fw-bold text-sm text-gray-800">{{ $paymentPercentage }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product-wise Sales Table -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header p-4" style="background-color: #eff6ff;">
                            <h5 class="card-title mb-0 fw-bold text-sm" style="color: #1e3a8a;">Sales & Payment History</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="position-sticky top-0" style="background-color: #eff6ff;">
                                        <tr>
                                            <th class="ps-4 text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">#</th>
                                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Description</th>
                                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Date</th>
                                            <th class="text-uppercase text-xs fw-semibold py-3 text-end" style="color: #1e3a8a;">Sales Amount</th>
                                            <th class="text-uppercase text-xs fw-semibold py-3 text-end" style="color: #1e3a8a;">Received Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($modalData['displayData'] as $item)
                                        <tr class="border-bottom transition-all hover:bg-[#f1f5f9] {{ $loop->iteration % 2 == 0 ? 'bg-[#f9fafb]' : '' }} {{ $item['type'] === 'total' ? 'fw-bold bg-light border-top-2 border-primary' : '' }} {{ $item['type'] === 'due' ? 'fw-bold bg-warning bg-opacity-10 border-top-2 border-danger' : '' }}">
                                            <td class="ps-4 text-center text-sm text-gray-800" data-label="#">{{ $loop->iteration }}</td>
                                            <td class="text-sm text-gray-800" data-label="Description">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        @if($item['type'] !== 'total' && $item['type'] !== 'due')
                                                        <span class="fw-bold {{ $item['type'] === 'payment' ? ($item['payment_status'] ?? 'Paid' === 'Paid' ? 'text-success' : 'text-warning') : 'text-gray-800' }}">
                                                            {{ $item['description'] }}
                                                        </span>
                                                        @if($item['type'] === 'payment')
                                                        @elseif($item['type'] === 'invoice')
                                                        @endif
                                                        @else
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <i class="bi bi-calculator {{ $item['type'] === 'due' ? 'text-danger' : 'text-primary' }} me-2"></i>
                                                            <span class="fw-bold {{ $item['type'] === 'due' ? 'text-danger' : 'text-primary' }}">
                                                                {{ $item['description'] }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm text-gray-600 text-center" data-label="Date">
                                                @if($item['type'] !== 'total' && $item['type'] !== 'due')
                                                    {{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('d M Y') : '-' }}
                                                @endif
                                            </td>
                                            <td class="text-end text-sm {{ $item['sales_amount'] > 0 && $item['type'] !== 'due' ? 'text-gray-800 fw-bold' : 'text-gray-400' }}" data-label="Sales Amount">
                                                @if($item['sales_amount'] > 0 && $item['type'] !== 'due')
                                                    Rs.{{ number_format($item['sales_amount'], 2) }}
                                                @else
                                                    
                                                @endif
                                            </td>
                                            <td class="text-end text-sm {{ $item['received_amount'] > 0 || $item['type'] === 'due' ? 'text-gray-800 fw-bold' : 'text-gray-400' }}" data-label="Received Amount">
                                                @if($item['type'] === 'due' && $item['sales_amount'] > 0)
                                                    <span style="color: #ef4444;">Rs.{{ number_format($item['sales_amount'], 2) }}</span>
                                                @elseif($item['received_amount'] > 0)
                                                    Rs.{{ number_format($item['received_amount'], 2) }}
                                                @else
                                                    
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-6">
                                                <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                                                    <i class="bi bi-box-seam text-gray-600 fs-3"></i>
                                                </div>
                                                <h5 class="text-gray-600 fw-normal">No Sales Data Found</h5>
                                                <p class="text-sm text-gray-500 mb-0">No sales or payment data available.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                            <i class="bi bi-person text-gray-600 fs-3"></i>
                        </div>
                        <h5 class="text-gray-600 fw-normal">Loading Customer Sales Data</h5>
                        <p class="text-sm text-gray-500 mb-0">Please wait while data is being loaded...</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                        onclick="printTableOnly()" aria-label="Print sales & payment history table">
                        <i class="bi bi-printer me-1"></i> Print Details
                    </button>
                    <button type="button" class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                        data-bs-dismiss="modal" aria-label="Close modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script>
    // Toast notifications for Livewire events
    document.addEventListener('livewire:initialized', () => {
        @this.on('showToast', ({
            type,
            message
        }) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1f2937',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    });

    // Modal opening
    window.addEventListener('open-customer-sale-details-modal', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('customerSalesModal'));
            modal.show();
        }, 500);
    });

    // Main table print function
    document.addEventListener('livewire:initialized', function() {
        Livewire.on('print-customer-table', function() {
            console.log('Print function triggered');

            // Try multiple selectors to find the table
            let tableElement = document.querySelector('.table.table-sm');
            if (!tableElement) {
                tableElement = document.querySelector('table.table-sm');
            }
            if (!tableElement) {
                tableElement = document.querySelector('.table-responsive table');
            }
            if (!tableElement) {
                tableElement = document.querySelector('table');
            }

            if (!tableElement) {
                Swal.fire({
                    icon: 'error',
                    title: 'Table not found',
                    text: 'Could not locate the customer sales table.',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            console.log('Table found:', tableElement);

            const clonedTable = tableElement.cloneNode(true);
            const actionColumnIndex = 8; // Action column at index 8

            // Remove action column from header
            const headerRow = clonedTable.querySelector('thead tr');
            if (headerRow) {
                const headerCells = headerRow.querySelectorAll('th');
                if (headerCells.length > actionColumnIndex) {
                    headerCells[actionColumnIndex].remove();
                }
            }

            // Remove action column from all rows
            const rows = clonedTable.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > actionColumnIndex) {
                    cells[actionColumnIndex].remove();
                }
            });

            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Customer Sales Report</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        @page { size: landscape; margin: 1cm; }
                        body {
                            font-family: 'Inter', sans-serif;
                            font-size: 14px;
                            color: #000000;
                            margin: 0;
                            padding: 20px;
                            display: flex;
                            flex-direction: column;
                            min-height: 100vh;
                        }
                        .print-container {
                            max-width: 1200px;
                            margin: 0 auto;
                            flex: 1;
                            display: flex;
                            flex-direction: column;
                        }
                        .print-header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #000000; text-align: center; }
                        .print-header h2 { color: #000000; font-weight: 700; letter-spacing: -0.025em; }
                        .table-responsive { flex: 1; }
                        .print-footer {
                            margin-top: auto;
                            padding-top: 15px;
                            border-top: 1px solid #000000;
                            text-align: center;
                            font-size: 12px;
                            color: #000000;
                            position: fixed;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            background: white;
                            padding: 10px 20px;
                        }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td {
                            border: none;
                            padding: 8px;
                            text-align: center;
                            vertical-align: middle;
                            color: #000000;
                            background: none;
                        }
                        th { font-weight: 600; text-transform: uppercase; font-size: 12px; }
                        .badge {
                            padding: 0;
                            border-radius: 0;
                            font-size: inherit;
                            font-weight: inherit;
                            color: #000000;
                            background: none;
                            border: none;
                        }
                        @media print {
                            * {
                                -webkit-print-color-adjust: exact !important;
                                color-adjust: exact !important;
                                color: #000000 !important;
                            }
                            body {
                                -webkit-print-color-adjust: exact;
                                color-adjust: exact;
                                color: #000000 !important;
                            }
                            .print-footer {
                                position: fixed !important;
                                bottom: 0 !important;
                                background: white !important;
                            }
                            .badge {
                                background: none !important;
                                color: #000000 !important;
                                padding: 0 !important;
                                border-radius: 0 !important;
                                border: none !important;
                                font-weight: inherit !important;
                                font-size: inherit !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        <div class="print-header">
                            <h2>Customer Sales Report</h2>
                            <p>Generated on: ${new Date().toLocaleString('en-US', { timeZone: 'Asia/Colombo' })}</p>
                        </div>
                        <div class="table-responsive">
                            ${clonedTable.outerHTML}
                        </div>
                        <div class="print-footer">
                            <p>SAHAR LANKA | No. 397/3, Dunu Ela, Thihariya, Kalagedihena. | Phone: 077 6718838</p>
                        </div>
                    </div>
                </body>
                </html>
            `;

            try {
                const printWindow = window.open('', '_blank', 'width=1000,height=700');
                if (!printWindow) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Popup blocked',
                        text: 'Please allow popups for this site.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return;
                }

                printWindow.document.open();
                printWindow.document.write(htmlContent);
                printWindow.document.close();

                printWindow.onload = function() {
                    printWindow.print();
                    setTimeout(() => printWindow.close(), 1000);
                };
            } catch (error) {
                console.error('Print error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Print failed',
                    text: 'An error occurred while printing.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Modal print function - Print only the table
    function printTableOnly() {
        const customerName = document.querySelector('#customerSalesModalLabel')?.innerText?.trim();
        const modalBody = document.querySelector('.modal-body');

        if (!modalBody) {
            Swal.fire({
                icon: 'error',
                title: 'Modal not found',
                text: 'Please wait for the modal to load completely.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const table = modalBody.querySelector('.table');

        if (!table) {
            Swal.fire({
                icon: 'warning',
                title: 'No table data',
                text: 'No sales data available to print.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>${customerName || 'Customer'} - Sales & Payment History</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    @page { size: landscape; margin: 1cm; }
                    body {
                        font-family: 'Inter', sans-serif;
                        padding: 20px;
                        font-size: 14px;
                        color: #000000;
                        margin: 0;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 1px solid #000000;
                    }
                    .print-header h2 {
                        color: #000000;
                        font-weight: 700;
                        letter-spacing: -0.025em;
                        margin: 0;
                    }
                    .print-footer {
                        text-align: center;
                        margin-top: 20px;
                        padding-top: 15px;
                        border-top: 1px solid #000000;
                        font-size: 12px;
                        color: #000000;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: none;
                        padding: 12px;
                        text-align: left;
                        vertical-align: middle;
                        color: #000000;
                        background: none;
                    }
                    th {
                        font-weight: 600;
                        text-transform: uppercase;
                        font-size: 12px;
                    }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: 600; }
                    .badge {
                        padding: 0;
                        border-radius: 0;
                        font-size: inherit;
                        font-weight: inherit;
                        color: #000000;
                        background: none;
                        border: none;
                        display: inline;
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>${customerName || 'Customer Sales History'}</h2>
                    <p>Sales & Payment History</p>
                    <small>Generated on: ${new Date().toLocaleString('en-US', { timeZone: 'Asia/Colombo' })}</small>
                </div>
                <div class="table-responsive">
                    ${table.outerHTML}
                </div>
                <div class="print-footer">
                    <p>SAHAR LANKA | No. 397/3, Dunu Ela, Thihariya, Kalagedihena. | Phone: 077 6718838</p>
                </div>
            </body>
            </html>
        `;

        try {
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            if (!printWindow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Popup blocked',
                    text: 'Please allow popups for this site to enable printing.',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.print();
                setTimeout(() => {
                    printWindow.close();
                }, 1000);
            };
        } catch (error) {
            console.error('Print error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Print failed',
                text: 'An error occurred while preparing the print content.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }
</script>
@endpush