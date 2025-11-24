<div>
    @push('styles')
    <style>
        .search-results-container {
            z-index: 1050;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        input[type="number"].is-invalid {
            border-color: #dc3545;
        }

        .tracking-tight {
            letter-spacing: -0.025em;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover\:shadow:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hover\:bg-gray-50:hover {
            background-color: #F8F9FA;
        }

        .table-bordered {
            border-collapse: collapse;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #233D7F;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .discount-type-btn {
            padding: 8px 16px;
            border: 2px solid #dee2e6;
            background: white;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .discount-type-btn:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
            transform: translateY(-1px);
        }

        .discount-type-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: #667eea !important;
            color: white !important;
            font-weight: bold;
        }

        .discount-type-btn.active i {
            color: white !important;
        }

        .discount-type-btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .discount-type-btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            border-left: none;
        }

        @media (max-width: 767.98px) {
            .table {
                font-size: 0.875rem;
            }

            .table td:nth-child(3),
            .table th:nth-child(3),
            .table td:nth-child(6),
            .table th:nth-child(6) {
                display: none;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                justify-content: center;
            }
        }
    </style>
    @endpush

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Billing System</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mx-auto">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        placeholder="Search by code, model, barcode, brand or name..."
                                        wire:model.live="search" autocomplete="off">
                                </div>

                                @if ($search && count($searchResults) > 0)
                                <div class="search-results-container position-absolute mt-1 w-50 bg-white shadow-lg rounded"
                                    style="max-height: 350px; overflow-y: auto; z-index: 1000;">
                                    @foreach ($searchResults as $result)
                                    @if($result->status== 'Available')
                                    <div class="search-result-item p-2 border-bottom d-flex align-items-center"
                                        wire:key="result-{{ $result->id }}">

                                        <div class="product-image me-3" style="min-width: 60px;">
                                            @if ($result->image)
                                            <img src="{{ asset('public/storage/' . $result->image) }}"
                                                alt="{{ $result->name }}" class="img-fluid rounded"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                            <div
                                                style="width:30px;height:30px;background-color:#f3f4f6;border-radius:0.5rem;display:flex;align-items:center;justify-content:center; margin:0 auto;">
                                                <i class="bi bi-box-seam text-gray-600"></i>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="product-info flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1 fw-bold">{{ $result->product_name ?? 'Unnamed Product'
                                                    }}</h6>
                                                <div class="text-end">
                                                    <span class="badge bg-success">
                                                        Rs.{{ $result->discount_price ?: $result->selling_price ?? '-'
                                                        }}
                                                    </span>
                                                    <span
                                                        class="badge {{ $result->stock_quantity <= 5 ? 'bg-warning text-dark' : 'bg-info' }}">
                                                        Available: {{ $result->stock_quantity ?? 0 }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <span class="me-2">Code: {{ $result->product_code ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="ps-2">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="addToCart({{ $result->id }})" {{ $result->stock_quantity <=
                                                    0 ? 'disabled' : '' }}>
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>

                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                @elseif($search && count($searchResults) == 0)
                                <div
                                    class="search-results-container position-absolute mt-1 w-50 bg-white shadow-lg rounded p-3">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-search fa-2x mb-2"></i>
                                        <p>No products found matching "{{ $search }}"</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Product</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Unit Price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Quantity</th>
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Discount</th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cart as $id => $item)
                                    <tr wire:key="cart-{{ $id }}">
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if ($item['image'])
                                                    <img src="{{ asset('public/storage/' . $item['image']) }}"
                                                        class="avatar avatar-sm me-3 rounded" alt="{{ $item['name'] }}"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                    <div
                                                        style="width:40px;height:40px;background-color:#f3f4f6;border-radius:0.5rem;display:flex;align-items:center;justify-content:center; margin-right: auto;">
                                                        <i class="bi bi-box-seam text-gray-600"></i>
                                                    </div>
                                                    @endif

                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $item['name'] }}</h6>
                                                    <small class="text-xs text-secondary mb-0">{{ $item['code'] ?? 'N/A'
                                                        }} |
                                                        {{ $item['brand'] ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" class="form-control form-control-sm"
                                                    value="{{ $prices[$id] }}" min="0" step="0.01"
                                                    wire:model.blur="prices.{{ $id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 100px;">
                                                <input type="number"
                                                    class="form-control form-control-sm text-center quantity-input"
                                                    value="{{ $quantities[$id] }}" min="1"
                                                    max="{{ $item['stock_quantity'] }}"
                                                    wire:model.blur="quantities.{{ $id }}">
                                                <small class="text-muted">Max: {{ $item['stock_quantity'] }}</small>
                                            </div>
                                        </td>

                                        <!-- <td>
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" class="form-control form-control-sm"
                                                    value="{{ $discounts[$id] ?? 0 }}" min="0"
                                                    max="{{ $prices[$id] }}" step="0.01"
                                                    wire:model.blur="discounts.{{ $id }}">
                                            </div>
                                        </td> -->
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rs.{{ number_format(($prices[$id]) *
                                                $quantities[$id] - ($discounts[$id] ?? 0) * $quantities[$id], 2) }}
                                            </p>
                                        </td>
                                        <td>
                                            <button class="btn btn-link btn-sm text-danger rounded-circle "
                                                wire:click="removeFromCart({{ $id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                                <p>Your cart is empty. Search and add products to create a bill.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header pb-0 bg-primary">
                                        <h6 class="text-white">Customer & Payment Information</h6>
                                    </div>
                                    <div class="card-body" style="height: 500px; overflow-y: auto;">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Select Customer</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="input-group flex-grow-1">
                                                    <span class="input-group-text">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                    <select class="form-select" wire:model="customerId">
                                                        <option value="">-- Select a customer --</option>
                                                        @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->name }} ({{$customer->phone}})
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <button class="btn btn-primary d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                                    <i class="bi bi-plus-circle me-1"></i>ADD
                                                </button>
                                            </div>
                                            @error('customerId')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Payment Type</label>
                                            <div class="d-flex">
                                                <div class="form-check me-4">
                                                    <input class="form-check-input" type="radio" name="paymentType"
                                                        id="partialPayment" value="partial"
                                                        wire:model.live="paymentType">
                                                    <label class="form-check-label" for="partialPayment">
                                                        <span class="badge bg-warning me-1">
                                                            <i class="fas fa-percentage me-1"></i>
                                                        </span>
                                                        Credit
                                                    </label>
                                                </div>
                                                <div class="form-check ">
                                                    <input class="form-check-input" type="radio" name="paymentType"
                                                        id="fullPayment" value="full" wire:model.live="paymentType"
                                                        checked>
                                                    <label class="form-check-label" for="fullPayment">
                                                        <span class="badge bg-success me-1">
                                                            <i class="fas fa-money-bill me-1"></i>
                                                        </span>
                                                        Cash
                                                    </label>
                                                </div>
                                                
                                            </div>
                                        </div>

                                        @if ($paymentType == 'full')
                                        @else
                                        <div class="card mb-3 border">
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold mb-3">
                                                    <i class="fas fa-money-bill-wave me-2"></i>Cash Payment
                                                </h6>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Cash Amount</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rs.</span>
                                                        <input type="number" class="form-control"
                                                            placeholder="Enter cash amount (optional)" wire:model="cashAmount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3 border">
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold mb-3">
                                                    <i class="fas fa-money-check-alt me-2"></i>Cheque Payments
                                                </h6>

                                                <div class="card bg-light mb-3">
                                                    <div class="card-header bg-white">
                                                        <h6 class="mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i>Add New Cheque</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <form wire:submit.prevent="addCheque" id="chequeForm">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">
                                                                        <i class="fas fa-hashtag me-1"></i>Cheque Number <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter cheque number"
                                                                        wire:model="newCheque.number"
                                                                        required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">
                                                                        <i class="fas fa-university me-1"></i>Bank Name <span class="text-danger">*</span>
                                                                    </label>
                                                                    <select class="form-select" wire:model="newCheque.bank" required>
                                                                        <option value="">-- Select a bank --</option>
                                                                        @foreach($banks as $bank)
                                                                        <option value="{{ $bank }}">{{ $bank }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                    @error('newCheque.bank')
                                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">
                                                                        <i class="fas fa-calendar-alt me-1"></i>Cheque Date <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="date" class="form-control"
                                                                        wire:model="newCheque.date"
                                                                        min="{{ date('Y-m-d') }}"
                                                                        required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">
                                                                        <i class="fas fa-money-bill-wave me-1"></i>Amount (Rs.) <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">Rs.</span>
                                                                        <input type="number" class="form-control"
                                                                            placeholder="0.00"
                                                                            wire:model="newCheque.amount"
                                                                            min="0"
                                                                            step="0.01"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                                                <div class="text-muted small">
                                                                    <i class="fas fa-info-circle me-1"></i>All fields marked with <span class="text-danger">*</span> are required
                                                                </div>
                                                                <div>
                                                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="resetChequeForm()">
                                                                        <i class="fas fa-undo me-1"></i>Reset
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="fas fa-plus me-1"></i> Add Cheque
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                @if(!empty($cheques))
                                                <div class="table-responsive mt-3">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="fas fa-list-check me-2"></i>Added Cheques
                                                                <span class="badge bg-light text-primary ms-2">{{ count($cheques) }}</span>
                                                            </h6>
                                                            <small class="text-white-50">
                                                                <i class="fas fa-info-circle me-1"></i>Total: Rs.{{ number_format(collect($cheques)->sum('amount'), 2) }}
                                                            </small>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover mb-0">
                                                                    <thead class="table-light">
                                                                        <tr class="text-center">
                                                                            <th class="border-0 fw-bold text-primary" style="width: 60px;">
                                                                                <i class="fas fa-hashtag me-1"></i>#
                                                                            </th>
                                                                            <th class="border-0 fw-bold text-primary">
                                                                                <i class="fas fa-receipt me-1"></i>Cheque Details
                                                                            </th>
                                                                            <th class="border-0 fw-bold text-primary" style="width: 120px;">
                                                                                <i class="fas fa-calendar-alt me-1"></i>Date
                                                                            </th>
                                                                            <th class="border-0 fw-bold text-primary" style="width: 120px;">
                                                                                <i class="fas fa-money-bill-wave me-1"></i>Amount
                                                                            </th>
                                                                            <th class="border-0 fw-bold text-primary" style="width: 80px;">
                                                                                <i class="fas fa-cogs me-1"></i>Action
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($cheques as $index => $cheque)
                                                                        <tr wire:key="cheque-{{ $index }}" class="text-center align-middle">
                                                                            <td class="fw-bold text-muted">
                                                                                {{ $index + 1 }}
                                                                            </td>
                                                                            <td class="text-start">
                                                                                <div class="d-flex flex-column">
                                                                                    <div class="fw-bold text-dark mb-1">
                                                                                        <i class="fas fa-receipt text-primary me-1"></i>
                                                                                        {{ $cheque['number'] }}
                                                                                    </div>
                                                                                    <div class="small text-muted">
                                                                                        <i class="fas fa-university text-secondary me-1"></i>
                                                                                        {{ $cheque['bank'] }}
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-light text-dark border">
                                                                                    <i class="fas fa-calendar-day me-1"></i>
                                                                                    {{ \Carbon\Carbon::parse($cheque['date'])->format('M d, Y') }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-success text-white fw-bold fs-6">
                                                                                    Rs.{{ number_format($cheque['amount'], 2) }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <button class="btn btn-outline-danger btn-sm"
                                                                                    wire:click.prevent="removeCheque({{ $index }})"
                                                                                    title="Remove Cheque">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot class="table-light">
                                                                        <tr class="text-center fw-bold">
                                                                            <td colspan="3" class="text-end border-0">
                                                                                <i class="bi bi-calculator me-2"></i>Total Cheques:
                                                                            </td>
                                                                            <td class="border-0">
                                                                                <span class="badge bg-primary text-white fs-6">
                                                                                    Rs.{{ number_format(collect($cheques)->sum('amount'), 2) }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="border-0"></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Notes</label>
                                            <textarea class="form-control" rows="3"
                                                placeholder="Add any notes about this sale"
                                                wire:model="saleNotes"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>Rs.{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Item Discounts:</span>
                                        <span class="text-danger">-Rs.{{ number_format($totalDiscount, 2) }}</span>
                                    </div>

                                    <!-- Overall Discount Section with Icon Buttons -->
                                    <div class="card bg-light p-3 mb-3 mt-3">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-tag me-2"></i>Additional Discount
                                        </h6>

                                        <!-- Discount Value Input with Type Icons -->
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Discount Value</label>
                                            <div class="input-group">
                                                @if($discountType === 'percentage')
                                                <input type="number" class="form-control"
                                                    placeholder="Enter percentage"
                                                    wire:model.live="discountValue"
                                                    min="0"
                                                    max="100"
                                                    step="0.01">
                                                @else
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" class="form-control"
                                                    placeholder="Enter amount"
                                                    wire:model.live="discountValue"
                                                    min="0"
                                                    max="{{ $subtotal - $totalDiscount }}"
                                                    step="0.01">
                                                @endif

                                                <!-- Discount Type Toggle Buttons -->
                                                <div class="btn-group" role="group" aria-label="Discount Type">
                                                    <button type="button"
                                                        class="discount-type-btn {{ $discountType === 'amount' ? 'active' : '' }}"
                                                        wire:click="$set('discountType', 'amount')"
                                                        title="Amount Discount">
                                                        <i class="fas fa-dollar-sign"></i> Rs
                                                    </button>
                                                    <button type="button"
                                                        class="discount-type-btn {{ $discountType === 'percentage' ? 'active' : '' }}"
                                                        wire:click="$set('discountType', 'percentage')"
                                                        title="Percentage Discount">
                                                        <i class="fas fa-percent"></i> %
                                                    </button>
                                                </div>
                                            </div>
                                            @if($discountType === 'percentage')
                                            <small class="text-muted">Max: 100%</small>
                                            @else
                                            <small class="text-muted">Max: Rs.{{ number_format($subtotal - $totalDiscount, 2) }}</small>
                                            @endif
                                        </div>

                                        <!-- Calculated Discount Display -->
                                        @if($calculatedDiscount > 0)
                                        <div class="alert alert-success mb-0 py-2">
                                            <small class="fw-bold">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Discount Applied: Rs.{{ number_format($calculatedDiscount, 2) }}
                                                @if($discountType === 'percentage')
                                                ({{ number_format($discountValue, 2) }}%)
                                                @endif
                                            </small>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Show overall discount if applied -->
                                    @if($calculatedDiscount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Additional Discount:</span>
                                        <span class="text-danger">-Rs.{{ number_format($calculatedDiscount, 2) }}</span>
                                    </div>
                                    @endif

                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Grand Total:</span>
                                        <span class="fw-bold text-success">Rs.{{ number_format($grandTotal, 2) }}</span>
                                    </div>

                                    <div class="d-flex mt-4">
                                        <button class="btn btn-danger me-2" wire:click="clearCart">
                                            <i class="bi bi-x me-2"></i>Clear
                                        </button>
                                        <button class="btn btn-success flex-grow-1" wire:click="completeSale">
                                            <i class="bi bi-check me-2"></i>Complete Sale
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="viewDetailModal" tabindex="-1"
            aria-labelledby="viewDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h1 class="modal-title fs-5 text-white" id="viewDetailModalLabel">Watch Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @if ($productDetails)
                    <div class="modal-body p-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-md-4 border-end">
                                        <div class="position-relative h-100">
                                            @if ($productDetails->image)
                                            <img src="{{ asset('public/storage/' . $productDetails->image) }}"
                                                alt="{{ $productDetails->name }}"
                                                class="img-fluid rounded-start h-100 w-100 object-fit-cover">
                                            @else
                                            <div
                                                class="bg-light d-flex flex-column align-items-center justify-content-center h-100">
                                                <i class="bi bi-box-seam text-muted" style="font-size: 5rem;"></i>
                                                <p class="text-muted mt-2">No image available</p>
                                            </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-2">
                                                @if ($productDetails->available_stock > 0)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle-fill"></i> In Stock
                                                </span>
                                                @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle-fill"></i> Out of Stock
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h3 class="fw-bold mb-0 text-primary">{{
                                                        $productDetails->product_name }}</h3>
                                            </div>

                                            <div class="mb-3">
                                                <span class="badge bg-dark p-2 fs-6">Code: {{
                                                        $productDetails->product_code ?? 'N/A' }}</span>
                                            </div>

                                            <div class="mb-4">
                                                <p class="text-muted mb-1">Description</p>
                                                <p>{{ $productDetails->description ?? 'N/A' }}</p>
                                            </div>

                                            <div class="card bg-light p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="text-primary fw-bold">
                                                            Rs.{{ number_format($productDetails->selling_price, 2)
                                                                }}
                                                        </h4>
                                                        @if ($productDetails->available_stock > 0)
                                                        <small class="text-success">
                                                            <i class="bi bi-check-circle-fill"></i> {{
                                                                $productDetails->available_stock }} units available
                                                        </small>
                                                        @else
                                                        <small class="text-danger fw-bold">
                                                            <i class="bi bi-exclamation-triangle-fill"></i> OUT OF
                                                            STOCK
                                                        </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion mt-4" id="productDetailsAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#inventory-collapse" aria-expanded="true"
                                            aria-controls="inventory-collapse">
                                            <i class="bi bi-box-seam me-2"></i> Inventory
                                        </button>
                                    </h2>
                                    <div id="inventory-collapse" class="accordion-collapse collapse show"
                                        data-bs-parent="#productDetailsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-danger">
                                                        <div class="card-body d-flex justify-content-between">
                                                            <p class="card-text fw-bold">Damage Stock</p>
                                                            <h4 class="card-title text-danger">{{
                                                                    $productDetails->damage_quantity }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="card mb-3 {{ $productDetails->available_stock > 0 ? 'border-success' : 'border-danger' }}">
                                                        <div class="card-body d-flex justify-content-between">
                                                            <p class="card-text fw-bold">Available Stock</p>
                                                            <h4
                                                                class="card-title {{ $productDetails->available_stock > 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $productDetails->available_stock }}
                                                            </h4>
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
                    @endif


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="addCustomerModal" tabindex="-1"
            aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addCustomerModalLabel">
                            <i class="bi bi-user-plus me-2"></i>Add New Customer
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveCustomer">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Customer Type</label>
                                    <div class="d-flex">
                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="newCustomerType"
                                                id="newWholesale" value="wholesale" wire:model="newCustomerType" checked>
                                            <label class="form-check-label" for="newWholesale">Wholesale</label>
                                        </div>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="newCustomerType"
                                                id="newRetail" value="retail" wire:model="newCustomerType">
                                            <label class="form-check-label" for="newRetail">Retail</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter customer name"
                                            wire:model="newCustomerName" required>
                                    </div>
                                    @error('newCustomerName')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter phone number"
                                            wire:model="newCustomerPhone">
                                    </div>
                                    @error('newCustomerPhone')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                        <input type="email" class="form-control" placeholder="Enter email address"
                                            wire:model="newCustomerEmail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter address"
                                            wire:model="newCustomerAddress">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Additional Information</label>
                                    <textarea class="form-control" rows="3"
                                        placeholder="Add any additional information about this customer"
                                        wire:model="newCustomerNotes"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveCustomer">
                            <i class="bi bi-save me-1"></i>Save Customer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 shadow-xl"
                    style="border: 2px solid #233D7F; background: linear-gradient(145deg, #FFFFFF, #F8F9FA);">
                    <div class="modal-header"
                        style="background-color: #233D7F; color: #FFFFFF; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                        <h5 class="modal-title fw-bold tracking-tight" id="receiptModalLabel">
                            <i class="bi bi-receipt me-2"></i>Sales Receipt
                        </h5>
                        <div class="ms-auto d-flex gap-2">
                            <button type="button" class="btn btn-sm rounded-full px-3 transition-all hover:shadow"
                                id="printButton" style="background-color: #233D7F;border-color:#fff; color: #fff;">
                                <i class="bi bi-printer me-1"></i>Print
                            </button>
                            <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body p-4" id="receiptContent">
                        @if ($receipt)
                        <div class="receipt-container">
                            <div class="text-center mb-4">
                                <h3 class="mb-1 fw-bold tracking-tight" style="color: #233D7F;">SAHAR LANKA</h3>
                                <h5 class="mb-1 fw-medium" style="color: #233D7F;">Importers & Retailers of Genuine Spares for <br> MARUTI-LEYLAND - MAHINDRA-TATA-ALTO</h5>
                                <p class="mb-0 text-muted small" style="color: #6B7280;">NO. 397/, DUNU ELA, THIHARIYA, KALAGEDIHENA</p>
                                <p class="mb-0 text-muted small" style="color: #6B7280;">Phone: 077 6718838</p>
                                <hr style=" border: 2px solid #233D7F;">
                            </div>


                            <div class="row mb-2">

                                <div class="col-md-6">

                                    @if ($receipt->customer)
                                    <p class="mb-1" style="color: #233D7F;"><strong>Customer Name:</strong> {{
                                            $receipt->customer->name }}</p>
                                    <p class="mb-1" style="color: #233D7F;"><strong>Address :</strong> {{
                                            $receipt->customer->address }}</p>
                                    <p class="mb-1" style="color: #233D7F;"><strong>Phone :</strong> {{
                                            $receipt->customer->phone }}</p>
                                    @else
                                    <p class="text-muted" style="color: #6B7280;">Walk-in Customer</p>
                                    @endif
                                </div>
                                <div class="col-md-6">

                                    <p class="mb-1" style="color: #233D7F;"><strong>Invoice Number:</strong> {{
                                            $receipt->invoice_number }}</p>
                                    <p class="mb-1" style="color: #233D7F;"><strong>Date:</strong> {{
                                            $receipt->created_at->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }}
                                    </p>
                                    <p class="mb-1"><strong>Payment Status:</strong>
                                        @if(ucfirst($receipt->payment_status) == 'Paid')
                                        <span class="badge"
                                            style="background-color: {{ $receipt->payment_status == 'paid' ? '#0F5132' : ($receipt->payment_status == 'partial' ? '#664D03' : '#842029') }}; color: #FFFFFF;">
                                            Paid
                                        </span>
                                        @else
                                        <span class="badge"
                                            style="background-color: {{ $receipt->payment_status == 'paid' ? '#0F5132' : ($receipt->payment_status == 'partial' ? '#664D03' : '#842029') }}; color: #FFFFFF;">
                                            Credit
                                        </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm border-1"
                                    style="border-color: #233D7F;">
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
                                        @foreach ($receipt->items as $index => $item)
                                        <tr class="transition-all hover:bg-gray-50">
                                            <td class="text-center py-1">{{ $index + 1 }}</td>
                                            <td class="text-center py-1">{{ $item->product->product_code ?? 'N/A' }}
                                            </td>
                                            <td class="text-left py-1">{{ $item->product->product_name ?? 'N/A' }}
                                            </td>
                                            <td class="text-center py-1">{{ $item->quantity }}</td>
                                            <td class="text-right py-1">{{ number_format($item->price, 2) }}
                                            </td>

                                            <td class="text-right py-1">{{ number_format(($item->price *
                                                    $item->quantity) - ($item->discount * $item->quantity), 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <!-- Subtotal, Discount, and Grand Total rows -->
                                        <tr style="background-color: #f8f9fa;">
                                            <td colspan="5" class="text-right py-2 fw-bold" style="font-size: 14px;">Amount (LKR):</td>
                                            <td class="text-right py-2 fw-bold" style="font-size: 14px;">{{ number_format($receipt->subtotal, 2) }}</td>
                                        </tr>
                                        @if($receipt->discount_amount > 0)
                                        <tr style="background-color: #f8f9fa;">
                                            <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Total Discount:</td>
                                            <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">({{ number_format($receipt->discount_amount, 2) }})</td>
                                        </tr>

                                        <tr style="background-color: #233D7F; color: #FFFFFF;">
                                            <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">Total:</td>
                                            <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">{{ number_format($receipt->total_amount, 2) }}</td>
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
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4 pt-3 border-top" style="border-color: #233D7F;">
                                <p class="mb-0 text-muted small" style="color: #6B7280;">Thank you for your
                                    purchase!</p>
                            </div>
                        </div>
                        @else
                        <div class="text-center p-5">
                            <p class="text-muted" style="color: #6B7280;">No receipt data available</p>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top py-3" style="border-color: #233D7F; background: #F8F9FA;">
                        <button type="button"
                            class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                            data-bs-dismiss="modal"
                            style="background-color: #6B7280; border-color: #6B7280; color: #FFFFFF;"
                            onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';"
                            onmouseout="this.style.backgroundColor='#6B7280'; this.style.borderColor='#6B7280';">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printButton = document.getElementById('printButton');
        if (printButton) {
            printButton.addEventListener('click', function() {
                printSalesReceipt();
            });
        }
    });

    function printSalesReceipt() {
        const receiptContent = document.querySelector('#receiptContent');
        if (!receiptContent) {
            alert('Receipt content not found.');
            return;
        }

        // Extract data from receipt modal
        const rowDiv = receiptContent.querySelector('.row.mb-2');
        const leftCol = rowDiv?.querySelector('.col-md-6:first-child');
        const rightCol = rowDiv?.querySelector('.col-md-6:last-child');

        // Extract customer information (from left column)
        let customerName = 'Walk-in Customer';
        let customerAddress = '';
        let customerPhone = '';

        if (leftCol) {
            const customerNameP = leftCol.querySelector('p:first-child');
            if (customerNameP && customerNameP.textContent.includes('Customer Name:')) {
                customerName = customerNameP.textContent.replace('Customer Name:', '').trim();
            }

            const customerAddressP = leftCol.querySelector('p:nth-child(2)');
            if (customerAddressP && customerAddressP.textContent.includes('Address :')) {
                customerAddress = customerAddressP.textContent.replace('Address :', '').trim();
            }

            const customerPhoneP = leftCol.querySelector('p:nth-child(3)');
            if (customerPhoneP && customerPhoneP.textContent.includes('Phone :')) {
                customerPhone = customerPhoneP.textContent.replace('Phone :', '').trim();
            }
        }

        // Extract invoice information (from right column)
        let invoiceNumber = '';
        let date = '';
        let paymentStatus = 'N/A';

        if (rightCol) {
            const invoiceP = rightCol.querySelector('p:first-child');
            if (invoiceP && invoiceP.textContent.includes('Invoice Number:')) {
                invoiceNumber = invoiceP.textContent.replace('Invoice Number:', '').trim();
            }

            const dateP = rightCol.querySelector('p:nth-child(2)');
            if (dateP && dateP.textContent.includes('Date:')) {
                date = dateP.textContent.replace('Date:', '').trim();
            }

            const statusP = rightCol.querySelector('p:nth-child(3)');
            if (statusP) {
                const statusBadge = statusP.querySelector('.badge');
                if (statusBadge) {
                    paymentStatus = statusBadge.textContent.trim();
                }
            }
        }

        // Get items table
        const itemsTable = receiptContent.querySelector('.table-bordered');

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
                            font-size: 14px;
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
                        .receipt-title {
                            font-size: 14px;
                            font-weight: bold;
                            color: #000;
                            text-align: right;
                            margin: 5px 0;
                            padding-bottom: 10px;
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
                        .info-section h6 {
                            color: #000;
                            font-weight: bold;
                            padding-bottom: 4px;
                            margin-bottom: 4px;
                            font-size: 13px;
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
                        .info-label {
                            font-weight: bold;
                            display: inline-block;
                            width: 140px;
                        }
                        .info-value {
                            display: inline-block;
                        }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .fw-bold { font-weight: bold; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        .mb-2 { margin-bottom: 0.5rem; }
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
                        }
                        table th {
                            background-color: #f0f0f0;
                            font-weight: bold;
                        }
                        
                        /* Footer Styling */
                        .footer {
                            text-align: center;
                            margin-top: 30px;
                            padding-top: 20px;
                            color: #000;
                            position: absolute;
                            bottom: 0;
                            width: 100%;
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

        // Listener for showing modals from the backend
        window.addEventListener('showModal', event => {
            const modal = new bootstrap.Modal(document.getElementById(event.detail[0].modalId));
            modal.show();
        });

        // Listener for closing modals from the backend
        window.addEventListener('closeModal', event => {
            const modal = bootstrap.Modal.getInstance(document.getElementById(event.detail[0].modalId));
            if (modal) {
                modal.hide();
            }
        });

        // This is the single, working toast notification listener using SweetAlert2
        window.addEventListener('show-toast', event => {
            const data = event.detail[0];
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });

        // Cheque form validation and utilities
        window.resetChequeForm = function() {
            const form = document.getElementById('chequeForm');
            if (form) {
                form.reset();
                // Trigger Livewire to reset the form data
                @this.call('resetChequeForm');
            }
        }

        function validateChequeForm() {
            const form = document.getElementById('chequeForm');
            if (!form) return true;

            const chequeNumber = form.querySelector('input[wire\\:model="newCheque.number"]')?.value || '';
            const bankName = form.querySelector('select[wire\\:model="newCheque.bank"]')?.value || '';
            const chequeDate = form.querySelector('input[wire\\:model="newCheque.date"]')?.value || '';
            const amount = form.querySelector('input[wire\\:model="newCheque.amount"]')?.value || '';

            if (!chequeNumber.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a cheque number'
                });
                return false;
            }

            if (!bankName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a bank'
                });
                return false;
            }

            if (!chequeDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a cheque date'
                });
                return false;
            }

            // Check if date is today or future
            const selectedDate = new Date(chequeDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'Cheque date cannot be in the past. Please select today\'s date or a future date.'
                });
                return false;
            }

            if (!amount || parseFloat(amount) <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a valid amount greater than 0'
                });
                return false;
            }

            return true;
        }

        // Add form validation on submit
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'chequeForm') {
                if (!validateChequeForm()) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }
        });

    });
    document.addEventListener('livewire:initialized', () => {
        // Handle discount type button clicks with proper event delegation
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.discount-type-btn');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();

                // Remove active class from all buttons
                document.querySelectorAll('.discount-type-btn').forEach(b => {
                    b.classList.remove('active');
                });

                // Add active class to clicked button
                btn.classList.add('active');

                // Trigger Livewire update
                const discountType = btn.title.includes('Amount') ? 'amount' : 'percentage';
                @this.set('discountType', discountType);
            }
        });

        // Update button states when discountType changes
        Livewire.on('discountTypeChanged', () => {
            const currentType = @this.discountType;
            document.querySelectorAll('.discount-type-btn').forEach(btn => {
                const btnType = btn.title.includes('Amount') ? 'amount' : 'percentage';
                if (btnType === currentType) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        });
    });
</script>
@endpush
</div>