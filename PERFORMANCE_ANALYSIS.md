# PERFORMANCE_ANALYSIS.md — Performance Review

> Analysed from live codebase. Last updated: 2026-06-04.

---

## Automatic Performance Features (Already Implemented)

### Image Optimisation (via ForcePrimaryDomain middleware)
The `enrichImageAltAndTitle()` method in `ForcePrimaryDomain` automatically injects on every `<img>` tag in every HTML response:

| Attribute | Value | Benefit |
|-----------|-------|---------|
| `loading="lazy"` | Images 3+ | Defers offscreen image load |
| `loading="eager"` | First 2 images | Ensures LCP image loads fast |
| `fetchpriority="high"` | First 2 images | Browser prioritises LCP image |
| `decoding="async"` | All images | Non-blocking image decode |
| `width` + `height` | Auto-resolved | Prevents CLS (layout shift) |
| `alt` | Auto-generated | Accessibility + SEO |

This is applied at the HTTP response layer — not per-template. Every page benefits automatically.

### URL & Cache Efficiency
- UTM/tracking param stripping via 301 → reduces cache fragmentation
- Canonical URL on every response → no PageRank dilution
- Legacy .php URL 301 redirects → consolidates link equity

---

## Asset Pipeline

### How CSS is Delivered
```
public/assets/css/bundle.css     ← manually maintained, single file
public/assets/css/module-css/    ← source files (not served directly)
resources/css/app.css            ← Tailwind entry (Vite-managed)
```

**Bundle.css** is a large manually-compiled CSS file. It is served as a single HTTP request. This is good for HTTP/1.1 but in HTTP/2 multiple smaller files can be better.

### How JS is Delivered
```
public/assets/js/script.js       ← monolithic JS file
```
All animations (AOS, WOW.js), chat widget, forms, and robot state machine are in one file. This means the entire JS loads on every page even if most of it is unused on that page.

---

## Database Performance

### SQLite Considerations
- Single-file database — no network round-trip
- Read performance is excellent for low-concurrent read workloads
- **Write bottleneck:** SQLite uses exclusive file locks for writes. Concurrent writes queue. At 10+ concurrent form submissions this can cause timeout errors.
- No connection pooling (not applicable to SQLite)

### N+1 Query Risk Areas
- `BlogPageController` — check if blog list loads posts with eager-loaded relationships
- `PortfolioPageController` — check if portfolio list lazy-loads gallery images
- `AdminChatController` — chat message lists should eager-load conversation

**Recommendation:** Add `->with(['messages', 'conversation'])` on any list query that accesses relationships in a loop.

### Meeting Booking JSON Store
- `storage/app/meeting-bookings.json` — read/written on every availability check and every booking
- File is read entirely into memory on each request
- At <50 bookings/month this is fine. At high volume, move to `leads` table query.

---

## Email Performance

**Critical gap:** Emails are sent **synchronously** during the HTTP request:
```php
Mail::to($adminEmail)->send(new ChatAdminNotificationMail(...));
```

This means:
- If SMTP server is slow or down → HTTP response blocks (can take 30+ seconds)
- User sees a loading spinner waiting for email confirmation
- Contact form, chat message, and meeting booking all have this issue

**Impact:** Medium — SMTP on managed hosting is usually fast (<500ms). But any SMTP issue directly degrades UX.

**Fix (when needed):**
```php
// Change to queued mail
Mail::to($adminEmail)->queue(new ChatAdminNotificationMail(...));
// Requires: queue driver configured + queue worker running
```

---

## Core Web Vitals Assessment

### LCP (Largest Contentful Paint)
- First 2 images get `loading="eager"` and `fetchpriority="high"` automatically ✅
- Hero image should be `preload_image` in `$seoOverride` — check `header.blade.php` for `<link rel="preload">` implementation
- `bundle.css` is render-blocking until loaded — consider inlining critical CSS

### CLS (Cumulative Layout Shift)
- `width` and `height` auto-injected on images via middleware ✅
- Chat widget: `position:fixed` — does not affect document flow ✅
- Font loading could cause FOUT — check if web fonts use `font-display: swap`

### FID / INP (Interaction to Next Paint)
- Robot animation uses `@keyframes` + CSS classes — GPU-accelerated ✅
- AOS/WOW.js scroll animations add JS execution — keep `duration` low ✅ (set to 800ms)
- `script.js` is loaded synchronously — consider `defer` attribute

---

## Caching Strategy

| Layer | Current state | Assessment |
|-------|--------------|-----------|
| Config cache | `php artisan config:cache` | ✅ Run on deploy |
| Route cache | Not explicitly mentioned | ⚠️ Add `php artisan route:cache` to deploy |
| View cache | `php artisan view:cache` | ✅ Run via optimize |
| Application cache | SQLite-based cache driver | ✅ Works |
| HTTP cache | No Cache-Control headers observed | ⚠️ Static assets should have long max-age |
| OPcache | Depends on cPanel PHP config | ⚠️ Verify OPcache enabled on server |

**Improved deploy command:**
```bash
git pull origin main
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```
Or simply: `php artisan optimize` (does all of the above)

---

## Server Environment (cPanel Shared Hosting)

| Factor | Assessment |
|--------|-----------|
| OPcache | Likely enabled — verify in cPanel PHP settings |
| PHP workers | Shared pool — limited concurrency |
| SQLite file I/O | Depends on disk speed — usually SSD on modern hosts |
| SMTP | Likely on same server or local relay — fast |
| No queue worker | Emails synchronous — acceptable for low volume |
| No Redis | Cache uses SQLite/file driver — acceptable |

---

## Performance Strengths

✅ Automatic image lazy loading on all pages via middleware  
✅ `fetchpriority="high"` on first 2 images (LCP optimisation)  
✅ `decoding="async"` on all images  
✅ Auto width/height injection (prevents CLS)  
✅ UTM param stripping (reduces cache fragmentation)  
✅ Single CSS bundle (one HTTP request)  
✅ AOS configured with `once:true` and `mirror:false` (no re-animation on scroll-up)  
✅ Chat widget uses CSS animations (GPU-accelerated)  

## Performance Improvement Priorities

| Priority | Action | Impact |
|----------|--------|--------|
| 1 | Run `php artisan optimize` on deploy (not just `optimize:clear`) | Route + view cache |
| 2 | Add `defer` to `script.js` `<script>` tag in footer | Faster page render |
| 3 | Verify OPcache enabled in cPanel → PHP Settings | 50-80% PHP speedup |
| 4 | Add `Cache-Control: max-age=31536000` to static assets in `.htaccess` | Browser cache |
| 5 | Check N+1 queries in blog/portfolio list pages | DB query reduction |
| 6 | Consider queued mail for contact/chat notifications | Remove HTTP blocking |
| 7 | Inline critical CSS for above-the-fold content | Faster FCP |

---

## Quick Reference: Deploy Command (Optimised)

```bash
cd /home/arsdojcg/arsdeveloper.co.uk
git pull origin main
php artisan optimize
```

`php artisan optimize` = config:cache + route:cache + view:cache + event:cache in one command.
Use `php artisan optimize:clear` only when you need to clear (after config changes).
