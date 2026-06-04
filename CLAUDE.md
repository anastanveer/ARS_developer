# CLAUDE.md — ARS Developer UK · Maximum Engineering Discipline Mode

> This file is the primary instruction set. Claude must read this file, PROJECT_RULES.md,
> AI_WORKFLOW.md, PROJECT_MAP.md, and ARCHITECTURE_ANALYSIS.md before responding to any task.
> Non-compliance with these rules is a failure mode, not a shortcut.

---

## Project Identity

| Property | Value |
|----------|-------|
| Name | ARS Developer Ltd |
| Domain | arsdeveloper.co.uk |
| Framework | Laravel 12.0 + PHP 8.2 |
| Frontend | Tailwind CSS v4 + Vite 7 |
| Database | SQLite (`database/database.sqlite`) — LIVE, production data |
| Hosting | cPanel shared hosting, premium135, `/home/arsdojcg/arsdeveloper.co.uk` |
| Status | **PRODUCTION SYSTEM — treat every change as high-risk** |

---

## Mandatory Pre-Task Reading Order

Before responding to ANY task, Claude must read in this order:

1. `CLAUDE.md` — identity, rules, production status
2. `PROJECT_RULES.md` — absolute prohibitions and change-control rules
3. `AI_WORKFLOW.md` — mandatory 8-phase execution protocol
4. `PROJECT_MAP.md` — routes, models, services, config files
5. `ARCHITECTURE_ANALYSIS.md` — auth system, request lifecycle, design decisions

**Claude must never propose or generate code before completing this reading sequence.**

---

## Production System Rules

This is a live, revenue-generating business system. Every change carries real risk.

- A broken homepage = lost client enquiries
- A broken admin panel = founder cannot manage leads
- A broken contact form = lost business
- A broken chat widget = lost live conversations
- A broken meeting scheduler = lost client calls

**Treat every change as if it requires a rollback plan written before the first line of code.**

---

## Critical Paths — Never Break These

| Path | Purpose | Risk if broken |
|------|---------|---------------|
| `routes/web.php` | All routes — public + admin + API | Site goes dark |
| `app/Http/Controllers/Admin/` | 20+ admin controllers | Admin panel inaccessible |
| `app/Models/` | 25+ Eloquent models | DB queries fail sitewide |
| `database/migrations/` | Schema history | Cannot migrate or rollback |
| `database/database.sqlite` | Live production data | **Catastrophic data loss** |
| `resources/views/layouts/header.blade.php` | Global SEO, JSON-LD, nav | Sitewide SEO collapse |
| `resources/views/layouts/footer.blade.php` | Scripts, chat widget init | JS broken sitewide |
| `resources/views/pages/` | All 38 public pages | Public pages 500 |
| `resources/views/admin/` | Admin panel views | Admin inaccessible |
| `public/assets/css/bundle.css` | Served CSS — all styling | Site renders unstyled |
| `public/assets/js/script.js` | AOS, chat, forms, robot | All JS features broken |
| `config/seo_service_pages.php` | 40+ dynamic SEO pages | SEO pages 404 |
| `app/Services/` | Business logic services | Emails, WhatsApp, meetings fail |
| `app/Http/Middleware/` | Auth, domain, security | Auth bypassed or site locked |

---

## Architecture Memory — Build Before You Act

Claude must understand these systems before proposing any change:

### Authentication (Two separate systems)
- **Admin:** Custom session-based (`admin_authenticated` session key, `AdminAuthenticate` + `AdminRole` middleware)
- **Public:** UUID token-based for portal, invoice, meeting, review, chat
- **Forms:** reCAPTCHA via `RecaptchaService` + `BlockedContact` IP/email blocking

### Asset Pipeline (Dual system — critical)
- `public/assets/css/bundle.css` = manually maintained served CSS
- `public/assets/css/module-css/*.css` = source files (not served directly)
- **Rule:** Editing module CSS requires syncing the matching block in `bundle.css`
- Vite manages only `resources/css/app.css` → `public/build/` (Tailwind)

### SEO Infrastructure (Do not degrade)
- `$seoOverride` array at top of every blade page controls meta tags
- `header.blade.php` outputs 10+ JSON-LD schema types — never remove
- `ForcePrimaryDomain` middleware handles: domain redirect, UTM strip, legacy URL redirect, mixed-case normalise, canonical header, robots header, image attribute injection
- `config/seo_service_pages.php` powers 40+ dynamic pages via catch-all `/{slug}` route

### Database (SQLite — file-based)
- Single file: `database/database.sqlite` — NEVER delete, truncate, or reset
- 29 models, 44+ migrations
- Meeting bookings stored in JSON file: `storage/app/meeting-bookings.json`

### Email (Synchronous — blocking)
- Emails sent synchronously in HTTP request cycle
- Slow SMTP = slow HTTP response — no queue driver
- Affects: contact form, chat messages, meeting confirmations

---

## Preserved Systems — Never Alter Without Explicit Instruction

| System | Files | Why protected |
|--------|-------|--------------|
| UI + Responsiveness | All blade + CSS | Live customer-facing site |
| Named Routes | `routes/web.php` | External links, sitemaps, SEO |
| DB Schema | All migrations + models | Production data integrity |
| Admin Auth | `AdminAuthenticate`, `AdminRole` | Security boundary |
| WhatsApp Integration | `WhatsAppService`, webhook controller | Live business comms |
| Payment Logic | Invoice, payment controllers | Financial integrity |
| reCAPTCHA | `RecaptchaService` | Spam protection |
| JSON-LD Structured Data | `header.blade.php` | SEO rankings |
| Sitemap | `SitemapController` | Google indexing |
| ForcePrimaryDomain | Middleware | SEO + security headers |

---

## Deployment Workflow

```bash
# Local
git add <specific files only>
git commit -m "type: description"
git push origin main

# Server
cd /home/arsdojcg/arsdeveloper.co.uk
git pull origin main
php artisan optimize
```

`php artisan optimize` = config + route + view + event cache. Use this on deploy.
`php artisan optimize:clear` = clear all caches. Use only after config changes.

---

## Stack Reference

| Component | Version | Notes |
|-----------|---------|-------|
| Laravel | 12.x | No Sanctum, no Passport |
| PHP | 8.2 | Shared hosting |
| SQLite | File-based | No MySQL/Postgres |
| Tailwind | v4 | Via Vite |
| Vite | 7.x | Build only |
| Font Awesome | Bundled | Do not swap versions |
| AOS | Bundled | `once:true`, `mirror:false` |
| WOW.js | Bundled | Visibility override applied |
