<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Cheque;
use Livewire\Component;
use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Models\AdminSale;
use App\Models\ProductDetail;
use App\Models\SalesItem;

#[Title("Store Billing")]
#[Layout('components.layouts.admin')]
class StoreBilling extends Component
{
    use WithFileUploads;

    public $search = '';
    public $searchResults = [];
    public $cart = [];
    public $quantities = [];
    public $discounts = [];
    public $prices = [];
    public $quantityTypes = [];
    public $productDetails = null;
    public $subtotal = 0;
    public $totalDiscount = 0;
    public $grandTotal = 0;

    // Overall Discount Properties
    public $discountType = 'percentage'; // Default to percentage
    public $discountValue = 0;
    public $calculatedDiscount = 0;

    public $customers = [];
    public $customerId = null;
    public $customerType = 'wholesale';

    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerEmail = '';
    public $newCustomerType = 'wholesale';
    public $newCustomerAddress = '';
    public $newCustomerNotes = '';

    public $saleNotes = '';
    public $paymentType = 'partial';
    public $paymentMethod = '';
    public $paymentReceiptImage;
    public $paymentReceiptImagePreview = null;
    public $bankName = '';

    public $initialPaymentAmount = 0;
    public $initialPaymentMethod = '';
    public $initialPaymentReceiptImage;
    public $initialPaymentReceiptImagePreview = null;
    public $initialBankName = '';

    public $balanceAmount = 0;
    public $balancePaymentMethod = '';
    public $balanceDueDate = '';
    public $balancePaymentReceiptImage;
    public $balancePaymentReceiptImagePreview = null;
    public $balanceBankName = '';

    public $lastSaleId = null;
    public $showReceipt = false;
    public $receipt = null;

    public $cashAmount = 0;
    public $cheques = [];
    public $newCheque = [
        'number' => '',
        'bank' => '',
        'date' => '',
        'amount' => '',
    ];
    public $duePaymentMethod = '';
    public $duePaymentAttachment;
    public $duePaymentAttachmentPreview = null;

    public $banks = [];

    protected $listeners = ['quantityUpdated' => 'updateTotals'];

