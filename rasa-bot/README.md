# Hướng dẫn sử dụng Chatbot Cửa hàng Điện thoại

## 1. Giới thiệu
Chatbot này được xây dựng bằng Rasa để hỗ trợ khách hàng tìm hiểu thông tin về sản phẩm, giá cả, đặt hàng và các chính sách của cửa hàng.

## 2. Các tính năng chính
- Tìm hiểu thông tin sản phẩm
- Hỏi giá sản phẩm
- Hướng dẫn đặt hàng
- Thông tin giao hàng
- Chính sách đổi trả
- Hỗ trợ khách hàng 24/7

## 3. Cách chạy chatbot

### Yêu cầu hệ thống
- Python 3.8 trở lên
- Rasa 3.x
- pip (trình quản lý gói Python)

### Cài đặt
1. Cài đặt các thư viện cần thiết:
```bash
pip install rasa
```

2. Clone repository về máy:
```bash
git clone [đường dẫn repository]
cd [tên thư mục]
```

### Chạy chatbot
1. Huấn luyện model:
```bash
rasa train
```

2. Chạy server Rasa:
```bash
rasa run
```

3. Mở giao diện chat:
```bash
rasa shell
```

## 4. Cấu trúc dự án
```
rasa-bot/
├── data/
│   ├── nlu.yml          # Dữ liệu huấn luyện NLU
│   ├── responses.yml    # Các câu trả lời
│   └── rules.yml        # Quy tắc xử lý
├── domain.yml           # Cấu hình domain
├── config.yml           # Cấu hình model
└── models/              # Thư mục chứa model đã train
```

## 5. Các câu hỏi mẫu
### Chào hỏi
- Xin chào
- Hi
- Chào buổi sáng

### Hỏi về sản phẩm
- Bạn có những sản phẩm gì?
- Có iPhone không?
- Có Samsung không?

### Hỏi giá
- Giá iPhone 16 Pro Max bao nhiêu?
- iPhone 16 Pro Max giá bao nhiêu?

### Đặt hàng
- Làm sao để đặt hàng?
- Cách đặt hàng như thế nào?

### Giao hàng
- Giao hàng bao lâu?
- Thời gian giao hàng là bao lâu?

### Đổi trả
- Chính sách đổi trả như thế nào?
- Có được đổi trả không?

## 6. Lưu ý khi sử dụng
- Chatbot có thể xử lý các câu hỏi bằng tiếng Việt
- Nếu chatbot không hiểu câu hỏi, nó sẽ yêu cầu bạn hỏi lại
- Để có kết quả tốt nhất, hãy sử dụng các câu hỏi đơn giản và rõ ràng

## 7. Xử lý lỗi
Nếu gặp lỗi khi chạy chatbot:
1. Kiểm tra phiên bản Python và Rasa
2. Đảm bảo đã cài đặt đầy đủ các thư viện
3. Kiểm tra cấu hình trong các file .yml
4. Thử train lại model

## 8. Liên hệ hỗ trợ
Nếu cần hỗ trợ thêm, vui lòng liên hệ:
- Email: support@example.com
- Hotline: 1900xxxx 