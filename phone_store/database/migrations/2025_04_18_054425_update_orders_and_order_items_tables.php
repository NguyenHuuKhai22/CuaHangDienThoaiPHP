<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm các trường mới cho orders nếu chưa tồn tại
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->after('id')->unique();
            }
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->after('shipping_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'shipping_district')) {
                $table->string('shipping_district')->after('shipping_city');
            }
            if (!Schema::hasColumn('orders', 'shipping_ward')) {
                $table->string('shipping_ward')->after('shipping_district');
            }
            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable()->after('shipping_ward');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Thêm các trường mới cho order_items nếu chưa tồn tại
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('order_items', 'product_color')) {
                $table->string('product_color')->nullable()->after('product_image');
            }
            if (!Schema::hasColumn('order_items', 'product_ram')) {
                $table->string('product_ram')->nullable()->after('product_color');
            }
            if (!Schema::hasColumn('order_items', 'product_storage')) {
                $table->string('product_storage')->nullable()->after('product_ram');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa các trường mới
            $table->dropIfExists('order_code');
            $table->dropIfExists('shipping_name');
            $table->dropIfExists('shipping_phone');
            $table->dropIfExists('shipping_city');
            $table->dropIfExists('shipping_district');
            $table->dropIfExists('shipping_ward');
            $table->dropIfExists('note');
            $table->dropIfExists('payment_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Xóa các trường mới
            $table->dropIfExists('product_name');
            $table->dropIfExists('product_image');
            $table->dropIfExists('product_color');
            $table->dropIfExists('product_ram');
            $table->dropIfExists('product_storage');
        });
    }
};
