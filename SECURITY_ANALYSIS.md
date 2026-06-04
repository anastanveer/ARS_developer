# SECURITY_ANALYSIS.md — Security Review

> Analysed from live codebase. Last updated: 2026-06-04.
> Severity: CRITICAL | HIGH | MEDIUM | LOW | INFO

---

## Security Headers (Set by ForcePrimaryDomain Middleware)

| Header | Value | Assessment |
|--------|-------|-----------|
| `X-Content-Type-Options` | `nosniff` | ✅ Correct |
| `X-Frame-Options` | `SAMEORIGIN` | ✅ Correct |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | ✅ Correct |
| `Permissions-Policy` | Blocks camera, mic, payment, geolocation, etc. | ✅ Correct |
| `Cross-Origin-Opener-Policy` | `same-origin-allow-popups` | ✅ Correct |
| `Cross-Origin-Resource-Policy` | `same-site` | ✅ Correct |
| `Content-Security-Policy` | `upgrade-insecure-requests` only | ⚠️ Minimal — no full CSP |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains; preload` | ✅ Correct (HTTPS only) |

**Assessment:** Security headers are solid. Full CSP (blocking inline scripts/unknown domains) is not implemented — this is a deliberate trade-off given third-party scripts (reCAPTCHA, Font Awesome CDN, analytics). Adding a strict CSP would require nonce-based inline script management.

---

## CSRF Protection

- Laravel CSRF middleware active on all web routes
- **Exceptions (intentionally exempt):**
  - `stripe/webhook` — Stripe signs its own payloads; CSRF would block it
  - `whatsapp/webhook` — WhatsApp sends webhooks without CSRF tokens
- All public forms include `@csrf` — verified by Laravel middleware
- **Assessment:** ✅ Correct configuration. Webhook exemptions are standard practice.

---

## Input Validation

### Contact Form (`ContactFormController`)
- Full Laravel Validator on all fields
- Field-specific rules: `max:120`, `max:180`, `max:5000`, `email`, `date`, `in:` for meeting slots
- reCAPTCHA verification before any DB write
- BlockedContact check (IP + email) before processing
- **Assessment:** ✅ Well validated

### Chat Widget (`ChatWidgetController`)
- Validator on all fields: name max:120, email max:180, message max:2000
- Image upload: `mimes:jpg,jpeg,png,webp,gif`, `max:4096` (4MB)
- Custom validator closure: requires message OR image (not empty both)
- `user_agent` truncated to 1000 chars before storage
- **Assessment:** ✅ Good validation

### Image Upload (Chat)
- Images stored in `storage/app/public/chat-widget/`
- Accessible at `/storage/chat-widget/filename`
- MIME type validated by Laravel (`image` rule + `mimes:`)
- Max 4MB enforced
- **MEDIUM RISK:** Uploaded filenames use Laravel's `store()` which generates random names — good. But uploaded images are publicly accessible via `/storage/` URL without authentication. Anyone with the URL can view uploaded images. This is acceptable for a chat widget but note the exposure.

---

## Admin Authentication

### Strengths
- Custom session-based auth separate from Laravel's default User auth
- `admin_authenticated` session key checked on every request
- `AdminUser::find()` called on every admin request — stale/deleted/inactive accounts ejected immediately
- `is_active` flag allows disabling accounts without deletion
- Role hierarchy enforced per-route via `AdminRole` middleware
- Session is cleared on inactive account detection

### Risks
| Risk | Severity | Notes |
|------|----------|-------|
| No password reset flow visible | MEDIUM | Forgotten password requires direct DB access or manual SQL |
| Session fixation | LOW | Laravel regenerates session on login — mitigated by framework |
| No brute force protection on `/admin/login` | MEDIUM | No rate limiter on login route. Recommend adding `throttle:5,1` to admin login route |
| Passwords stored in `admin_users` table | INFO | Using Laravel's `Hash::make()` (bcrypt) — verify in AdminAuthController |
| No 2FA | LOW | Single-factor auth for admin panel |

---

## Public Token Security

| Feature | Token type | Risk |
|---------|-----------|------|
| Client portal | UUID v4 | ✅ 122 bits entropy — safe |
| Invoice access | UUID | ✅ 122 bits entropy — safe |
| Meeting manage/cancel | UUID | ✅ 122 bits entropy — safe |
| Client review submission | UUID | ✅ 122 bits entropy — safe |
| Chat conversation | UUID (Str::uuid()) | ✅ 122 bits entropy — safe |

**Note:** These tokens are in URLs, meaning they appear in server logs, browser history, and Referer headers. For meeting cancel links this is acceptable. For invoices, advise clients not to forward URLs.

---

## SQL Injection

- All DB queries use Eloquent ORM (`Model::query()->where()`, `->create()`, etc.)
- No raw SQL `DB::statement()` or string interpolation in queries observed
- **Assessment:** ✅ No SQL injection risk from the code reviewed

---

## XSS (Cross-Site Scripting)

- All Blade output uses `{{ }}` (auto-escaped) not `{!! !!}` (unescaped)
- Chat message bodies are stored and displayed via Blade — escaped on output
- **Assessment:** ✅ Blade auto-escaping in place. Verify any `{!! !!}` usage in admin views.

---

## WhatsApp Webhook Security

- Endpoint: `POST /whatsapp/webhook` — CSRF exempt
- Incoming webhook logs stored in `WhatsAppWebhookLog` model
- **Verify:** WhatsApp recommends verifying the `X-Hub-Signature-256` header to ensure requests genuinely come from Meta's servers. Check `WhatsAppWebhookController` to confirm signature verification is implemented.

---

## File System Security

| Path | Exposure | Risk |
|------|---------|------|
| `database/database.sqlite` | Served from `/home/arsdojcg/arsdeveloper.co.uk/database/` — above public root | ✅ Not publicly accessible |
| `storage/app/public/` | Accessible via `/storage/` symlink | ⚠️ All uploaded files public |
| `.env` | Above public root | ✅ Not publicly accessible |
| `storage/logs/` | Above public root | ✅ Not publicly accessible |

---

## Rate Limiting

- reCAPTCHA on all public forms — de facto rate limiting via Google
- BlockedContact IP blocking — post-abuse blocking
- **MEDIUM RISK:** No Laravel `throttle:` middleware observed on:
  - `/contact/submit` — brute-force email spam possible
  - `/chat/message` — message flooding possible
  - `/admin/login` — brute-force login possible

**Recommendation:** Add to `routes/web.php`:
```php
// Contact form
Route::post('/contact/submit', ...)->middleware('throttle:10,1');