    public function mount()
    {
        $this->loadCustomers();
        $this->loadBanks();
        $this->updateTotals();
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));
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

    public function loadCustomers()
    {
        $this->customers = Customer::orderBy('name')->get();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->searchResults = ProductDetail::join('product_categories', 'product_categories.id', '=', 'product_details.category_id')
                ->where('product_details.product_name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('product_details.product_code', 'LIKE', '%' . $this->search . '%')
                ->select('product_details.*')
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addToCart($productId)
    {
        $product = ProductDetail::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => 'This product is out of stock.']);
            return;
        }

        if (isset($this->cart[$productId])) {
            if (($this->quantities[$productId] + 1) > $product->stock_quantity) {
                $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Maximum available stock is {$product->stock_quantity}"]);
                return;
            }
            $this->quantities[$productId]++;
        } else {
            $newItem = [
                $productId => [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'code' => $product->product_code,
                    'brand' => $product->brand->name ?? 'N/A',
                    'image' => $product->image,
                    'price' => $product->selling_price,
                    'stock_quantity' => $product->stock_quantity,
                ]
            ];

            $this->cart = $newItem + $this->cart;
            $this->prices[$productId] = $product->selling_price ?? 0;
            $this->quantities[$productId] = 1;
            $this->discounts[$productId] = $product->discount_price ?? 0;
            $this->quantityTypes[$productId] = '';
        }

        $this->search = '';
        $this->searchResults = [];
        $this->updateTotals();
    }

    public function updatedQuantities($value, $key)
    {
        $this->validateQuantity((int)$key);
    }

    public function updatedPrices($value, $key)
    {
        $value = max(0, floatval($value));
        $this->prices[$key] = $value;

        if (isset($this->discounts[$key]) && $this->discounts[$key] > $value) {
            $this->discounts[$key] = $value;
        }

        $this->updateTotals();
    }

    public function updatedDiscounts($value, $key)
    {
        $price = $this->prices[$key] ?? $this->cart[$key]['price'] ?? 0;
        $this->discounts[$key] = max(0, min(floatval($value), $price));
        $this->updateTotals();
    }

    public function updatedDiscountType()
    {
        $this->discountValue = 0;
        $this->updateTotals();
    }

    public function updatedDiscountValue()
    {
        $this->updateTotals();
    }

    public function validateQuantity($productId)
    {
        if (!isset($this->cart[$productId]) || !isset($this->quantities[$productId])) {
            return;
        }

        $maxAvailable = $this->cart[$productId]['stock_quantity'];
        $currentQuantity = filter_var($this->quantities[$productId], FILTER_VALIDATE_INT);

        if ($currentQuantity === false || $currentQuantity < 1) {
            $this->quantities[$productId] = 1;
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => 'Minimum quantity is 1']);
        } elseif ($currentQuantity > $maxAvailable) {
            $this->quantities[$productId] = $maxAvailable;
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Maximum stock is {$maxAvailable}"]);
        }
        $this->updateTotals();
    }

    public function updateQuantity($productId, $quantity)
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $maxAvailable = $this->cart[$productId]['stock_quantity'];

        if ($quantity <= 0) {
            $quantity = 1;
        } elseif ($quantity > $maxAvailable) {
            $quantity = $maxAvailable;
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }

        $this->quantities[$productId] = $quantity;
        $this->updateTotals();
    }

    public function updatePrice($productId, $price)
    {
        if (!isset($this->cart[$productId])) return;

        $price = floatval($price);
        if ($price < 0) $price = 0;

        $this->cart[$productId]['price'] = $price;
        $this->prices[$productId] = $price;
        $this->updateTotals();
    }

    public function updateDiscount($productId, $discount)
    {
        if (!isset($this->cart[$productId])) return;

        $this->discounts[$productId] = max(0, min($discount, $this->cart[$productId]['price'] ?? 0));
        $this->updateTotals();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        unset($this->quantities[$productId]);
        unset($this->discounts[$productId]);
        unset($this->prices[$productId]);
        unset($this->quantityTypes[$productId]);
        $this->updateTotals();
    }

    public function showDetail($productId)
    {
        $this->productDetails = ProductDetail::select(
            'id',
            'product_name',
            'product_code',
            'selling_price',
            'stock_quantity',
            'damage_quantity',
            'sold',
            DB::raw("(stock_quantity) as available_stock")
        )
            ->where('id', $productId)
            ->first();

        $this->js('$("#viewDetailModal").modal("show")');
    }

    public function calculateOverallDiscount()
    {
        $baseAmount = $this->subtotal - $this->totalDiscount;

        if ($baseAmount <= 0) {
            $this->calculatedDiscount = 0;
            return;
        }

        if ($this->discountType === 'percentage') {
            $percentage = min(100, max(0, floatval($this->discountValue)));
            $this->calculatedDiscount = ($baseAmount * $percentage) / 100;
        } else {
            // Amount-based discount
            $this->calculatedDiscount = min($baseAmount, max(0, floatval($this->discountValue)));
        }
    }

    public function updateTotals()
    {
        $this->subtotal = 0;
        $this->totalDiscount = 0;

        foreach ($this->cart as $id => $item) {
            $price = $this->prices[$id] ?? $item['price'] ?? 0;
            $qty = $this->quantities[$id] ?? 1;
            $discount = $this->discounts[$id] ?? 0;
            $this->subtotal += $price * $qty;
            $this->totalDiscount += $discount * $qty;
        }

        // Calculate overall discount
        $this->calculateOverallDiscount();

        // Grand total = subtotal - item discounts - overall discount
        $this->grandTotal = $this->subtotal - $this->totalDiscount - $this->calculatedDiscount;

        // Ensure grand total doesn't go negative
        if ($this->grandTotal < 0) {
            $this->grandTotal = 0;
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->quantities = [];
        $this->discounts = [];
        $this->prices = [];
        $this->quantityTypes = [];
        $this->discountType = 'percentage';
        $this->discountValue = 0;
        $this->calculatedDiscount = 0;
        $this->updateTotals();
    }

    public function saveCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required',
        ]);

        $customer = Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
            'email' => $this->newCustomerEmail,
            'type' => $this->newCustomerType,
            'address' => $this->newCustomerAddress,
            'notes' => $this->newCustomerNotes,
        ]);

        $this->loadCustomers();
        $this->customerId = $customer->id;

        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->newCustomerEmail = '';
        $this->newCustomerAddress = '';
        $this->newCustomerNotes = '';

        $this->js('$("#addCustomerModal").modal("hide")');
        $this->js('swal.fire("Success", "Customer added successfully!", "success")');
    }

    public function calculateBalanceAmount()
    {
        if ($this->paymentType == 'partial') {
            $this->balanceAmount = $this->grandTotal - $this->initialPaymentAmount;
        } else {
            $this->balanceAmount = 0;
        }
    }

    public function updatedPaymentType($value)
    {
        if ($value == 'partial') {
            $this->calculateBalanceAmount();
        } else {
            $this->balanceAmount = 0;
        }
    }

    public function addCheque()
    {
        $this->validate([
            'newCheque.number' => 'required|string|max:255',
            'newCheque.bank' => 'required|string|max:255',
            'newCheque.date' => 'required|date|after_or_equal:today',
            'newCheque.amount' => 'required|numeric|min:0.01',
        ], [
            'newCheque.number.required' => 'Cheque number is required.',
            'newCheque.bank.required' => 'Bank name is required.',
            'newCheque.date.required' => 'Cheque date is required.',
            'newCheque.date.after_or_equal' => 'Cheque date cannot be in the past.',
            'newCheque.amount.required' => 'Cheque amount is required.',
            'newCheque.amount.min' => 'Cheque amount must be greater than 0.',
        ]);

        $chequeDate = strtotime($this->newCheque['date']);
        $today = strtotime(date('Y-m-d'));

        if ($chequeDate < $today) {
            $this->js('swal.fire("Error", "Cheque date cannot be in the past. Please select today\'s date or a future date.", "error")');
            return;
        }

        $this->cheques[] = [
            'number' => $this->newCheque['number'],
            'bank' => $this->newCheque['bank'],
            'date' => $this->newCheque['date'],
            'amount' => floatval($this->newCheque['amount']),
        ];

        $this->resetChequeForm();
    }

    public function resetChequeForm()
    {
        $this->newCheque = [
            'number' => '',
            'bank' => '',
            'date' => '',
            'amount' => '',
        ];
    }

    public function removeCheque($index)
    {
        if (isset($this->cheques[$index])) {
            unset($this->cheques[$index]);
            $this->cheques = array_values($this->cheques);
        }
    }

    public function completeSale()
    {

        if (empty($this->cart)) {
            $this->js('swal.fire("Error", "Please add items to the cart.", "error")');
            return;
        }

        $this->validate([
            'customerId' => 'required',
            'paymentType' => 'required|in:full,partial,credit',
        ]);

        $totalChequeAmount = collect($this->cheques)->sum('amount');
        $totalPaid = floatval($this->cashAmount) + floatval($totalChequeAmount);

        if ($this->paymentType === 'full') {
            $this->cashAmount = $this->grandTotal;
            $totalPaid = $this->grandTotal;
        }

        if ($this->paymentType === 'full') {
            if ($totalPaid != $this->grandTotal) {
                $this->js('swal.fire("Error", "Full payment must equal the grand total.", "error")');
                return;
            }
        }

        if ($this->paymentType === 'partial') {
            if (floatval($this->cashAmount) < 0) {
                $this->js('swal.fire("Error", "Cash amount cannot be negative.", "error")');
                return;
            }

            if (floatval($this->cashAmount) > 0 && floatval($this->cashAmount) > $this->grandTotal) {
                $this->js('swal.fire("Error", "Cash amount cannot exceed the grand total.", "error")');
                return;
            }

            if (!empty($this->cheques)) {
                foreach ($this->cheques as $cheque) {
                    if (floatval($cheque['amount']) <= 0) {
                        $this->js('swal.fire("Error", "All cheque amounts must be greater than 0.", "error")');
                        return;
                    }
                }
            }
            if (($totalPaid < 0) || ($totalPaid > $this->grandTotal)) {
                $this->js('swal.fire("Error", "For partial payments, the total paid amount must be greater than 0 and less than the grand total.", "error")');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $totalChequeAmount = collect($this->cheques)->sum('amount');
            $totalPaid = floatval($this->cashAmount) + floatval($totalChequeAmount);

            if ($this->paymentType === 'full') {
                $paymentStatus = 'paid';
                $dueAmount = 0;
            } else {
                $paymentStatus = 'pending';
                $dueAmount = $this->grandTotal - (floatval($this->cashAmount) + floatval($totalChequeAmount));
            }

            // Calculate total discount (item discounts + overall discount)
            $totalDiscountAmount = $this->totalDiscount + $this->calculatedDiscount;

            $sale = Sale::create([
                'invoice_number'   => Sale::generateInvoiceNumber(),
                'customer_id'      => $this->customerId,
                'user_id'          => auth()->id(),
                'customer_type'    => Customer::find($this->customerId)->type,
                'subtotal'         => $this->subtotal,
                'discount_amount'  => $totalDiscountAmount,
                'total_amount'     => $this->grandTotal,
                'payment_type'     => $this->paymentType,
                'payment_status'   => $paymentStatus,
                'notes'            => $this->saleNotes ?: null,
                'due_amount'       => $dueAmount,
            ]);

            foreach ($this->cart as $id => $item) {
                $quantityToSell = $this->quantities[$id];
                $price = $this->prices[$id] ?? $item['price'];
                $itemDiscount = $this->discounts[$id] ?? 0;
                $total = ($price * $quantityToSell) - ($itemDiscount * $quantityToSell);

                SalesItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $quantityToSell,
                    'price' => $price,
                    'discount' => $itemDiscount,
                    'total' => $total,
                ]);

                $productStock = ProductDetail::find($item['id']);
                $productStock->stock_quantity -= $quantityToSell;
                $productStock->sold += $quantityToSell;
                $productStock->save();
            }

            if ($this->paymentType === 'full' || floatval($this->cashAmount) > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => floatval($this->cashAmount),
                    'payment_method' => 'cash',
                    'is_completed' => true,
                    'status' => 'Paid',
                    'payment_date' => now(),
                ]);
            }

            foreach ($this->cheques as $cheque) {
                $payment = Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => floatval($cheque['amount']),
                    'payment_method' => 'cheque',
                    'payment_reference' => $cheque['number'],
                    'bank_name' => $cheque['bank'],
                    'is_completed' => false,
                    'status' => 'Pending',
                    'payment_date' => $cheque['date'],
                ]);

                Cheque::create([
                    'cheque_number' => $cheque['number'],
                    'cheque_date' => $cheque['date'],
                    'bank_name' => $cheque['bank'],
                    'cheque_amount' => $cheque['amount'],
                    'status' => 'pending',
                    'customer_id' => $this->customerId,
                    'payment_id' => $payment->id,
                ]);
            }

            DB::commit();

            $this->receipt = Sale::with(['customer', 'items.product', 'payments'])
                ->find($sale->id);

            $this->dispatch('showModal', ['modalId' => 'receiptModal']);

            $this->js('swal.fire("Success", "Sale completed successfully!", "success")');
            $this->clearCart();
            $this->resetPaymentInfo();
        } catch (Exception $e) {
            DB::rollBack();
            $this->js('swal.fire("Error", "' . $e->getMessage() . '", "error")');
        }
    }

    public function resetPaymentInfo()
    {
        $this->paymentType = 'full';
        $this->paymentMethod = '';
        $this->paymentReceiptImage = null;
        $this->paymentReceiptImagePreview = null;
        $this->bankName = '';

        $this->cashAmount = 0;
        $this->cheques = [];
        $this->newCheque = [
            'number' => '',
            'bank' => '',
            'date' => '',
            'amount' => '',
        ];

        $this->discountType = 'percentage';
        $this->discountValue = 0;
        $this->calculatedDiscount = 0;

        $this->initialPaymentAmount = 0;
        $this->initialPaymentMethod = '';
        $this->initialPaymentReceiptImage = null;
        $this->initialPaymentReceiptImagePreview = null;
        $this->initialBankName = '';

        $this->balanceAmount = 0;
        $this->balancePaymentMethod = '';
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));
        $this->balancePaymentReceiptImage = null;
        $this->balancePaymentReceiptImagePreview = null;
        $this->balanceBankName = '';
    }

    public function render()
    {
        return view('livewire.admin.store-billing');
    }
}
