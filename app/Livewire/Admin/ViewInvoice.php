<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SalesItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("View Invoices")]
#[Layout('components.layouts.admin')]
class ViewInvoice extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentType = '';
    public $customerType = '';

    public $saleDetails = null;
    public $selectedSaleId = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingPaymentType()
    {
        $this->resetPage();
    }

    public function updatingCustomerType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'paymentType', 'customerType']);
        $this->resetPage();
    }

    public function viewInvoice($saleId)
    {
        $this->selectedSaleId = $saleId;

        $sale = Sale::with(['customer', 'user', 'items.product'])->find($saleId);

        if (!$sale) {
            session()->flash('error', 'Sale not found');
            return;
        }

        $this->saleDetails = [
            'sale' => $sale,
            'items' => $sale->items,
            'returnItems' => collect([]), // Empty collection
            'totalReturnAmount' => 0,
            'adjustedGrandTotal' => $sale->total_amount
        ];

        $this->dispatch('openInvoiceModal');
    }

    public function render()
    {
        $query = Sale::with(['customer', 'user', 'items'])
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Date filters
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Payment type filter
        if ($this->paymentType) {
            $query->where('payment_type', $this->paymentType);
        }

        // Customer type filter
        if ($this->customerType) {
            $query->whereHas('customer', function ($q) {
                $q->where('customer_type', $this->customerType);
            });
        }

        $sales = $query->paginate(15);

        // Calculate stats
        $totalSales = Sale::count();
        $todaySales = Sale::whereDate('created_at', Carbon::today())->count();
        $totalRevenue = Sale::sum('total_amount');
        $todayRevenue = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');

        return view('livewire.admin.view-invoice', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue
        ]);
    }
}
