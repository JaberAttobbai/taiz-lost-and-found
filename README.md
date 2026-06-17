<div align="center">

# 🔍 منصة مفقودات وموجودات تعز

### Taiz Lost & Found Platform

منصة إلكترونية لنشر إعلانات المفقودات والموجودات في محافظة تعز، اليمن.
ابحث عن أغراضك المفقودة أو أعلن عمّا وجدته — بسهولة وأمان.

[![Laravel](https://img.shields.io/badge/Laravel-v13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Render](https://img.shields.io/badge/Deployed_on-Render-46E3B7?style=for-the-badge&logo=render&logoColor=white)](https://taiz-lost-and-found.onrender.com)

[🌐 زيارة الموقع](https://taiz-lost-and-found.onrender.com) · [🐛 الإبلاغ عن مشكلة](https://github.com/JaberAttobbai/taiz-lost-and-found/issues)

</div>

---

## 📋 نبذة عن المشروع

**منصة مفقودات وموجودات تعز** هي تطبيق ويب مبني بإطار Laravel، يهدف إلى مساعدة سكان محافظة تعز في البحث عن أغراضهم المفقودة أو الإعلان عمّا وجدوه. تتيح المنصة للمستخدمين إنشاء إعلانات مصنّفة حسب النوع (مفقود/موجود)، والفئة، والمنطقة، مع إمكانية البحث والتصفية المتقدمة.

## ✨ المميزات

| الميزة | الوصف |
|--------|-------|
| 📝 **إنشاء إعلانات** | نشر إعلانات مفقودات أو موجودات مع صور ووصف تفصيلي |
| 🔎 **بحث وفلترة متقدم** | بحث بالكلمة المفتاحية + فلترة حسب النوع والفئة والمنطقة |
| 📍 **تصنيف جغرافي** | أكثر من 90 حي ومنطقة في محافظة تعز |
| 🏷️ **6 فئات رئيسية** | إلكترونيات، وثائق، مفاتيح، حقائب، مجوهرات، وأخرى |
| 🔐 **نظام مستخدمين** | تسجيل دخول، تسجيل حساب، إدارة الملف الشخصي |
| 📊 **لوحة تحكم** | عرض وإدارة إعلانات المستخدم الخاصة |
| 🖼️ **رفع صور** | دعم رفع صور الأغراض المفقودة/الموجودة |
| ✅ **تتبع الحالة** | تحديث حالة الإعلان (نشط / مسترجع) |
| 📱 **تصميم متجاوب** | واجهة جميلة تعمل على جميع الأجهزة |
| 🌐 **SEO متكامل** | Canonical URLs, Open Graph, Schema.org, Sitemap ديناميكي |

## 🛠️ التقنيات المستخدمة

<table>
<tr>
<td align="center"><strong>Backend</strong></td>
<td>

- **Laravel 13** — إطار PHP الأقوى
- **PHP 8.3+** — آخر إصدار مستقر
- **SQLite** — قاعدة بيانات خفيفة
- **Laravel Breeze** — نظام المصادقة

</td>
</tr>
<tr>
<td align="center"><strong>Frontend</strong></td>
<td>

- **Blade Templates** — محرك قوالب Laravel
- **Tailwind CSS v4** — تصميم حديث ومتجاوب
- **Alpine.js** — تفاعلات JavaScript خفيفة
- **Vite** — أداة بناء سريعة

</td>
</tr>
<tr>
<td align="center"><strong>البنية التحتية</strong></td>
<td>

- **Render** — استضافة وتشغيل
- **GitHub** — إدارة الكود المصدري
- **GitHub Actions** — CI/CD (اختياري)

</td>
</tr>
</table>

## 🏗️ هيكل المشروع

```
taiz-lost-and-found/
├── app/
│   ├── Http/Controllers/
│   │   ├── ItemController.php        # التحكم بالإعلانات (CRUD + بحث)
│   │   ├── ProfileController.php     # إدارة الملف الشخصي
│   │   └── SitemapController.php     # توليد Sitemap ديناميكي
│   ├── Models/
│   │   ├── Item.php                  # نموذج الإعلان
│   │   ├── User.php                  # نموذج المستخدم
│   │   ├── Category.php              # نموذج الفئة
│   │   └── Neighborhood.php          # نموذج المنطقة/الحي
│   └── Http/Requests/                # Form Requests للتحقق
├── resources/views/
│   ├── items/                        # صفحات الإعلانات
│   │   ├── index.blade.php           # الصفحة الرئيسية + البحث
│   │   ├── show.blade.php            # تفاصيل الإعلان
│   │   ├── create.blade.php          # إنشاء إعلان
│   │   └── edit.blade.php            # تعديل إعلان
│   └── layouts/
│       ├── app.blade.php             # القالب الأساسي + SEO
│       └── guest.blade.php           # قالب صفحات المصادقة
├── database/
│   ├── migrations/                   # هيكل قاعدة البيانات
│   └── seeders/                      # بيانات أولية (فئات + أحياء)
└── public/
    ├── robots.txt                    # تعليمات محركات البحث
    └── google*.html                  # ملف تحقق Google
```

## 📊 مخطط قاعدة البيانات (ERD)

```
┌──────────┐       ┌──────────────┐       ┌──────────────┐
│  users   │       │    items     │       │  categories  │
├──────────┤       ├──────────────┤       ├──────────────┤
│ id (PK)  │──────<│ user_id (FK) │       │ id (PK)      │
│ name     │       │ category_id  │>──────│ name         │
│ email    │       │ neighborhood │       └──────────────┘
│ password │       │ title        │
└──────────┘       │ description  │       ┌───────────────┐
                   │ type         │       │ neighborhoods │
                   │ status       │       ├───────────────┤
                   │ image_path   │       │ id (PK)       │
                   │ contact_info │>──────│ name          │
                   └──────────────┘       └───────────────┘
```

## 🚀 التشغيل محلياً

### المتطلبات
- PHP >= 8.3
- Composer
- Node.js >= 18
- SQLite

### خطوات التثبيت

```bash
# 1. استنساخ المستودع
git clone https://github.com/JaberAttobbai/taiz-lost-and-found.git
cd taiz-lost-and-found

# 2. تثبيت الاعتماديات
composer install
npm install

# 3. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 4. إعداد قاعدة البيانات
touch database/database.sqlite
php artisan migrate --seed

# 5. ربط التخزين
php artisan storage:link

# 6. تشغيل المشروع
npm run dev          # في terminal أول
php artisan serve    # في terminal ثاني
```

ثم افتح المتصفح على: **http://localhost:8000**

## 🌐 SEO

المشروع يتضمن نظام SEO متكامل:

- ✅ **Canonical URLs** — لمنع المحتوى المكرر
- ✅ **Open Graph** — مشاركة جميلة على Facebook, WhatsApp, Telegram
- ✅ **Twitter Cards** — معاينة غنية على Twitter
- ✅ **Schema.org JSON-LD** — WebSite, Organization, Article, BreadcrumbList
- ✅ **Sitemap ديناميكي** — يتضمن فقط الإعلانات النشطة مع دعم الصور
- ✅ **robots.txt** — توجيه ذكي لعناكب البحث
- ✅ **meta robots** — `noindex` للصفحات الخاصة (dashboard, auth, إلخ)
- ✅ **Pagination SEO** — `rel=prev/next` + `noindex` لصفحة 2+

## 📂 المسارات الرئيسية (Routes)

| المسار | الطريقة | الوصف | الحماية |
|--------|---------|-------|---------|
| `/` | GET | الصفحة الرئيسية + بحث وفلترة | عام |
| `/items/{id}` | GET | تفاصيل إعلان | عام |
| `/items/create` | GET | نموذج إنشاء إعلان | 🔐 auth |
| `/items` | POST | حفظ إعلان جديد | 🔐 auth |
| `/items/{id}/edit` | GET | نموذج تعديل إعلان | 🔐 auth + مالك |
| `/items/{id}` | PUT | تحديث إعلان | 🔐 auth + مالك |
| `/items/{id}` | DELETE | حذف إعلان | 🔐 auth + مالك |
| `/dashboard` | GET | لوحة تحكم المستخدم | 🔐 auth |
| `/profile` | GET | إعدادات الملف الشخصي | 🔐 auth |
| `/sitemap.xml` | GET | خريطة الموقع | عام |

## 👨‍💻 المطوّر

<div align="center">

**تم بناء وتطوير هذا المشروع بواسطة**

### م. جابر فرحان

[![GitHub](https://img.shields.io/badge/GitHub-JaberAttobbai-181717?style=for-the-badge&logo=github)](https://github.com/JaberAttobbai)

</div>

## 📄 الرخصة

هذا المشروع مرخص تحت [رخصة MIT](https://opensource.org/licenses/MIT).
