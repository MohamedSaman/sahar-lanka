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
    public $banks=[];

    public $duePayment;
    protected $listeners = ['refreshPayments' => '$refresh'];

    public function mount() {
        $this->loadBanks();
    }

    public function loadBanks()
    {
        $this->banks = [
            'Bank of Ceylon (BOC)'=>'Bank of Ceylon (BOC)',
            'Commercial Bank of Ceylon (ComBank)'=>'Commercial Bank of Ceylon (ComBank)',
            'Hatton National Bank (HNB)'=>'Hatton National Bank (HNB)',
            'People\'s Bank'=>'People\'s Bank',
            'Sampath Bank'=>'Sampath Bank',
            'National Development Bank (NDB)'=>'National Development Bank (NDB)',
            'DFCC Bank'=>'DFCC Bank',
            'Nations Trust Bank (NTB)'=>'Nations Trust Bank (NTB)',
            'Seylan Bank'=>'Seylan Bank',
            'Amana Bank'=>'Amana Bank',
            'Cargills Bank'=>'Cargills Bank',
            'Pan Asia Banking Corporation'=>'Pan Asia Banking Corporation',
            'Union Bank of Colombo'=>'Union Bank of Colombo',
            'Bank of China Ltd'=>'Bank of China Ltd',
            'Citibank, N.A.'=>'Citibank, N.A.',
            'Habib Bank Ltd'=>'Habib Bank Ltd',
            'Indian Bank'=>'Indian Bank',
            'Indian Overseas Bank'=>'Indian Overseas Bank',
            'MCB Bank Ltd'=>'MCB Bank Ltd',
            'Public Bank Berhad'=>'Public Bank Berhad',
            'Standard Chartered Bank'=>'Standard Chartered Bank',
        ];
    }

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
            // Since we're only showing sales with due amounts, we always get sale details
            $sale = Sale::with(['customer', 'items'])->find($id);

            if (!$sale) {
                $this->js('swal.fire("Error", "Sale record not found.", "error")');
                return;
            }

            if ($sale->due_amount <= 0) {
                $this->js('swal.fire("Error", "This sale has no due amount remaining.", "error")');
                return;
            }

            $this->paymentDetail = (object) [
                'id' => null,
                'sale' => $sale,
                'amount' => $sale->due_amount,
                'due_date' => Carbon::now()->addDays(30),
            ];

            $this->duePaymentMethod = '';
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
            $this->js('swal.fire("Error", "Error loading payment details: ' . addslashes($e->getMessage()) . '", "error")');
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

            // Get the sale record
            $sale = Sale::findOrFail($this->paymentDetail->sale->id);

            $cashAmount = floatval($this->receivedAmount) ?: 0;
            $chequeTotal = collect($this->cheques)->sum('amount');
            $totalPaid = $cashAmount + $chequeTotal;

            if ($totalPaid <= 0) {
                DB::rollBack();
                $this->js('swal.fire("Error", "Please enter a cash amount, add cheque(s), or both.", "error")');
                return;
            }

            if ($totalPaid > $sale->due_amount) {
                DB::rollBack();
                $this->js('swal.fire("Error", "Total payment exceeds due amount. Due amount: Rs.' . number_format($sale->due_amount, 2) . '", "error")');
                return;
            }

            // Store attachment if provided
            $attachmentPath = null;
            if ($this->duePaymentAttachment) {
                $receiptName = time() . '-payment-' . $sale->id . '.' . $this->duePaymentAttachment->getClientOriginalExtension();
                $this->duePaymentAttachment->storeAs('public/due-receipts', $receiptName);
                $attachmentPath = "due-receipts/{$receiptName}";
            }

            // Create payment record
            $payment = Payment::create([
                'sale_id' => $sale->id,
                'amount' => $totalPaid,
                'payment_method' => $cashAmount > 0 && $chequeTotal > 0 ? 'cash+cheque' : ($chequeTotal > 0 ? 'cheque' : 'cash'),
                'due_payment_attachment' => $attachmentPath,
                'status' => 'Paid',
                'is_completed' => true,
                'payment_date' => now(),
                'due_date' => now(),
            ]);

            // Save cheques if any
            foreach ($this->cheques as $cheque) {
                Cheque::create([
                    'cheque_number' => $cheque['number'],
                    'cheque_date'   => $cheque['date'],
                    'bank_name'     => $cheque['bank'],
                    'cheque_amount' => $cheque['amount'],
                    'status'        => 'pending',
                    'customer_id'   => $sale->customer_id,
                    'payment_id'    => $payment->id,
                ]);
            }

            // Update sale's due amount
            $newDueAmount = $sale->due_amount - $totalPaid;
            $sale->update([
                'due_amount' => $newDueAmount
            ]);

            // Add payment note to sale if provided
            if ($this->paymentNote) {
                $sale->update([
                    'notes' => ($sale->notes ? $sale->notes . "\n" : '') .
                        "Payment received on " . now()->format('Y-m-d H:i') . ": " . $this->paymentNote
                ]);
            }

            DB::commit();

            $this->js('swal.fire("Success", "Payment received successfully!", "success").then(() => {
                Livewire.dispatch("closeModal", "payment-detail-modal");
                window.location.reload();
            });');
            
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
            DB::rollBack();
            $this->js('swal.fire("Error", "Error processing payment: ' . addslashes($e->getMessage()) . '", "error")');
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
        $perPage = 10;

        // Get only sales with due amounts not equal to 0
        $salesQuery = Sale::where('due_amount', '>', 0)
            ->with(['customer']);

        // Apply search filter
        if (!empty($this->search)) {
            $salesQuery->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Get all sales with due amounts
        $salesWithDue = $salesQuery->orderBy('created_at', 'desc')->get();

        // Map sales to the expected format
        $items = $salesWithDue->map(function ($sale) {
            return (object) [
                'is_payment' => false,
                'id' => $sale->id,
                'sale' => $sale,
                'amount' => $sale->due_amount,
                'status' => null, // Always pending for sales with due amounts
                'due_date' => $sale->updated_at ?? Carbon::now()->addDays(30),
                'created_at' => $sale->created_at
            ];
        });

        // Add status badges
        foreach ($items as $item) {
            $item->status_badge = 'Pending';
        }

        // Paginate the collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentItems = $items->forPage($currentPage, $perPage);
        $duePayments = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Calculate statistics
        $duePaymentsCount = $salesWithDue->count();
        $totalDue = $salesWithDue->sum('due_amount');

        // Today's statistics
        $todaySalesWithDue = Sale::where('due_amount', '>', 0)
            ->whereDate('created_at', today());

        if (!empty($this->search)) {
            $todaySalesWithDue->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $todaySales = $todaySalesWithDue->get();
        $todayDuePaymentsCount = $todaySales->count();
        $todayDuePayments = $todaySales->sum('due_amount');

        return view('livewire.admin.due-payments', [
            'duePayments' => $duePayments,
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