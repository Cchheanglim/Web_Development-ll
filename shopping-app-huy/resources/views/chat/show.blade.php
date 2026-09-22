@extends('layouts.app')
@section('title', 'Chat — ' . $product->title)

@section('content')
    <div class="max-w-2xl mx-auto mt-6">
        <a href="{{ route('products.show', $product) }}" class="text-sm text-orange-600 hover:underline">
            &larr; Back to "{{ $product->title }}"
        </a>

        <div class="bg-white border rounded-lg mt-4 flex flex-col h-[32rem]">
            <div class="border-b p-4">
                <p class="font-semibold">
                    {{ auth()->id() === $seller->id ? $buyer->name : $seller->name }}
                </p>
                <p class="text-xs text-gray-500">re: {{ $product->title }}</p>
            </div>

            {{-- Messages (auto-refreshed by the script below) --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                @foreach($messages as $message)
                    @php $mine = $message->sender_id === auth()->id(); @endphp
                    <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-3 py-2 rounded-lg text-sm {{ $mine ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                            {{ $message->message }}
                            <div class="text-[10px] mt-1 opacity-70">{{ $message->created_at->format('M j, g:i A') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Composer --}}
            <form id="chat-form" action="{{ route('chat.store', array_filter(['product' => $product->id, 'seller' => $seller->id])) }}{{ auth()->id() === $seller->id ? '?buyer=' . $buyer->id : '' }}"
                  method="POST" class="border-t p-3 flex gap-2">
                @csrf
                <input type="text" name="message" required autocomplete="off" placeholder="Type a message..."
                       class="flex-1 border rounded-md px-3 py-2 text-sm">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm hover:bg-orange-700">
                    Send
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Very simple "real-time" chat: poll the JSON endpoint every 3 seconds
    // and re-render the thread. Good enough for a class project; swap for
    // Laravel Echo + Reverb/Pusher broadcasting for true push updates.
    const pollUrl = "{{ route('chat.messages', array_filter(['product' => $product->id, 'seller' => $seller->id])) }}{{ auth()->id() === $seller->id ? '?buyer=' . $buyer->id : '' }}";
    const container = document.getElementById('chat-messages');

    async function refreshMessages() {
        try {
            const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const messages = await res.json();

            container.innerHTML = messages.map(m => `
                <div class="flex ${m.is_mine ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-xs px-3 py-2 rounded-lg text-sm ${m.is_mine ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-800'}">
                        ${m.message}
                        <div class="text-[10px] mt-1 opacity-70">${m.created_at}</div>
                    </div>
                </div>
            `).join('');
            container.scrollTop = container.scrollHeight;
        } catch (e) {
            console.error('Could not refresh chat', e);
        }
    }

    container.scrollTop = container.scrollHeight;
    setInterval(refreshMessages, 3000);

    // Send message via fetch so the page doesn't fully reload each time.
    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        await fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: data,
        });
        form.reset();
        refreshMessages();
    });
</script>
@endpush
