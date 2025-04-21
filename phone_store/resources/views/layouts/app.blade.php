<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KAIRA - Cửa hàng Điện thoại')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Mulish:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

<link rel="stylesheet" href="{{asset('css/IntersectionObserver.css')}}">
<link rel="stylesheet" href="{{ asset('css/home.css')}}">
<link rel="stylesheet" href="{{ asset('css/styleHeart.css') }}">
<link rel="stylesheet" href="{{ asset('css/notification.css') }}">


    @stack('styles')
</head>
<body>
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Chat Widget -->
    <div class="chat-widget">
        <div class="chat-header">
            <span>Chat với AI</span>
            <div class="chat-header-controls">
                <button class="close-chat" title="Đóng"><i class="bi bi-x"></i></button>
            </div>
        </div>
        <div id="chat-messages" class="chat-messages">
            <!-- Messages will appear here -->
        </div>
        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <input type="text" id="message-input" class="chat-input" placeholder="Nhập tin nhắn của bạn...">
                <button onclick="sendMessage()" class="chat-send-btn"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <!-- toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Chat Widget Script -->
    <script>
        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();
            
            if (message) {
                // Add user message to chat
                addMessage('user', message);
                messageInput.value = '';
                
                // Send to server
                fetch('{{ route("chat") }}', {
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
                    addMessage('bot', 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.');
                });
            }
        }

        function addMessage(sender, message) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;
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

        // Chat widget functionality
        const chatWidget = document.querySelector('.chat-widget');
        const closeBtn = document.querySelector('.close-chat');

        // Toggle expanded state on widget click
        chatWidget.addEventListener('click', function(e) {
            if (!chatWidget.classList.contains('expanded') || 
                (!e.target.closest('.chat-input-container') && !e.target.closest('.chat-messages'))) {
                chatWidget.classList.toggle('expanded');
            }
        });

        // Close chat button functionality
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            chatWidget.classList.remove('expanded');
        });
    </script>

    @stack('scripts')
</body>
</html> 