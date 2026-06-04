# ARCHITECTURE_ANALYSIS.md — System Design Analysis

> Analysed from live codebase. Last updated: 2026-06-04.

---

## Architecture Pattern

**Monolithic Laravel MVC** — single application handling:
- Public marketing website (38 pages)
- Admin panel (20+ controllers, role-based)
- Chat widget API (real-time-like JSON polling)
- Meeting booking system (JSON file-based store)
- Client portal (token-based access)
- WhatsApp Business integration
- SEO infrastructure (sitemap, IndexNow, structured data)
- Internal finance/CRM (invoices, projects, expenses)

No microservices. No queue worker (email sent synchronously). No Redis.

---

## Authentication Architecture

### Admin Auth — Custom Session-Based
```
POST /admin/login
  → AdminAuthController validates credentials against admin_users table
  → Sets session keys: admin_authenticated, admin_role, admin_user_id, admin_name
  → AdminAuthenticate middleware reads session on every request
  → AdminRole middleware enforces per-route role requirements
```

**Three roles:**
| Role constant | Value | Access |
|--------------|-------|--------|
| `ROLE_SUPER` | `super_admin` | Everything |
| `ROLE_ADVANCED` | `advanced_admin` | Most admin areas |
| `ROLE_BLOG` | `blog_seo_admin` | Blog only |

**Key behaviour:** AdminAuthenticate re-fetches `AdminUser` from DB on every request and checks `is_active`. Inactive accounts are immediately ejected even mid-session. This is correct.

**Gap:** No password reset flow is visible in the codebase. Admin password changes require direct DB access or a custom admin action.

### Public Auth — Token-Based
- Client portal: UUID token in URL (`/client-portal/{token}`)
- Invoices: UUID in URL (`/invoice/{uuid}`)
- Meetings: token in URL for manage/cancel (`/meeting/manage/{token}`)
- Reviews: token in URL (`/review/{token}`)
- Chat: `public_token` (UUID) stored in browser, passed with each request

No Laravel Sanctum. No Laravel Passport. No JWT.

### Public Forms — reCAPTCHA
All public form submissions go through `RecaptchaService::verify()` before processing. BlockedContact table provides IP/email-level blocking as a secondary layer.

---

## Request Lifecycle

```
HTTP Request
  ↓
ForcePrimaryDomain (web middleware)
  → 301 redirect if wrong domain
  → 301 redirect if legacy .php URL
  → 301 redirect if mixed-case path
  → 301 strip UTM/tracking params (except /blog?page=N)
  → On response: inject security headers
  → On response: inject X-Robots-Tag
  → On response: inject canonical Link header
  → On response: auto-inject alt/title/loading/decoding on <img> tags
  ↓
CSRF verification (except stripe/webhook, whatsapp/webhook)
  ↓
AdminAuthenticate (admin routes only)
  ↓
AdminRole (specific admin routes)
  ↓
Controller → Model → Blade / JSON response
```

---

## Database Architecture

**Engine:** SQLite (single file at `database/database.sqlite`)

**Strengths:**
- Zero infrastructure — no DB server to manage or secure
- File-level backup is trivial
- Perfect for low-to-medium concurrent traffic

**Risks:**
- SQLite write lock: only one write at a time. Concurrent form submissions or chat messages can queue. Under high traffic this becomes a bottleneck.
- No connection pooling
- File must be readable/writable by the web process. Incorrect permissions = instant 500.

**Key table relationships:**
```
leads ──────────────────── coupon_redemptions ── coupons
leads (meeting fields)
clients ─────────────────── projects ─── project_milestones
                                      └── project_requirements
clients ─────────────────── invoices ─── payments
chat_conversations ──────── chat_messages
blog_posts ──────────────── blog_comments
portfolios
admin_users ─────────────── admin_notification_reads
```

**Meeting booking store:** NOT in SQLite. Uses a flat JSON file at `storage/app/meeting-bookings.json`. This is intentionally simple but:
- No atomic writes — concurrent booking attempts could corrupt the file
- No indexing — scanning all bookings on each availability check
- Acceptable for low-volume use (1-5 bookings/day)

