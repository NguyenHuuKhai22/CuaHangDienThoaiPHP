<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tạm thời lưu dữ liệu từ cột cũ
            $table->decimal('total_amount_new', 20, 2)->nullable();
        });

        // Copy dữ liệu từ cột cũ sang cột mới
        DB::statement('UPDATE orders SET total_amount_new = total_amount');

        Schema::table('orders', function (Blueprint $table) {
            // Xóa cột cũ
            $table->dropColumn('total_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Đổi tên cột mới thành tên cột cũ
            $table->renameColumn('total_amount_new', 'total_amount');
        });

        // Đảm bảo cột không null
        DB::statement('ALTER TABLE orders MODIFY total_amount DECIMAL(20,2) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tạm thời lưu dữ liệu từ cột cũ
            $table->decimal('total_amount_old', 10, 2)->nullable();
        });

        // Copy dữ liệu từ cột cũ sang cột mới
        DB::statement('UPDATE orders SET total_amount_old = total_amount');

        Schema::table('orders', function (Blueprint $table) {
            // Xóa cột cũ
            $table->dropColumn('total_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Đổi tên cột mới thành tên cột cũ
            $table->renameColumn('total_amount_old', 'total_amount');
        });

        // Đảm bảo cột không null
        DB::statement('ALTER TABLE orders MODIFY total_amount DECIMAL(10,2) NOT NULL');
    }
};