// Chat message
Route::post('/chat/message', ...)->middleware('throttle:30,1');

// Admin login
Route::post('/admin/login', ...)->middleware('throttle:5,1');
```

---

## Data Privacy (UK GDPR)

| Data collected | Storage | Exposure |
|---------------|---------|---------|
| Lead name, email, phone | SQLite `leads` | Admin only |
| Chat visitor IP, user_agent | SQLite `chat_conversations` | Admin only |
| Chat name, email | SQLite | Admin only |
| Meeting name, email, phone | SQLite `leads` | Admin only |
| Uploaded chat images | `storage/app/public/` | Public URL |

**Assessment:** Personal data stored in SQLite above public root — correct. Chat images are the only publicly-accessible personal data (if images contain personal info). Cookie consent and privacy policy pages exist. Right to erasure and data retention should be verified in admin tools.

---

## Security Strengths Summary

✅ Security headers on every response via middleware  
✅ CSRF on all state-changing routes  
✅ reCAPTCHA on all public forms  
✅ Input validation with max-length on all fields  
✅ UUID tokens (high entropy) for public access links  
✅ Eloquent ORM (no raw SQL injection risk)  
✅ Blade auto-escaping (no XSS from templates)  
✅ Admin account inactive check on every request  
✅ HSTS in production  
✅ Database above public root  

## Priority Recommendations

| Priority | Action |
|----------|--------|
| 1 | Add `throttle:5,1` to `/admin/login` POST route |
| 2 | Add `throttle:10,1` to `/contact/submit` POST route |
| 3 | Add `throttle:30,1` to `/chat/message` POST route |
| 4 | Verify WhatsApp webhook signature verification in `WhatsAppWebhookController` |
| 5 | Add admin password reset flow |
| 6 | Consider stronger CSP if third-party scripts are audited |