---

## SEO Architecture

This is one of the most sophisticated parts of the system.

### Dynamic Meta per Page
Every blade page defines `$seoOverride` array at the top:
```php
$seoOverride = [
    'title' => '...',           // max 60 chars
    'description' => '...',     // 120-155 chars
    'keywords' => '...',
    'related_links' => [...],   // internal link suggestions
    'faq_items' => [...],       // FAQ schema items
];
```
`header.blade.php` reads this array and outputs all meta tags.

### Structured Data (JSON-LD)
`header.blade.php` outputs 10+ schema types on every page:
- Organization + LocalBusiness
- WebSite with SearchAction
- ProfessionalService
- AggregateRating (from approved reviews)
- SiteNavigationElement
- ItemList (pillar/cluster pages)
- OfferCatalog (22+ services)
- Person/Founder schema
- Speakable schema

### Dynamic SEO Service Pages
`config/seo_service_pages.php` defines 40+ pages. The catch-all route `/{slug}` resolves slugs against this config and renders `pages.seo-service-page` with the config data injected. This allows adding new SEO landing pages without writing new blade files.

### ForcePrimaryDomain SEO Functions
- Strips UTM/tracking params via 301 (no duplicate content from tracked URLs)
- Forces lowercase paths (no duplicate content from mixed-case)
- Forces primary domain (no duplicate content from www/non-www)
- Sets canonical Link header on every response
- Sets X-Robots-Tag header (noindex on admin, portal, policy, search pages)
- Injects `alt`, `title`, `loading`, `decoding`, `fetchpriority`, `width`, `height` on all `<img>` tags automatically

---

## Frontend Architecture

**No JavaScript framework.** Pure Blade templates with:
- Tailwind CSS v4 (utility classes in templates)
- Custom CSS in `public/assets/css/bundle.css` (pre-compiled, not Vite-managed)
- `public/assets/js/script.js` — monolithic JS file handling:
  - AOS scroll animations init
  - WOW.js init
  - Chat widget state machine (robot + panel)
  - Contact form AJAX
  - Meeting scheduler multi-step form
  - FAQ accordion
  - Search autocomplete

**CSS dual-system (important):**
Vite manages `resources/css/app.css` (Tailwind) → compiled to `public/build/`.
BUT the main served CSS is `public/assets/css/bundle.css` which is maintained manually. This is a hybrid system — Tailwind for newer components, bundle.css for legacy/complex components.

**Chat widget:** Div-based animated robot. 20 CSS animation states cycled by a JS state machine using `setTimeout`. Speech bubble text changes per state. No WebSocket — the admin panel polls for new messages.

---

## Email Architecture

- All emails sent via Laravel Mail (SMTP via `.env` config)
- Email types:
  - Contact form: admin notification + user acknowledgement
  - Chat: admin notification when new chat message arrives
  - Meeting: confirmation + 24h reminder + 2h reminder
  - Invoice: sent from admin panel
- `EmailLog` model tracks outbound emails
- **Emails sent synchronously** (no queue driver confirmed) — if SMTP is slow, the HTTP response blocks

---

## Key Architectural Decisions

| Decision | Rationale | Trade-off |
|----------|-----------|-----------|
| SQLite over MySQL | Zero infrastructure on shared hosting | Concurrent write limit |
| Custom session admin auth | Full control, no package dependency | No built-in password reset |
| JSON file for meeting bookings | Simple, no extra tables | Not atomic under concurrent writes |
| Catch-all `/{slug}` route | Adds 40+ SEO pages from one config | Must be last route in web.php |
| ForcePrimaryDomain does everything | One middleware for domain, SEO, security headers, image enrichment | Large single file, hard to debug |
| Synchronous email | Simple, no queue config needed | Slow SMTP = slow HTTP responses |
| bundle.css manual sync | Legacy asset pipeline compatibility | Dual-maintenance burden |
