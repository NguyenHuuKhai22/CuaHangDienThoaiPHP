<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function handleMessage(Request $request)
    {
        $message = $request->input('message');
        
        // Xử lý tin nhắn và trả về phản hồi
        $response = $this->processMessage($message);
        
        return response()->json([
            'response' => $response
        ]);
    }

    private function processMessage($message)
    {
        // Chuyển đổi tin nhắn thành chữ thường để dễ so sánh
        $message = strtolower($message);
        
        // Các câu trả lời mặc định
        $responses = [
            'xin chào' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
            'tạm biệt' => 'Tạm biệt! Hẹn gặp lại bạn!',
            'cảm ơn' => 'Không có gì!',
            'giá' => 'Bạn có thể xem giá sản phẩm tại trang sản phẩm của chúng tôi.',
            'hỗ trợ' => 'Đội ngũ hỗ trợ của chúng tôi sẽ liên hệ với bạn sớm nhất có thể.',
        ];

        // Kiểm tra xem có câu trả lời phù hợp không
        foreach ($responses as $key => $value) {
            if (strpos($message, $key) !== false) {
                return $value;
            }
        }

        // Nếu không tìm thấy câu trả lời phù hợp
        return 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi lại không?';
    }
} 