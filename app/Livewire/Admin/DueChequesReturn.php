<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Cheque;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Due Cheques')]

class DueChequesReturn extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    // Remove public $chequeDetails - it will be passed directly in render()
    public $cheques = []; // Temporary array for new cheques
    public $chequeNumber;
    public $bankName;
    public $chequeAmount;
    public $chequeDate;
    public $cashAmount = 0;
    public $note;
    public $selectedChequeId;
    public $originalCheque; // Store original cheque details for modal

    // For Complete with Cash
    public $completeCashAmount;
    public $completeNote;
    public $banks = [];

    public function mount()
    {
        $this->loadBanks();
    }

    public function loadBanks()
    {
        $this->banks = [
            'Bank of Ceylon (BOC)',
            'Commercial Bank of Ceylon (ComBank)',
            'Hatton National Bank (HNB)',
            'People\'s Bank',
            'Sampath Bank',
            'National Development Bank (NDB)',
            'DFCC Bank',
            'Nations Trust Bank (NTB)',
            'Seylan Bank',
            'Amana Bank',
            'Cargills Bank',
            'Pan Asia Banking Corporation',
            'Union Bank of Colombo',
            'Bank of China Ltd',
            'Citibank, N.A.',
            'Habib Bank Ltd',
            'Indian Bank',
            'Indian Overseas Bank',
            'MCB Bank Ltd',
            'Public Bank Berhad',
            'Standard Chartered Bank',
        ];
    }

    // Open modal for re-entry
    public function openReentryModal($chequeId)
    {
        $this->selectedChequeId = $chequeId;
        $this->originalCheque = Cheque::with('customer')->find($chequeId);
        $this->reset(['chequeNumber', 'bankName', 'chequeAmount', 'chequeDate', 'cashAmount', 'note', 'cheques']);
        $this->dispatch('open-reentry-modal');
    }

    // Add a new cheque to temporary array
    public function addCheque()
    {
        $this->validate([
            'chequeNumber' => 'required|string|max:255',
            'bankName' => 'required|string|max:255',
            'chequeAmount' => 'required|numeric|min:0.01',
            'chequeDate' => 'required|date|after_or_equal:today',
        ], [
            'chequeNumber.required' => 'Cheque number is required.',
            'bankName.required' => 'Bank name is required.',
            'chequeAmount.required' => 'Cheque amount is required.',
            'chequeAmount.min' => 'Cheque amount must be greater than 0.',
            'chequeDate.required' => 'Cheque date is required.',
            'chequeDate.after_or_equal' => 'Cheque date cannot be in the past.',
        ]);

        $this->cheques[] = [
            'number' => $this->chequeNumber,
            'bank' => $this->bankName,
            'amount' => $this->chequeAmount,
            'date' => $this->chequeDate,
        ];

        $this->reset(['chequeNumber', 'bankName', 'chequeAmount', 'chequeDate']);
        
        $this->dispatch('notify', type: 'success', message: 'Cheque added successfully.');
    }

    // Remove cheque from temporary array
    public function removeCheque($index)
    {
        if (isset($this->cheques[$index])) {
            unset($this->cheques[$index]);
            $this->cheques = array_values($this->cheques);
            $this->dispatch('notify', type: 'success', message: 'Cheque removed successfully.');
        }
    }

    // Save new cheque(s) and/or cash, update original cheque status
    public function submitNewCheque()
    {
        $originalCheque = Cheque::find($this->selectedChequeId);

        if (!$originalCheque) {
            $this->dispatch('notify', type: 'error', message: 'Original cheque not found.');
            return;
        }

        // Calculate total amount of new cheques + cash
        $totalNewChequeAmount = array_sum(array_column($this->cheques, 'amount'));
        $totalAmount = $totalNewChequeAmount + ($this->cashAmount ?: 0);

        // Validate that at least one payment method is provided
        if (empty($this->cheques) && (!$this->cashAmount || $this->cashAmount <= 0)) {
            $this->addError('general', 'Please add at least one cheque or provide a cash amount.');
            return;
        }

        // Custom validation for total amount
        if ($totalAmount != $originalCheque->cheque_amount) {
            $this->addError('general', 'The total amount of cheques and cash (Rs. ' . number_format($totalAmount, 2) . ') must equal the original cheque amount (Rs. ' . number_format($originalCheque->cheque_amount, 2) . ').');
            return;
        }

        // Additional validation
        $this->validate([
            'cashAmount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Save new cheques with payment_id from original cheque
            foreach ($this->cheques as $cheque) {
                Cheque::create([
                    'customer_id'     => $originalCheque->customer_id,
                    'cheque_number'   => $cheque['number'],
                    'bank_name'       => $cheque['bank'],
                    'cheque_amount'   => $cheque['amount'],
                    'cheque_date'     => $cheque['date'],
                    'status'          => 'pending',
                    'payment_id'      => $originalCheque->payment_id,
                    
                ]);
            }

            // If cash amount is provided, create a new cash payment
            if ($this->cashAmount > 0) {
                $originalPayment = Payment::find($originalCheque->payment_id);
                
                if ($originalPayment) {
                    Payment::create([
                        'sale_id'         => $originalPayment->sale_id,
                        'admin_sale_id'   => $originalPayment->admin_sale_id,
                        'amount'          => $this->cashAmount,
                        'payment_method'  => 'cash',
                        'is_completed'    => true,
                        'payment_date'    => now(),
                        'status'          => 'Paid',
                        'notes'           => $this->note,
                    ]);
                }
            }

            // Update original cheque status to 'cancel'
            $originalCheque->update([
                'status' => 'cancel',
                
            ]);

            DB::commit();

            // Clear modal and array
            $this->cheques = [];
            $this->originalCheque = null;
            $this->reset(['cashAmount', 'note', 'chequeNumber', 'bankName', 'chequeAmount', 'chequeDate', 'selectedChequeId']);
            
            $this->dispatch('close-reentry-modal');
            $this->dispatch('notify', type: 'success', message: 'New cheque(s) and/or cash submitted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error submitting new cheque/cash: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    // Open modal for complete with cash
    public function openCompleteModal($chequeId)
    {
        $this->selectedChequeId = $chequeId;
        $this->originalCheque = Cheque::with('customer')->find($chequeId);
        $this->completeCashAmount = $this->originalCheque->cheque_amount;
        $this->reset(['completeNote']);
        $this->dispatch('open-complete-modal');
    }

    // Submit complete with cash
    public function submitCompleteWithCash()
    {
        $this->validate([
            'completeCashAmount' => 'required|numeric|min:0.01',
            'completeNote' => 'required|string|max:500',
        ], [
            'completeCashAmount.required' => 'Cash amount is required.',
            'completeCashAmount.min' => 'Cash amount must be greater than 0.',
            'completeNote.required' => 'Note is required.',
        ]);

        $originalCheque = Cheque::find($this->selectedChequeId);

        if (!$originalCheque) {
            $this->dispatch('notify', type: 'error', message: 'Original cheque not found.');
            return;
        }

        // Verify if cash amount matches the original cheque amount
        if ($this->completeCashAmount != $originalCheque->cheque_amount) {
            $this->addError('completeCashAmount', 'Cash amount (Rs. ' . number_format($this->completeCashAmount, 2) . ') must match the original cheque amount (Rs. ' . number_format($originalCheque->cheque_amount, 2) . ').');
            return;
        }

        DB::beginTransaction();

        try {
            $originalPayment = Payment::find($originalCheque->payment_id);

            if ($originalPayment) {
                // Create new cash payment
                Payment::create([
                    'sale_id'         => $originalPayment->sale_id,
                    'admin_sale_id'   => $originalPayment->admin_sale_id,
                    'amount'          => $this->completeCashAmount,
                    'payment_method'  => 'cash',
                    'is_completed'    => true,
                    'payment_date'    => now(),
                    'status'          => 'Paid',
                    'notes'           => $this->completeNote,
                ]);
            }

            // Update original cheque status to 'cancel'
            $originalCheque->update([
                'status' => 'cancel',
                'note'   => $this->completeNote,
            ]);

            DB::commit();

            $this->originalCheque = null;
            $this->reset(['completeCashAmount', 'completeNote', 'selectedChequeId']);
            
            $this->dispatch('close-complete-modal');
            $this->dispatch('notify', type: 'success', message: 'Cheque completed with cash successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error completing cheque with cash: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $chequeDetails = Cheque::with('customer')
            ->where('status', 'return')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.due-cheques-return', [
            'chequeDetails' => $chequeDetails
        ]);
    }
}