# PROJECT_MAP.md — Complete Project Structure Map

> Generated from live codebase inspection. Update when adding new routes, models, or services.

---

## Stack
| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 12.x |
| Language | PHP | 8.2 |
| Database | SQLite | File-based (`database/database.sqlite`) |
| Frontend CSS | Tailwind CSS | v4 |
| Build Tool | Vite | 7.x |
| JS Animations | AOS + WOW.js | Bundled in `bundle.css`/`script.js` |
| Icons | Font Awesome | Bundled |
| Forms | reCAPTCHA | v2/v3 via `RecaptchaService` |
| Chat | Custom WebSocket-less | Long-poll JSON via `ChatWidgetController` |
| WhatsApp | WhatsApp Business API | via `WhatsAppService` + `config/whatsapp.php` |
| Email | Laravel Mail | SMTP (config via `.env`) |
| Meetings | Custom JSON store | `storage/app/meeting-bookings.json` |

---

## Directory Structure

```
ars_uk_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           ← 20 admin controllers
│   │   │   ├── ChatWidgetController.php
│   │   │   ├── ContactFormController.php
│   │   │   ├── BlogPageController.php
│   │   │   ├── HomeController.php
│   │   │   ├── MeetingBookingController.php
│   │   │   ├── PortfolioPageController.php
│   │   │   ├── PricingController.php
│   │   │   ├── PublicInvoiceController.php
│   │   │   ├── SearchController.php
│   │   │   ├── SeoHubController.php
│   │   │   ├── SitemapController.php
│   │   │   ├── ClientPortalController.php
│   │   │   ├── ClientReviewController.php
│   │   │   ├── WhatsAppWebhookController.php
│   │   │   └── BlogCommentController.php
│   │   └── Middleware/
│   │       ├── AdminAuthenticate.php   ← session-based admin guard
│   │       ├── AdminRole.php           ← role-based access (super/advanced/blog)
│   │       └── ForcePrimaryDomain.php  ← domain redirect + security headers + robots
│   ├── Models/                  ← 29 Eloquent models (see Database section)
│   ├── Services/
│   │   ├── IndexNowService.php         ← pings Bing/Google on new content
│   │   ├── MeetingReminderService.php  ← sends meeting reminders
│   │   ├── RecaptchaService.php        ← verifies reCAPTCHA tokens
│   │   ├── SystemLogService.php        ← log file digest + analysis
│   │   └── WhatsAppService.php         ← WhatsApp Business API calls
│   ├── Mail/                    ← email notification templates
│   └── Support/                 ← utility helpers (ServicePageImages, etc.)
├── bootstrap/
│   └── app.php                  ← middleware registration + CSRF exceptions
├── config/
│   ├── company.php              ← legal name, address, phone, reg number
│   ├── contact.php              ← inbox email, meeting slots, timezones
│   ├── recaptcha.php            ← reCAPTCHA keys
│   ├── whatsapp.php             ← WhatsApp API config
│   ├── indexnow.php             ← IndexNow API key
│   ├── seo_service_pages.php    ← 40+ dynamic SEO landing page definitions
│   ├── regions.php              ← UK regional config
│   └── site_search.php          ← search configuration
├── database/
│   ├── database.sqlite          ← LIVE DATABASE — never delete
│   └── migrations/              ← 44 migration files
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── bundle.css       ← SERVED CSS (must sync with module-css)
│   │   │   └── module-css/      ← source CSS files per component
│   │   └── js/
│   │       └── script.js        ← main JS (AOS, WOW, chat, forms, etc.)
│   └── storage → ../storage/app/public (symlink)
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── header.blade.php ← global head, SEO, JSON-LD, nav
│       │   └── footer.blade.php ← global footer, scripts
│       ├── pages/               ← 38 blade page templates
│       ├── partials/
│       │   └── chat-widget.blade.php ← robot chat button + panel
│       ├── admin/               ← admin panel views
│       ├── emails/              ← email templates
│       └── sitemaps/            ← XML sitemap templates
├── routes/
│   └── web.php                  ← ALL routes (public + admin + API)
└── storage/
    ├── app/
    │   ├── meeting-bookings.json ← meeting slot booking store
    │   └── public/              ← user-uploaded files
    └── logs/
        └── laravel.log
```

---

## Routes Map

