<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: إنشاء جدول الإعلانات (items).
 *
 * الجدول الرئيسي في التطبيق — يخزن بيانات الإعلانات (مفقودات/موجودات).
 *
 * الحقول:
 * - title: عنوان الإعلان
 * - description: الوصف التفصيلي
 * - image_path: مسار الصورة (nullable)
 * - type: enum (lost = مفقود, found = موجود)
 * - status: enum (active = نشط, returned = مسترجع) — افتراضي: active
 * - contact_phone: رقم الهاتف للتواصل
 *
 * العلاقات (Foreign Keys) — cascadeOnDelete:
 * - user_id → users.id       (المستخدم الناشر)
 * - category_id → categories.id (فئة الغرض)
 * - neighborhood_id → neighborhoods.id (الحي/المنطقة)
 *
 * ملاحظة: cascadeOnDelete يعني حذف الإعلان تلقائياً عند حذف
 * المستخدم أو الفئة أو الحي المرتبط.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->enum('type', ['lost', 'found']);
            $table->enum('status', ['active', 'returned'])->default('active');
            $table->string('contact_phone');
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('neighborhood_id')->constrained()->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
