<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Customer;

#[Layout('components.layouts.admin')]
#[Title('Customer Sales Details')]
class CustomerSaleDetails extends Component
{
    use WithPagination;

    public $modalData = null;

    public function viewSaleDetails($customerId)
    {
        // Get customer details
        $customer = Customer::findOrFail($customerId);

        // Get total sales amount
        $totalSales = DB::table('sales')
            ->where('customer_id', $customerId)
            ->sum('total_amount');

        // Get paid amount from payments table (Cash/Cheque with status Paid + Cheque with status Pending)
        $totalPaid = DB::table('payments')
            ->join('sales', 'payments.sale_id', '=', 'sales.id')
            ->where('sales.customer_id', $customerId)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereIn('payments.payment_method', ['cash', 'cheque'])
                        ->where('payments.status', 'Paid');
                })->orWhere(function ($q) {
                    $q->where('payments.payment_method', 'cheque')
                        ->where('payments.status', 'Pending');
                });
            })
            ->sum('payments.amount');

        // Calculate due amount
        $totalDue = $totalSales - $totalPaid;

        $salesSummary = (object) [
            'total_amount' => $totalSales,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ];

        // Get individual invoices
        $invoices = Sale::where('customer_id', $customerId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get product-wise sales with product details (replaced with invoice and payment data)
        $invoicePaymentData = DB::table('sales')
            ->leftJoin('payments', 'sales.id', '=', 'payments.sale_id')
            ->where('sales.customer_id', $customerId)
            ->where(function ($query) {
                $query->whereNull('payments.id') // Include invoices without payments
                    ->orWhere(function ($q) {
                        $q->where(function ($subQ) {
                            $subQ->whereIn('payments.payment_method', ['cash', 'cheque'])
                                ->where('payments.status', 'Paid');
                        })->orWhere(function ($subQ) {
                            $subQ->where('payments.payment_method', 'cheque')
                                ->where('payments.status', 'Pending');
                        });
                    });
            })
            ->select(
                'sales.id as sale_id',
                'sales.invoice_number',
                'sales.total_amount',
                'sales.created_at as sale_date',
                'payments.id as payment_id',
                'payments.payment_method',
                'payments.amount as payment_amount',
                'payments.status as payment_status',
                'payments.created_at as payment_date'
            )
            ->orderBy('sales.created_at', 'asc')
            ->orderBy('payments.created_at', 'asc')
            ->get();

        // Process data to create the required structure
        $processedData = [];
        $totalSales = 0;
        $totalReceived = 0;

        foreach ($invoicePaymentData as $record) {
            // Add invoice row
            if (!isset($processedData[$record->sale_id])) {
                $processedData[$record->sale_id] = [
                    'type' => 'invoice',
                    'sale_id' => $record->sale_id,
                    'invoice_number' => $record->invoice_number,
                    'sales_amount' => $record->total_amount,
                    'sale_date' => $record->sale_date,
                    'payments' => []
                ];
                $totalSales += $record->total_amount;
            }

            // Add payment row if exists and is valid (includes both paid and pending cheque payments)
            if ($record->payment_id && (
                ($record->payment_method === 'cash' && $record->payment_status === 'Paid') ||
                ($record->payment_method === 'cheque' && in_array($record->payment_status, ['Paid', 'Pending']))
            )) {
                $processedData[$record->sale_id]['payments'][] = [
                    'type' => 'payment',
                    'payment_method' => $record->payment_method,
                    'payment_amount' => $record->payment_amount,
                    'payment_date' => $record->payment_date,
                    'payment_status' => $record->payment_status
                ];
                $totalReceived += $record->payment_amount;
            }
        }

        // Flatten the data for display
        $displayData = [];
        foreach ($processedData as $saleId => $saleData) {
            // Add invoice row
            $displayData[] = [
                'type' => 'invoice',
                'description' => $saleData['invoice_number'],
                'date' => $saleData['sale_date'],
                'sales_amount' => $saleData['sales_amount'],
                'received_amount' => 0
            ];

            // Add payment rows
            foreach ($saleData['payments'] as $payment) {
                $statusText = $payment['payment_status'] === 'Paid' ? 'Paid' : 'Pending';
                $displayData[] = [
                    'type' => 'payment',
                    'description' => $saleData['invoice_number'] . ' - ' . $statusText . ' (' . ucfirst($payment['payment_method']) . ')',
                    'date' => $payment['payment_date'],
                    'sales_amount' => 0,
                    'received_amount' => $payment['payment_amount'],
                    'payment_status' => $payment['payment_status']
                ];
            }
        }

        // Add totals row
        $totalDue = $totalSales - $totalReceived;
        $displayData[] = [
            'type' => 'total',
            'description' => 'Total',
            'date' => '',
            'sales_amount' => $totalSales,
            'received_amount' => $totalReceived
        ];

        // Add total due row
        $displayData[] = [
            'type' => 'due',
            'description' => 'Total Due',
            'date' => '',
            'sales_amount' => $totalDue,
            'received_amount' => 0
        ];

        $this->modalData = [
            'customer' => $customer,
            'salesSummary' => $salesSummary,
            'invoices' => $invoices,
            'displayData' => $displayData
        ];

        $this->dispatch('open-customer-sale-details-modal');
    }

    // For print functionality (main table)
    public function printData()
    {
        // Trigger JavaScript print function from the frontend
        $this->dispatch('print-customer-table');
    }

    // For CSV export
    public function exportToCSV()
    {
        $customerSales = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->leftJoin('payments', function ($join) {
                $join->on('sales.id', '=', 'payments.sale_id')
                    ->where('payments.payment_method', 'cheque')
                    ->where('payments.status', 'Pending');
            })
            ->select(
                'customers.id as customer_id',
                'customers.name',
                'customers.email',
                'customers.business_name',
                'customers.type',
                DB::raw('COUNT(DISTINCT sales.invoice_number) as invoice_count'),
                DB::raw('SUM(sales.total_amount) as total_sales'),
                DB::raw('SUM(sales.due_amount) as total_due'),
                DB::raw('SUM(sales.total_amount) - SUM(sales.due_amount) as total_paid'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as received_cheque_amount')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.business_name', 'customers.type')
            ->orderBy('total_sales', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer_sales_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($customerSales) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['#', 'Customer Name', 'Email', 'Business Name', 'Type', 'Invoices', 'Total Sales', 'Total Paid', 'Received Cheque Amount', 'Total Due', 'Collection %']);

            // Add data rows
            foreach ($customerSales as $index => $customer) {
                $percentage = $customer->total_sales > 0 ? round(($customer->total_paid / $customer->total_sales) * 100) : 100;

                fputcsv($file, [
                    $index + 1,
                    $customer->name,
                    $customer->email,
                    $customer->business_name ?? 'N/A',
                    ucfirst($customer->type),
                    $customer->invoice_count,
                    'Rs.' . number_format($customer->total_sales, 2),
                    'Rs.' . number_format($customer->total_paid, 2),
                    'Rs.' . number_format($customer->received_cheque_amount, 2),
                    'Rs.' . number_format($customer->total_due, 2),
                    $percentage . '%'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $customerSales = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->leftJoin('payments', function ($join) {
                $join->on('sales.id', '=', 'payments.sale_id')
                    ->where('payments.payment_method', 'cheque')
                    ->where('payments.status', 'Pending');
            })
            ->select(
                'customers.id as customer_id',
                'customers.name',
                'customers.email',
                'customers.business_name',
                'customers.type',
                DB::raw('COUNT(DISTINCT sales.invoice_number) as invoice_count'),
                DB::raw('SUM(sales.total_amount) as total_sales'),
                DB::raw('SUM(sales.due_amount) as total_due'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as received_cheque_amount')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.business_name', 'customers.type')
            ->orderBy('sales.created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.customer-sale-details', [
            'customerSales' => $customerSales
        ]);
    }
}