### Public Pages
| Method | URL | Controller | Notes |
|--------|-----|-----------|-------|
| GET | `/` | `HomeController` | Homepage |
| GET | `/about` | view: `pages.about` | About page |
| GET | `/services` | view: `pages.services` | Services overview |
| GET | `/software-development` | view | Service page |
| GET | `/web-design-development` | view | Service page |
| GET | `/digital-marketing` | view | Service page |
| GET | `/search-engine-optimization` | view | Service page |
| GET | `/design-and-branding` | view | Service page |
| GET | `/app-development` | view | Service page |
| GET | `/portfolio` | `PortfolioPageController` | Portfolio list |
| GET | `/portfolio-details/{slug}` | `PortfolioPageController` | Portfolio item |
| GET | `/blog` | `BlogPageController@index` | Blog list |
| GET | `/blog/{slug}` | `BlogPageController@show` | Blog post |
| GET | `/pricing` | `PricingController` | Pricing page |
| GET | `/contact` | view: `pages.contact` | Contact page |
| GET | `/faq` | view: `pages.faq` | FAQ page |
| GET | `/testimonials` | view: `pages.testimonials` | Testimonials |
| GET | `/gallery` | view: `pages.gallery` | Gallery |
| GET | `/book` | view: `pages.book` | Meeting booking |
| GET | `/uk-growth-hub` | `SeoHubController` | SEO hub |
| GET | `/sectors/{sector}` | view: `pages.sector-landing` | Sector pages |
| GET | `/{slug}` | view: `pages.seo-service-page` | Dynamic SEO pages |

### Chat API (JSON)
| Method | URL | Controller | Auth |
|--------|-----|-----------|------|
| GET | `/chat/bootstrap` | `ChatWidgetController@bootstrap` | None |
| POST | `/chat/profile` | `ChatWidgetController@profile` | None |
| POST | `/chat/message` | `ChatWidgetController@message` | None |
| GET | `/chat/conversation/{token}` | `ChatWidgetController@conversation` | Token |

### Forms
| Method | URL | Controller |
|--------|-----|-----------|
| POST | `/contact/submit` | `ContactFormController` |
| POST | `/blog/comment` | `BlogCommentController` |

### Meetings
| Method | URL | Controller |
|--------|-----|-----------|
| GET | `/meeting/availability` | `MeetingBookingController@availability` |
| POST | `/meeting/confirm` | `MeetingBookingController@confirm` |
| POST | `/meeting/reschedule` | `MeetingBookingController@reschedule` |
| POST | `/meeting/cancel` | `MeetingBookingController@cancel` |
| GET | `/meeting/confirmation/{token}` | `MeetingBookingController@confirmation` |
| GET | `/meeting/manage/{token}` | `MeetingBookingController@manage` |

### Token-Based Public Access
| Method | URL | Controller | Auth |
|--------|-----|-----------|------|
| GET | `/client-portal/{token}` | `ClientPortalController` | UUID token |
| GET | `/invoice/{uuid}` | `PublicInvoiceController` | UUID |
| GET | `/review/{token}` | `ClientReviewController@show` | Token |
| POST | `/review/{token}` | `ClientReviewController@store` | Token |

### Admin Panel (prefix: /admin, middleware: admin.auth)
| Method | URL | Role Required |
|--------|-----|--------------|
| GET/POST | `/admin/login` | None |
| GET | `/admin/dashboard` | super, advanced |
| GET | `/admin/leads` | super, advanced |
| GET | `/admin/blog-posts` | super, advanced, blog |
| GET | `/admin/portfolio` | super, advanced |
| GET | `/admin/clients` | super, advanced |
| GET | `/admin/finance` | super |
| GET | `/admin/invoices` | super, advanced |
| GET | `/admin/projects` | super, advanced |
| GET | `/admin/analytics` | super, advanced |
| GET | `/admin/audit-reports` | super |
| GET | `/admin/chat` | super, advanced |
| GET | `/admin/system-logs` | super |
| GET | `/admin/users` | super |

### Infrastructure
| Method | URL | Notes |
|--------|-----|-------|
| GET | `/sitemap.xml` | Sitemap index |
| GET | `/sitemaps/pages.xml` | Static pages |
| GET | `/sitemaps/portfolio.xml` | Portfolio items |
| GET | `/sitemaps/blog.xml` | Blog posts |
| POST | `/whatsapp/webhook` | CSRF exempt |
| POST | `/stripe/webhook` | CSRF exempt |
| GET | `/search` | Search results |
| GET | `/search/suggest` | Search autocomplete |
| GET | `/up` | Laravel health check |

---

## Database Models Map

