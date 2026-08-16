# WebERP / WebPOS — Brand Assets

هذا المجلد يحتوي على الهوية البصرية الكاملة لتطبيق **WebERP / WebPOS**.

## 📁 محتويات المجلد

| الملف | الاستخدام |
|---|---|
| `logo.svg` | الشعار الأساسي (أفقي) — يُفضل استخدامه في الواجهات (vector، يظهر بأي دقة) |
| `logo-primary.png` | نسخة PNG عالية الجودة من الشعار الأساسي (للخلفيات الفاتحة) |
| `logo-white.svg` / `logo-white.png` | نسخة بيضاء من الشعار للخلفيات الداكنة (السيدبار/الفوتر) |
| `logo-pos.svg` / `logo-pos.png` | شعار خاص بوحدة **نقاط البيع POS** |
| `icon.svg` / `icon.png` | أيقونة التطبيق (App Icon) — مفضلة للـ favicon والـ PWA |
| `favicon.png` | أيقونة صغيرة بحجم 32×32 للمتصفح |
| `og-image.png` | صورة العرض عند مشاركة رابط النظام (1200×630) لـ Open Graph / Social Media |
| `login-hero.png` | صورة Hero لصفحات تسجيل الدخول |
| `brand.css` | ملف CSS يحتوي على ألوان وخطوط الهوية البصرية |
| `site.webmanifest` | ملف PWA manifest يحتوي على إعدادات التطبيق |

## 🎨 هوية الألوان

```css
--brand-primary:      #4F46E5;  /* indigo */
--brand-primary-dark: #4338CA;
--brand-accent:       #7C3AED;  /* violet */
--brand-accent-2:     #14B8A6;  /* teal */
--brand-warn:         #F59E0B;  /* amber */
--brand-danger:       #EF4444;  /* red */
--brand-ink:          #0F172A;  /* slate-900 */
--brand-slate:        #1E293B;  /* slate-800 */
--brand-muted:        #64748B;  /* slate-500 */
--brand-bg:           #F8FAFC;  /* slate-50 */
```

## 🔤 الخطوط

- **Inter** (الخط الأساسي) — من Google Fonts
- **العربية**: Cairo أو Inter Arabic (يمكن استخدامهما)

## 🧩 طريقة الاستخدام في Blade

```blade
<img src="{{ static_asset('img/brand/logo.svg') }}" alt="WebERP" class="brand-logo">

{{-- For dark sidebar: --}}
<img src="{{ static_asset('img/brand/logo-white.svg') }}" alt="WebERP" class="brand-logo">

{{-- Favicon: --}}
<link rel="icon" type="image/svg+xml" href="{{ static_asset('img/brand/icon.svg') }}">
```

## 📝 ملاحظات

- النسخ SVG مفضلة لأنها crisp على كل الدقات (Retina ، 2K ، إلخ).
- لا تستخدم الشعار على خلفية مزعجة أو بنفس درجة اللون (يُفضل خلفية بيضاء أو داكنة نقية).
- أترك مسافة حول الشعار (padding) لا تقل عن 8 بكسل.
