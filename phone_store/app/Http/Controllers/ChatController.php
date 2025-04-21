<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $rasaUrl;

    public function __construct()
    {
        $this->rasaUrl = 'http://localhost:5005/webhooks/rest/webhook';
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');
        
        // Gửi tin nhắn đến Rasa server
        $response = Http::post($this->rasaUrl, [
            'sender' => 'user',
            'message' => $message
        ]);

        // Xử lý phản hồi từ Rasa
        if ($response->successful()) {
            $botResponse = $response->json();
            if (!empty($botResponse)) {
                return response()->json([
                    'response' => $botResponse[0]['text']
                ]);
            }
        }

        return response()->json([
            'response' => 'Xin lỗi, tôi không thể xử lý yêu cầu của bạn ngay lúc này.'
        ]);
    }
} 