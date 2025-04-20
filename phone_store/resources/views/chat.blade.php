@extends('layouts.app')

@section('title', 'Chat với AI')

@section('content')
<div class="container mx-auto p-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div id="chat-messages" class="h-96 overflow-y-auto mb-4">
                <!-- Messages will appear here -->
            </div>
            <div class="flex">
                <input type="text" id="message-input" class="flex-1 border rounded-l-lg p-2" placeholder="Nhập tin nhắn của bạn...">
                <button onclick="sendMessage()" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg">Gửi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (message) {
            // Add user message to chat
            addMessage('user', message);
            messageInput.value = '';
            
            // Send to server
            fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                addMessage('bot', data.response);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function addMessage(sender, message) {
        const chatMessages = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-2 p-2 rounded-lg ${sender === 'user' ? 'bg-blue-100 ml-auto' : 'bg-gray-100'}`;
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Allow sending message with Enter key
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
</script>
@endpush 