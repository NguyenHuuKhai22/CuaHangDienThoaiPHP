# Phone Store - Hệ thống quản lý cửa hàng điện thoại

## Giới thiệu
Phone Store là một ứng dụng web được xây dựng bằng Laravel, kết hợp với Javascript, Bootstrap và Tailwind CSS để tạo giao diện người dùng hiện đại. Ứng dụng này giúp quản lý cửa hàng điện thoại với các tính năng như quản lý sản phẩm, đơn hàng, và giỏ hàng.

## Công nghệ sử dụng
- **Backend**: Laravel 12.x
- **Frontend**: Bootstrap, CSS, Tailwind CSS, Javascript
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Shopping Cart**: hardevine/shoppingcart
- **AI Integration**: Chatbot RASA

## Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- Node.js và npm
- MySQL
- Git

## Cài đặt

1. Clone repository:
```bash
git clone [https://github.com/NguyenHuuKhai22/CuaHangDienThoaiPHP.git]
cd phone_store
```

2. Cài đặt dependencies PHP:
```bash
composer install
```

3. Cài đặt dependencies JavaScript:
```bash
npm install
```

4. Tạo file .env:
```bash
cp .env.example .env
```

5. Tạo key cho ứng dụng:
```bash
php artisan key:generate
```

6. Cấu hình database trong file .env và chạy migrations:
```bash
php artisan migrate
```

7. Chạy seeder (nếu cần):
```bash
php artisan db:seed
```

8. Khởi động ứng dụng:
```bash
npm run dev
php artisan serve
```

## Tính năng chính
- Quản lý sản phẩm (thêm, sửa, xóa mèm)
- Quản lý đơn hàng
- Quản lý danh mục
- Quản lý khuyến mãi
- Quản lý sản phẩm
- Quản lý người dùng
- Quản lý đơn hàng
- Trang Dashboard
- Giỏ hàng
- Danh sách yêu thích
- Thông báo khi có khuyến mãi cho người dùng
- Người dùng(đổi thông tin cá nhân, đổi mật khẩu hoặc reset mặt khẩu)
- Tìm kiếm và lọc nâng cao
- Thanh toán( tích hợp MOMO,VNPAY,COD) gửi gmail khi thanh toán thành công
- Xác thực người dùng
- Tích hợp ChatBot RASA

## Cấu trúc thư mục
- `app/` - Chứa các model, controller và logic chính
- `resources/` - Chứa các file view và assets
- `routes/` - Định nghĩa các route
- `database/` - Chứa migrations và seeders
- `public/` - Chứa các file public
- `config/` - Chứa các file cấu hình

## Phát triển
Để chạy môi trường phát triển:
```bash
composer run dev
```

## Testing
```bash
php artisan test
```

## License
MIT License