| Model | Table | Key Relationships |
|-------|-------|-------------------|
| `AdminUser` | `admin_users` | Roles: super_admin, advanced_admin, blog_seo_admin |
| `Lead` | `leads` | Contacts + meeting bookings + coupons |
| `Client` | `clients` | Linked to projects, invoices |
| `Project` | `projects` | hasMany milestones, requirements |
| `ProjectMilestone` | `project_milestones` | belongsTo project |
| `ProjectRequirement` | `project_requirements` | belongsTo project |
| `Invoice` | `invoices` | hasMany payments |
| `Payment` | `payments` | belongsTo invoice |
| `Portfolio` | `portfolios` | Public portfolio items |
| `BlogPost` | `blog_posts` | hasMany comments |
| `BlogComment` | `blog_comments` | belongsTo blog_post |
| `ChatConversation` | `chat_conversations` | hasMany messages |
| `ChatMessage` | `chat_messages` | belongsTo conversation |
| `Coupon` | `coupons` | hasMany redemptions |
| `CouponRedemption` | `coupon_redemptions` | belongsTo coupon + lead |
| `ClientReview` | `client_reviews` | Public testimonials |
| `BlockedContact` | `blocked_contacts` | Email/IP block list |
| `EmailLog` | `email_logs` | Outbound email audit trail |
| `MonthlyMetric` | `monthly_metrics` | Dashboard analytics |
| `MonthlySourceMetric` | `monthly_source_metrics` | Traffic source breakdown |
| `CompanyBudget` | `company_budgets` | Internal finance tracking |
| `CompanyExpense` | `company_expenses` | Internal expense tracking |
| `TeamHire` | `team_hires` | HR/team tracking |
| `AuditReport` | `audit_reports` | SEO/site audit reports |
| `AuditScanRun` | `audit_scan_runs` | Audit scan history |
| `AuditTarget` | `audit_targets` | Audit target URLs |
| `AuditActionItem` | `audit_action_items` | Audit action tasks |
| `WhatsAppWebhookLog` | `whatsapp_webhook_logs` | Incoming WhatsApp events |
| `AdminNotificationRead` | `admin_notification_reads` | Read receipts for admin alerts |
| `User` | `users` | Default Laravel users (unused for admin) |

---

## Config Files Reference

| File | What it controls |
|------|-----------------|
| `config/company.php` | Legal name, address, phone, Companies House no, opening hours |
| `config/contact.php` | Inbox email, meeting slots, timezone options, booking store path |
| `config/recaptcha.php` | reCAPTCHA site/secret key, verify URL |
| `config/whatsapp.php` | WhatsApp API token, phone number ID, admin recipient |
| `config/indexnow.php` | IndexNow API key for search engine ping |
| `config/seo_service_pages.php` | 40+ dynamic SEO landing pages (slug, title, meta, FAQs, geo) |
| `config/regions.php` | UK regional targeting config |
| `config/site_search.php` | Site search configuration |

---

## Services Reference

| Service | Purpose | Key Methods |
|---------|---------|-------------|
| `WhatsAppService` | Send WhatsApp messages via Business API | `sendTextMessage()`, `sendTemplateMessage()`, `normalizePhone()` |
| `RecaptchaService` | Verify reCAPTCHA response | `verify($token, $ip)` |
| `IndexNowService` | Ping Bing/Google IndexNow on new content | `ping($url)` |
| `MeetingReminderService` | Send 24h + 2h meeting reminders | `sendReminders()` |
| `SystemLogService` | Parse and digest Laravel log files | `getLogFiles()`, `generateDigest()` |

---

## Frontend Asset Map

| File | Purpose | Edit rule |
|------|---------|-----------|
| `public/assets/css/bundle.css` | **Served CSS** — everything combined | Always sync with module-css source |
| `public/assets/css/module-css/chat-widget.css` | Chat robot + panel styles | Edit here, then sync bundle |
| `public/assets/css/module-css/*.css` | Per-component CSS | Edit here, then sync bundle |
| `public/assets/js/script.js` | AOS, WOW, forms, chat JS, robot state machine | Read fully before touching |
| `resources/css/app.css` | Tailwind entry point | Do not edit without Vite rebuild |
| `resources/js/app.js` | JS entry point for Vite | Do not edit without Vite rebuild |

---

## Environment Variables (Key)

| Variable | Purpose |
|----------|---------|
| `APP_URL` | Base URL of the application |
| `APP_PRIMARY_DOMAIN` | Canonical domain for redirect middleware |
| `APP_CANONICAL_SCHEME` | `https` in production |
| `APP_REDIRECT_DOMAINS` | Comma-separated domains to 301 to primary |
| `COMPANY_*` | Company legal details (legal_name, phone, email, etc.) |
| `CONTACT_INBOX_EMAIL` | Where contact/chat notifications are sent |
| `RECAPTCHA_SITE_KEY` | reCAPTCHA public key |
| `RECAPTCHA_SECRET_KEY` | reCAPTCHA private key |
| `WHATSAPP_*` | WhatsApp Business API credentials |
| `MAIL_*` | SMTP email configuration |
| `CONTACT_BOOKED_DATES` | Comma-separated dates to block from booking |
