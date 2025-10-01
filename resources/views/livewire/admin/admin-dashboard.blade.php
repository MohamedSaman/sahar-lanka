<div class="px-3">
    @push('styles')
    <style>
        /* ===== Base Layout ===== */
        .container-fluid {
            width: 100%;
            padding-right: var(--bs-gutter-x, 0.75rem);
            padding-left: var(--bs-gutter-x, 0.75rem);
            margin-right: auto;
            margin-left: auto;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            min-height: 100vh;
        }

        /* ===== Tabs ===== */
        .content-tabs {
            background: white;
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 2rem;
            overflow-x: auto;
            white-space: nowrap;
            box-shadow: 0 2px 20px rgba(35, 61, 127, 0.08);
            border: 1px solid rgba(35, 61, 127, 0.1);
        }

        .content-tab {
            padding: 0.75rem 1.5rem;
            margin-right: 0.25rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
        }

        .content-tab:hover {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #233d7f;
            transform: translateY(-1px);
        }

        .content-tab.active {
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(35, 61, 127, 0.3);
            transform: translateY(-1px);
        }

        .content-tab.active::after {
            display: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Stat Cards ===== */
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(35, 61, 127, 0.08);
            box-shadow: 0 4px 25px rgba(35, 61, 127, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #233d7f 0%, #1e3a8a 50%, #3b82f6 100%);
            border-radius: 16px 16px 0 0;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(35, 61, 127, 0.15);
            border-color: rgba(35, 61, 127, 0.2);
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0.5rem 0;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom Progress Bars */
        .progress {
            height: 10px;
            margin-bottom: 15px;
            background: rgba(35, 61, 127, 0.1);
            border-radius: 50px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            border-radius: 50px;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        }

        /* ===== Chart Card ===== */
        .chart-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            border: 1px solid rgba(35, 61, 127, 0.08);
            box-shadow: 0 8px 30px rgba(35, 61, 127, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(35, 61, 127, 0.12);
        }

        .chart-header {
            padding: 1.5rem 1.5rem 1rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid rgba(35, 61, 127, 0.08);
        }

        .chart-header h6 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .chart-container {
            position: relative;
            height: 320px;
            padding: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        /* ===== Enhanced Cards ===== */
        .card {
            border: 1px solid rgba(35, 61, 127, 0.08);
            border-radius: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 8px 30px rgba(35, 61, 127, 0.08);
            max-height: 400px;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(35, 61, 127, 0.12);
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .card-title {
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700 !important;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(35, 61, 127, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.9);
        }

        .amount {
            font-weight: 700;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .list-group-item {
            border: none !important;
            border-bottom: 1px solid rgba(35, 61, 127, 0.08) !important;
            padding: 1rem 0 !important;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 12px !important;
            transform: translateX(4px);
        }

        /* ===== Inventory Widget ===== */
        .widget-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            border: 1px solid rgba(35, 61, 127, 0.08);
            box-shadow: 0 8px 30px rgba(35, 61, 127, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            max-height: 450px;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .widget-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(35, 61, 127, 0.12);
        }

        .widget-header h6 {
            font-size: 1.125rem;
            font-weight: 700;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .in-stock {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #34d399;
        }

        .low-stock {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .out-of-stock {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        /* ===== Enhanced Buttons ===== */
        .btn-outline-primary {
            border: 2px solid #233d7f;
            color: #233d7f;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .btn-outline-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-outline-primary:hover {
            color: white;
            border-color: #233d7f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(35, 61, 127, 0.3);
        }

        .btn-outline-primary:hover::before {
            left: 0;
        }

        /* ===== Enhanced Badges ===== */
        .badge {
            border-radius: 50px;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border: 1px solid #34d399;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            border: 1px solid #f87171;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border: 1px solid #fbbf24;
            color: #ffffff !important;
        }

        /* ===== Form Controls ===== */
        .form-select {
            border: 2px solid rgba(35, 61, 127, 0.1);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .form-select:focus {
            border-color: #233d7f;
            box-shadow: 0 0 0 0.2rem rgba(35, 61, 127, 0.15);
            background: white;
        }

        /* ===== Alert Styling ===== */
        .alert-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 16px;
            color: #0c4a6e;
            font-weight: 500;
            padding: 1.25rem;
        }

        /* ===== Scrollbar Styling ===== */
        .widget-container::-webkit-scrollbar,
        .card::-webkit-scrollbar {
            width: 6px;
        }

        .widget-container::-webkit-scrollbar-track,
        .card::-webkit-scrollbar-track {
            background: rgba(35, 61, 127, 0.05);
            border-radius: 10px;
        }

        .widget-container::-webkit-scrollbar-thumb,
        .card::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
            border-radius: 10px;
        }

        /* ===== Mobile Responsiveness ===== */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1rem;
            }

            /* Tabs */
            .content-tabs {
                display: flex;
                overflow-x: auto;
                scrollbar-width: none;
                padding: 6px;
            }

            .content-tabs::-webkit-scrollbar {
                display: none;
            }

            .content-tab {
                flex: 1 0 auto;
                text-align: center;
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
            }

            /* Stat Cards */
            .stat-card {
                padding: 1.25rem;
                margin-bottom: 1rem;
            }

            .stat-label {
                font-size: 0.8rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            /* Chart */
            .chart-container {
                height: 280px !important;
                padding: 1rem;
            }

            .chart-header {
                padding: 1.25rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            /* Cards */
            .card {
                max-height: unset;
                margin-bottom: 1.5rem;
            }

            .card-body {
                padding: 1.25rem !important;
            }

            .list-group-item {
                flex-wrap: wrap;
                gap: 0.75rem;
                padding: 0.875rem 0 !important;
            }

            .avatar {
                width: 44px;
                height: 44px;
            }

            .amount {
                font-size: 0.875rem;
            }

            /* Widget */
            .widget-container {
                max-height: unset;
                padding: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .widget-header h6 {
                font-size: 1rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.6rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 0.75rem;
            }

            /* Tabs on extra small */
            .content-tab {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }

            /* Stat Cards */
            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            /* Chart */
            .chart-container {
                height: 250px !important;
                padding: 0.75rem;
            }

            .chart-header {
                padding: 1rem;
            }

            .chart-header h6 {
                font-size: 1rem;
            }

            /* Sales items */
            .avatar {
                width: 36px;
                height: 36px;
                font-size: 0.75rem;
            }

            .list-group-item h6 {
                font-size: 0.875rem;
            }

            .list-group-item p {
                font-size: 0.75rem;
            }

            .amount {
                font-size: 0.8rem;
            }

            /* Buttons */
            .btn-outline-primary {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }

            /* Progress bars */
            .progress {
                height: 8px;
            }

            /* Form controls */
            .form-select {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }

            /* Status badges */
            .status-badge {
                font-size: 0.65rem;
                padding: 0.25rem 0.5rem;
            }

            /* Widget */
            .widget-container {
                padding: 1rem;
            }

            .widget-header h6 {
                font-size: 0.95rem;
            }
        }

        /* ===== Additional Animations ===== */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .chart-card,
        .card,
        .widget-container {
            animation: slideInUp 0.6s ease-out;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }
    </style>
    @endpush


    <!-- ===== Tabs ===== -->
    <div class="content-tabs">
        <div class="d-flex">
            <div class="content-tab active" data-tab="overview">Overview</div>
            <div class="content-tab" data-tab="analytics">Analytics</div>
            <div class="content-tab" data-tab="reports">Reports</div>
            <div class="content-tab" data-tab="notifications">Notifications</div>
        </div>
    </div>

    <!-- Overview Content -->
    <div id="overview" class="tab-content active">
        <!-- Stats Cards Row -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Total Revenue</div>
                    </div>
                    <div class="stat-value">Rs.{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-info mt-1">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Revenue</small>
                            <small>{{ $revenuePercentage }}% of total sales</small>
                        </div>
                        <div class="progress ">
                            <div class="progress-bar bg-success" role="progressbar" style=" width: {{ $revenuePercentage }}%;" aria-valuenow="{{ $revenuePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">Rs.{{ number_format($totalRevenue) }} of Rs.{{ number_format($totalRevenue + $totalDueAmount) }}</small>
                        </div>
                    </div>
                    <div class="stat-info mt-3 pt-2 border-top border-1 border-gray-200">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Today Revenue</small>
                            <span class="badge bg-success">{{$todayRevenueCount}}</span>

                        </div>
                        <small class="d-block text-end text-success">Rs.{{ number_format($todayRevenue, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Total Due Amount</div>
                    </div>
                    <div class="stat-value">Rs.{{ number_format($totalDueAmount, 2) }}</div>
                    <div class="stat-change-alert">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Due Amount</small>
                            <small>{{ $duePercentage }}% of total sales</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $duePercentage }}%;" aria-valuenow="{{ $duePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">Rs.{{ number_format($totalDueAmount) }} due of Rs.{{ number_format($totalDueAmount + $totalRevenue) }}</small>
                        </div>
                    </div>
                    <div class="stat-info mt-3 pt-2 border-top border-1 border-gray-200">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-clock-fill text-danger me-1"></i> Partially Paid</small>
                            <span class="badge bg-danger">{{ $partialPaidCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Inventory Status</div>
                    </div>
                    <div class="stat-value">{{ number_format($totalStock) }} <span class="fs-6 text-muted">units</span></div>
                    <div class="stat-info">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Damaged Stock</small>
                            <small>{{ $totalStock > 0 ? round(($damagedStock / ($totalStock + $damagedStock)) * 100, 1) : 0 }}% of total</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalStock > 0 ? round(($damagedStock / ($totalStock + $damagedStock)) * 100, 1) : 0 }}%;" aria-valuenow="{{ $totalStock > 0 ? round(($damagedStock / ($totalStock + $damagedStock)) * 100, 1) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">{{ number_format($damagedStock) }} damaged of {{ number_format($totalStock + $damagedStock) }}</small>
                        </div>
                    </div>
                    <div class="stat-info mt-3 pt-2 border-top border-1 border-gray-200">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-box-seam text-warning me-1"></i> Total</small>
                            <span class="badge bg-warning" style=" color: #FFFFFF;">{{ number_format($totalStock) }}</span>
                        </div>
                        <small class="d-block text-end text-warning">Rs.{{ number_format($totalInventoryValue, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Total Products Sold</div>
                    </div>
                    @php
                    $totalSold = collect($soldProducts)->sum('total_quantity');
                    $soldPercentage = $totalStock > 0 ? round(($totalSold / ($totalStock + $totalSold)) * 100, 1) : 0;
                    @endphp
                    <div class="stat-value">{{ number_format($totalSold) }} <span class="fs-6 text-muted">units</span></div>
                    <div class="stat-info">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Sold Units</small>
                            <small>{{ $soldPercentage }}% of total inventory</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $soldPercentage }}%;" aria-valuenow="{{ $soldPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">{{ number_format($totalSold) }} sold of {{ number_format($totalStock + $totalSold) }}</small>
                        </div>
                    </div>
                    <div class="stat-info mt-3 pt-2 border-top border-1 border-gray-200">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-cart-check text-success me-1"></i> Total</small>
                            <span class="badge bg-success" style="color: #FFFFFF;">{{ number_format($totalSold) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and Recent Sales Section -->
        <div class="row">
            <div class="col-sm-12 col-lg-8 mb-4">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-mobile-2">
                            <h6 class="mb-1 fw-bold tracking-tight" style="color: #233D7F;">Sales Overview</h6>
                            <p class="text-muted mb-0 small">Daily sales trend</p>
                        </div>
                        <select wire:model.live="filter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="7_days">Last 7 Days</option>
                            <option value="30_days">Last 30 Days</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                            <div class="mb-2 mb-md-0">
                                <h6 class="card-title fw-bold tracking-tight" style="color: #233D7F;">Recent Sales</h6>
                                <p class="text-muted small mb-0">Latest transactions</p>
                            </div>
                            <a href="{{ route('admin.view-payments') }}" class="btn btn-outline-primary">
                                <i class="bi bi-list-ul"></i> View All
                            </a>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($recentSales->take(10) as $sale)
                            <li class="list-group-item d-flex align-items-center py-2">
                                <div class="avatar">
                                    {{ strtoupper(substr($sale->name, 0, 1)) }}{{ strtoupper(substr(strpos($sale->name, ' ') !== false ? substr($sale->name, strpos($sale->name, ' ') + 1, 1) : '', 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-truncate-mobile" style="color: #233D7F;">{{ $sale->name }}</h6>
                                    <!-- <p class="text-muted small mb-0 text-truncate-mobile">{{ $sale->email }}</p> -->
                                    <p class="text-muted small mb-0 text-truncate-mobile">{{ $sale->type }}</p>
                                </div>
                                <div class="amount text-end">
                                    Rs.{{ number_format($sale->total_amount, 2) }}
                                    @if($sale->due_amount > 0)
                                    <span class="d-block text-danger small">Rs.{{ number_format($sale->due_amount, 2) }}</span>
                                    @else
                                    <span class="d-block badge mt-1 small">Paid</span>
                                    @endif
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item text-center py-3">
                                <p class="text-muted mb-0">No sales recorded yet</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Section -->
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="widget-container">
                    <div class="widget-header d-flex justify-content-between align-items-start flex-wrap mb-3">
                        <div class="mb-2 mb-md-0">
                            <h6 class="fw-bold tracking-tight" style="color: #233D7F;">Inventory Status</h6>
                            <p class="text-muted small mb-0">Current stock levels and alerts</p>
                        </div>
                        <a href="{{ route('admin.product-stocks') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam"></i> View Details
                        </a>
                    </div>
                    <div class="inventory-container">
                        @forelse($productInventory->take(10) as $product)
                        @php
                        $stockPercentage = ($product->stock_quantity + $product->damage_quantity) > 0 ? round(($product->stock_quantity / ($product->stock_quantity + $product->damage_quantity)) * 100, 2) : 0;
                        $statusClass = $product->stock_quantity == 0 ? 'out-of-stock' : ($stockPercentage <= 25 ? 'low-stock' : 'in-stock' );
                            $statusText=$product->stock_quantity == 0 ? 'Out of Stock' : ($stockPercentage <= 25 ? 'Low Stock' : 'In Stock' );
                                $progressClass=$product->stock_quantity == 0 ? 'bg-danger' : ($stockPercentage <= 25 ? 'bg-warning' : 'bg-success' );
                                    @endphp

                                    <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0" style="color:#233d7f;">{{ $product->name }}</h6>
                                        <div class="d-flex align-items-end flex-wrap mt-1 mt-md-0">
                                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                            <div class="ms-2 text-muted small">{{ $product->stock_quantity }}/{{ $product->stock_quantity + $product->damage_quantity }}</div>
                                        </div>
                                    </div>
                                    <div class="progress mt-1">
                                        <div class="progress-bar {{ $progressClass }}" style="width: {{ $stockPercentage }}%"></div>
                                    </div>
                    </div>
                    @empty
                    <div class="alert alert-info border-0">No product inventory data available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Content -->
<div id="analytics" class="tab-content">
    <div class="alert alert-info border-0" style="border-radius: 0.5rem; color: #233D7F; background-color: #F8F9FA;">
        Analytics content will appear here when this tab is selected.
    </div>
</div>

<!-- Reports Content -->
<div id="reports" class="tab-content">
    <div class="alert alert-info border-0" style="border-radius: 0.5rem; color: #233D7F; background-color: #F8F9FA;">
        Reports content will appear here when this tab is selected.
    </div>
</div>

<!-- Notifications Content -->
<div id="notifications" class="tab-content">
    <div class="alert alert-info border-0" style="border-radius: 0.5rem; color: #233D7F; background-color: #F8F9FA;">
        Notifications content will appear here when this tab is selected.
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('livewire:initialized', function() {
        // Get the canvas context
        const salesCtx = document.getElementById('salesChart')?.getContext('2d');
        if (!salesCtx) {
            console.error('Sales chart canvas not found');
            return;
        }

        let salesChartInstance;

        function renderSalesChart(labels, totals) {
            // Destroy existing chart instance if it exists
            if (salesChartInstance) {
                salesChartInstance.destroy();
            }

            // Ensure labels and totals are arrays
            labels = Array.isArray(labels) ? labels.map(date => {
                const d = new Date(date);
                return isNaN(d) ? date : d.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
            }) : [];
            totals = Array.isArray(totals) ? totals : [];

            salesChartInstance = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Sales',
                        data: totals,
                        borderColor: '#233D7F',
                        backgroundColor: 'rgba(35, 61, 127, 0.2)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#233D7F',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#233D7F',
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `Sales: Rs. ${Number(context.parsed.y).toFixed(2)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#E9ECEF'
                            },
                            ticks: {
                                color: '#233D7F',
                                callback: function(value) {
                                    return 'Rs. ' + Number(value).toFixed(2);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#233D7F',
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }

        // Initial chart render
        const initialData = @json($salesData);
        renderSalesChart(initialData.labels || [], initialData.totals || []);

        // Listen for chart updates
        window.Livewire.on('refreshSalesChart', (data) => {
            console.log('Refreshing chart with data:', data); // Debugging
            renderSalesChart(data.labels || [], data.totals || []);
        });

        // Tab switching logic
        const tabs = document.querySelectorAll('.content-tab');
        const tabContents = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
    });
</script>
@endpush
</div>