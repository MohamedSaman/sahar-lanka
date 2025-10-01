<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Cheque;
use App\Models\Sale;
use Exception;
use Carbon\Carbon;

#[Title("Due Payments")]
#[Layout('components.layouts.admin')]
class DuePayments extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $selectedPayment = null;
    public $paymentDetail = null;
    public $duePaymentAttachment;
    public $paymentId;
    public $duePaymentMethod = '';
    public $paymentNote = '';
    public $duePaymentAttachmentPreview;
    public $receivedAmount = '';
    public $filters = [
        'status' => '',
        'dateRange' => '',
    ];

    public $extendDuePaymentId;
    public $newDueDate;
    public $extensionReason = '';

    // Cheque input fields and list
    public $chequeNumber = '';
    public $bankName = '';
    public $chequeAmount = '';
    public $chequeDate = '';
    public $cheques = [];

    public $duePayment;
    protected $listeners = ['refreshPayments' => '$refresh'];

    public function mount() {}

    public function updatedDuePaymentAttachment()
    {
        $this->validate([
            'duePaymentAttachment' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->duePaymentAttachment) {
            $this->duePaymentAttachmentPreview = $this->getFilePreviewInfo($this->duePaymentAttachment);
        }
    }

    public function getPaymentDetails($id, $isPayment = true)
    {
        try {
            $this->paymentId = $id;

            if ($isPayment) {
                $payment = Payment::with(['sale.customer', 'sale.items'])->find($id);

                if (!$payment) {
                    $this->dispatch('showToast', [
                        'type' => 'error',
                        'message' => 'Payment record not found.'
                    ]);
                    return;
                }

                $this->paymentDetail = $payment;
            } else {
                $sale = Sale::with(['customer', 'items'])->find($id);

                if (!$sale) {
                    $this->dispatch('showToast', [
                        'type' => 'error',
                        'message' => 'Sale record not found.'
                    ]);
                    return;
                }

                $this->paymentDetail = (object) [
                    'id' => null,
                    'sale' => $sale,
                    'amount' => $sale->due_amount,
                    'due_date' => Carbon::now()->addDays(30),
                ];
            }

            $this->duePaymentMethod = $this->paymentDetail->due_payment_method ?? '';
            $this->paymentNote = '';
            $this->duePaymentAttachment = null;
            $this->duePaymentAttachmentPreview = null;
            $this->receivedAmount = '';
            $this->chequeNumber = '';
            $this->bankName = '';
            $this->chequeAmount = '';
            $this->chequeDate = '';
            $this->cheques = [];

            $this->dispatch('openModal', 'payment-detail-modal');
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Error loading payment details: ' . $e->getMessage()
            ]);
        }
    }

    public function addCheque()
    {
        $this->validate([
            'chequeNumber' => 'required',
            'bankName' => 'required',
            'chequeAmount' => 'required|numeric|min:0.01',
            'chequeDate' => 'required|date',
        ]);

        $this->cheques[] = [
            'number' => $this->chequeNumber,
            'bank' => $this->bankName,
            'amount' => floatval($this->chequeAmount),
            'date' => $this->chequeDate,
        ];

        $this->chequeNumber = '';
        $this->bankName = '';
        $this->chequeAmount = '';
        $this->chequeDate = '';
    }

    public function removeCheque($index)
    {
        if (isset($this->cheques[$index])) {
            array_splice($this->cheques, $index, 1);
        }
    }

    public function submitPayment()
    {
        $this->validate([
            'receivedAmount' => 'nullable|numeric|min:0',
            'duePaymentAttachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if (!$this->paymentId) {
                // Create a new payment record for the sale
                $newPayment = Payment::create([
                    'sale_id' => $this->paymentDetail->sale->id,
                    'amount' => $this->paymentDetail->amount,
                    'due_date' => $this->paymentDetail->due_date,
                    'status' => null,
                    'is_completed' => false,
                ]);
                $this->paymentId = $newPayment->id;
                $this->paymentDetail->id = $newPayment->id;
            }

            $payment = Payment::findOrFail($this->paymentId);

            // Store attachment if provided
            $attachmentPath = $payment->due_payment_attachment;
            if ($this->duePaymentAttachment) {
                $receiptName = time() . '-payment-' . $payment->id . '.' . $this->duePaymentAttachment->getClientOriginalExtension();
                $this->duePaymentAttachment->storeAs('public/due-receipts', $receiptName);
                $attachmentPath = "due-receipts/{$receiptName}";
            }

            $cashAmount = floatval($this->receivedAmount) ?: 0;
            $chequeTotal = collect($this->cheques)->sum('amount');
            $totalPaid = $cashAmount + $chequeTotal;

            if ($totalPaid <= 0) {
                DB::rollBack();
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Please enter a cash amount, add cheque(s), or both.'
                ]);
                return;
            }

            if ($totalPaid > $payment->amount) {
                DB::rollBack();
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Total payment exceeds due amount.'
                ]);
                return;
            }
            // Update payment record
            $payment->update([
                'amount' => $totalPaid,
                'due_payment_method' => $cashAmount > 0 && $chequeTotal > 0 ? 'cash+cheque' : ($chequeTotal > 0 ? 'cheque' : 'cash'),
                'due_payment_attachment' => $attachmentPath,
                'status' => 'Paid',
                'is_completed' => true,
                'payment_date' => now(),
            ]);

            // Save cheques if any
            foreach ($this->cheques as $cheque) {
                Cheque::create([
                    'cheque_number' => $cheque['number'],
                    'cheque_date'   => $cheque['date'],
                    'bank_name'     => $cheque['bank'],
                    'cheque_amount' => $cheque['amount'],
                    'status'        => 'pending',
                    'customer_id'   => $payment->sale->customer_id,
                    'payment_id'    => $payment->id,
                ]);
            }

            // Add a note to track this payment submission
            if ($this->paymentNote) {
                $payment->sale->update([
                    'notes' => ($payment->sale->notes ? $payment->sale->notes . "\n" : '') .
                        "Payment received on " . now()->format('Y-m-d H:i') . ": " . $this->paymentNote
                ]);
            }
            $sale = $payment->sale; // using the relationship you already used above
            $remainingAmount = $sale->due_amount - $totalPaid;

            $sale->update([
                'due_amount' => $remainingAmount
            ]);
            // dd($sale->due_amount);
            // dd($remainingAmount);
            if ($remainingAmount > 0) {
                Payment::create([
                    'sale_id' => $payment->sale_id,
                    'amount' => $remainingAmount,
                    'due_date' => $payment->due_date,
                    'status' => null,
                    'is_completed' => false,
                ]);
            }

            // dd($remainingAmount);

            DB::commit();

            $this->dispatch('closeModal', 'payment-detail-modal');
            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Payment submitted successfully and sent for admin approval'
            ]);

            $this->reset([
                'paymentDetail',
                'duePaymentMethod',
                'duePaymentAttachment',
                'paymentNote',
                'receivedAmount',
                'chequeNumber',
                'bankName',
                'chequeAmount',
                'chequeDate',
                'cheques'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to submit payment: ' . $e->getMessage()
            ]);
        }
    }

    private function getFilePreviewInfo($file)
    {
        if (!$file) return null;

        $result = [
            'type' => 'file',
            'name' => $file->getClientOriginalName(),
            'preview' => null,
        ];

        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $result['type'] = 'image';
            $result['preview'] = $file->temporaryUrl();
        } elseif ($extension === 'pdf') {
            $result['type'] = 'pdf';
        } else {
            $result['icon'] = 'bi-file-earmark';
            $result['color'] = 'text-gray-600';
        }

        return $result;
    }

    public function resetFilters()
    {
        $this->filters = [
            'status' => '',
            'dateRange' => '',
        ];
    }

    public function printDuePayments()
    {
        $this->dispatch('print-due-payments');
    }

    public function render()
    {
        $perPage = 10; // Number of items per page

        // Get all sales with due amounts not equal to 0
        $salesWithDue = Sale::where('due_amount', '!=', 0)
            ->where('due_amount', '>', 0)
            ->with(['customer']);

        // Apply search to sales
        if (!empty($this->search)) {
            $salesWithDue->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $salesWithDue = $salesWithDue->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($s) {
                return (object) [
                    'is_payment' => false,
                    'id' => $s->id,
                    'sale' => $s,
                    'amount' => $s->due_amount,
                    'status' => null, // Always null for sales with due amounts (pending)
                    'due_date' => $s->updated_at ?? Carbon::now()->addDays(30),
                    'created_at' => $s->created_at
                ];
            });

        // If status filter is applied and not for pending, return empty
        $showPending = (!isset($this->filters['status']) || $this->filters['status'] === '' || $this->filters['status'] === 'null');

        if (!$showPending) {
            $salesWithDue = collect();
        }

        // We're now only showing sales, not payments
        $payments = collect();

        // Combine items (only sales with due amounts)
        $items = $salesWithDue->sortByDesc('created_at')->values();

        // Manually paginate the collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentItems = $items->forPage($currentPage, $perPage);
        $items = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Set status badges
        foreach ($items as $item) {
            if ($item->status === null) {
                $item->status_badge = 'Pending';
            } elseif ($item->status === 'Paid') {
                $item->status_badge = 'Paid';
            } elseif ($item->status === 'pending') {
                $item->status_badge = 'Pending Approval';
            } elseif ($item->status === 'approved') {
                $item->status_badge = 'Approved';
            } elseif ($item->status === 'rejected') {
                $item->status_badge = 'Rejected';
            } else {
                $item->status_badge = 'Unknown';
            }
        }

        // Stats: Calculate based on all items before pagination
        $duePaymentsCount = $salesWithDue->count();
        $totalDue = $salesWithDue->sum('amount');

        // Today stats - Filter today's sales with due amounts
        $todaySalesWithDue = Sale::where('due_amount', '!=', 0)
            ->where('due_amount', '>', 0)
            ->whereDate('created_at', today())
            ->with(['customer']);

        // Apply search to today's sales
        if (!empty($this->search)) {
            $todaySalesWithDue->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $todaySalesWithDue = $todaySalesWithDue->get();

        $todayDuePaymentsCount = $todaySalesWithDue->count();
        $todayDuePayments = $todaySalesWithDue->sum('due_amount');

        return view('livewire.admin.due-payments', [
            'items' => $items, // This is our paginated collection
            'duePaymentsCount' => $duePaymentsCount,
            'todayDuePayments' => $todayDuePayments,
            'todayDuePaymentsCount' => $todayDuePaymentsCount,
            'totalDue' => $totalDue,
            'cheques' => $this->cheques,
            'chequeNumber' => $this->chequeNumber,
            'bankName' => $this->bankName,
            'chequeAmount' => $this->chequeAmount,
            'chequeDate' => $this->chequeDate,
        ]);
    }
}
