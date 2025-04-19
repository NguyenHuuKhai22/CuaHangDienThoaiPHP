<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thanh toán thành công</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;">
        <!-- Header -->
        <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f4f4f4;">
            <h1 style="color: #333; margin: 0;">Cửa Hàng Điện Thoại</h1>
        </div>

        <!-- Content -->
        <div style="padding: 20px;">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Xin chào {{ $user->name }}!</h2>
            
            <p style="color: #34495e; line-height: 1.6; margin-bottom: 20px;">
                Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi. Đơn hàng của bạn đã được thanh toán thành công.
            </p>
            
            <!-- Thông tin đơn hàng -->
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 15px;">Thông tin đơn hàng</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Mã đơn hàng:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">{{ $order->order_code }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Ngày đặt hàng:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Tổng tiền:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">{{ number_format($order->total_amount) }} VNĐ</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Phương thức thanh toán:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">
                            @switch($order->payment_method)
                                @case('cod')
                                    Thanh toán khi nhận hàng (COD)
                                    @break
                                @case('momo')
                                    Ví điện tử MoMo
                                    @break
                                @case('vnpay')
                                    VNPay
                                    @break
                                @default
                                    Không xác định
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #495057;"><strong>Trạng thái:</strong></td>
                        <td style="padding: 10px; color: #212529;">
                            @switch($order->status)
                                @case('pending')
                                    Đang xử lý
                                    @break
                                @case('processing')
                                    Đang chuẩn bị hàng
                                    @break
                                @case('shipped')
                                    Đang giao hàng
                                    @break
                                @case('delivered')
                                    Đã giao hàng
                                    @break
                                @case('cancelled')
                                    Đã hủy
                                    @break
                                @default
                                    Không xác định
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Thông tin giao hàng -->
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 15px;">Thông tin giao hàng</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Người nhận:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">{{ $order->shipping_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #495057;"><strong>Số điện thoại:</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">{{ $order->shipping_phone }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #495057;"><strong>Địa chỉ:</strong></td>
                        <td style="padding: 10px; color: #212529;">
                            {{ $order->shipping_address }}, 
                            {{ $order->shipping_ward }}, 
                            {{ $order->shipping_district }}, 
                            {{ $order->shipping_city }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Danh sách sản phẩm -->
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 15px;">Danh sách sản phẩm</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; border-bottom: 2px solid #dee2e6; text-align: left; color: #495057;">Sản phẩm</th>
                            <th style="padding: 10px; border-bottom: 2px solid #dee2e6; text-align: center; color: #495057;">Số lượng</th>
                            <th style="padding: 10px; border-bottom: 2px solid #dee2e6; text-align: right; color: #495057;">Đơn giá</th>
                            <th style="padding: 10px; border-bottom: 2px solid #dee2e6; text-align: right; color: #495057;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529;">
                                {{ $item->product_name }}
                                <div style="color: #6c757d; font-size: 12px;">
                                    @if($item->product_color)
                                        Màu: {{ $item->product_color }}
                                    @endif
                                    @if($item->product_ram)
                                        | RAM: {{ $item->product_ram }}
                                    @endif
                                    @if($item->product_storage)
                                        | Bộ nhớ: {{ $item->product_storage }}
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; text-align: center; color: #212529;">{{ $item->quantity }}</td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; text-align: right; color: #212529;">{{ number_format($item->price) }} VNĐ</td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; text-align: right; color: #212529;">{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: right; color: #495057;"><strong>Tổng cộng:</strong></td>
                            <td style="padding: 10px; text-align: right; color: #212529;"><strong>{{ number_format($order->total_amount) }} VNĐ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($order->note)
            <!-- Ghi chú -->
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #2c3e50; margin-top: 0; margin-bottom: 15px;">Ghi chú</h3>
                <p style="color: #212529; margin: 0;">{{ $order->note }}</p>
            </div>
            @endif

            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('orders.show', $order->id) }}" 
                   style="display: inline-block; padding: 10px 20px; background-color: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    Xem chi tiết đơn hàng
                </a>
            </div>

            <p style="color: #34495e; line-height: 1.6; margin-bottom: 20px;">
                Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; border-top: 2px solid #f4f4f4; color: #7f8c8d;">
            <p style="margin: 0;">Trân trọng,<br><strong>{{ config('app.name') }}</strong></p>
            <p style="margin: 10px 0 0 0; font-size: 12px;">
                Đây là email tự động, vui lòng không trả lời email này.
            </p>
        </div>
    </div>
</body>
</html> 