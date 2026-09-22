{{--
    Shared payment method + card fields, used by both the single-item
    checkout (orders/checkout.blade.php) and the cart checkout
    (cart/checkout.blade.php). Expects $paymentMethods and $savedCards
    (a collection, possibly empty) in scope.
--}}
<div class="bg-white border rounded-lg p-5">
    <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        @include('partials.icon', ['name' => 'credit-card', 'class' => 'w-5 h-5 text-orange-600'])
        Payment method
    </h2>
    <div class="space-y-2" id="payment-method-options">
        @foreach($paymentMethods as $value => $label)
            <label class="flex items-center gap-3 border rounded-md px-4 py-3 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                <input type="radio" name="payment_method" value="{{ $value }}" class="payment-method-radio" @checked($loop->first)>
                <span class="text-sm">{{ $label }}</span>
            </label>
        @endforeach
    </div>

    {{-- Card fields — only shown/required when "card" is selected --}}
    <div id="card-fields" class="mt-4 space-y-3 hidden">
        @if($savedCards->isNotEmpty())
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-500">Use a saved card</p>
                @foreach($savedCards as $card)
                    <label class="flex items-center gap-3 border rounded-md px-4 py-2.5 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                        <input type="radio" name="saved_card_id" value="{{ $card->id }}" class="saved-card-radio">
                        <span class="text-sm">{{ $card->label }}</span>
                    </label>
                @endforeach
                <label class="flex items-center gap-3 border rounded-md px-4 py-2.5 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                    <input type="radio" name="saved_card_id" value="" class="saved-card-radio" checked>
                    <span class="text-sm">Use a new card</span>
                </label>
            </div>
        @endif

        <div id="new-card-fields">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Name on card</label>
                <input type="text" name="card_name" placeholder="As shown on the card"
                       class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="mt-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Card number</label>
                <input type="text" name="card_number" id="card-number-input" inputmode="numeric" placeholder="1234 5678 9012 3456" maxlength="19"
                       class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="grid grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Expiry (MM/YY)</label>
                    <input type="text" name="card_expiry" id="card-expiry-input" placeholder="MM/YY" maxlength="5"
                           class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">CVV</label>
                    <input type="text" name="card_cvv" inputmode="numeric" placeholder="123" maxlength="4"
                           class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
            <label class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                <input type="checkbox" name="save_card" value="1">
                Save this card for next time
            </label>
        </div>
    </div>

    <p class="text-xs text-gray-400 mt-3">Demo checkout — this is a made-up card form for testing the flow. No real charge happens, and only the card's brand and last 4 digits are ever saved (never the full number or CVV).</p>
</div>

<script>
    (function () {
        const cardFields = document.getElementById('card-fields');
        const newCardFields = document.getElementById('new-card-fields');
        const radios = document.querySelectorAll('.payment-method-radio');
        const savedCardRadios = document.querySelectorAll('.saved-card-radio');
        const cardNumberInput = document.getElementById('card-number-input');
        const cardExpiryInput = document.getElementById('card-expiry-input');

        function refreshCardVisibility() {
            const selected = document.querySelector('.payment-method-radio:checked');
            cardFields.classList.toggle('hidden', !selected || selected.value !== 'card');
        }
        radios.forEach((r) => r.addEventListener('change', refreshCardVisibility));
        refreshCardVisibility();

        function refreshSavedCardToggle() {
            if (savedCardRadios.length === 0) return;
            const usingNewCard = document.querySelector('.saved-card-radio:checked')?.value === '';
            newCardFields.classList.toggle('hidden', !usingNewCard);
        }
        savedCardRadios.forEach((r) => r.addEventListener('change', refreshSavedCardToggle));
        refreshSavedCardToggle();

        // Auto-format card number with spaces every 4 digits as you type.
        cardNumberInput?.addEventListener('input', (e) => {
            let digits = e.target.value.replace(/\D/g, '').slice(0, 16);
            e.target.value = digits.replace(/(.{4})/g, '$1 ').trim();
        });

        // Auto-insert the slash in MM/YY as you type.
        cardExpiryInput?.addEventListener('input', (e) => {
            let digits = e.target.value.replace(/\D/g, '').slice(0, 4);
            e.target.value = digits.length > 2 ? digits.slice(0, 2) + '/' + digits.slice(2) : digits;
        });
    })();
</script>
