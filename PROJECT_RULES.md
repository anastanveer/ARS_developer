# PROJECT_RULES.md — Absolute Rules & Change-Control Protocol

> These rules are non-negotiable. No exception exists unless the user explicitly
> overrides a specific rule in writing for a specific task.

---

## Rule 0 — Analyze First, Code Never Without Approval

**Claude must never generate code immediately upon receiving a request.**

Every task must pass through the full 8-phase protocol in `AI_WORKFLOW.md` before
a single line of code is written. If Claude skips directly to code, it has failed.

The correct response to any task request is to begin Phase 1 (Understand + Inspect),
not to write an implementation.

**No code without explicit user approval of the implementation plan.**

---

## Rule 1 — Project Memory Before Proposals

Before proposing any solution, Claude must demonstrate understanding of:

- The relevant route(s) that will be affected
- The relevant model(s) and their relationships
- The relevant middleware in the request path
- The relevant config files
- The relevant blade templates and CSS files
- Any existing pattern in the codebase that should be followed

A proposal made without this understanding is a guess, not engineering.

---

## Rule 2 — Minimal Safe Change

When multiple implementations are possible, always choose the one that:

1. Touches the fewest files
2. Makes the smallest change to existing code
3. Introduces no new dependencies
4. Follows existing patterns exactly
5. Leaves all surrounding code untouched

**Never:** refactor, rename, clean up, reorganise, or "improve" code
that is not directly part of the requested change.

---

## Absolute Prohibitions — No Exceptions

### Database
- **NEVER** delete or truncate `database/database.sqlite`
- **NEVER** modify existing migration files
- **NEVER** rename Model properties, fillable fields, relationships, or DB columns
- **NEVER** run `migrate:fresh`, `migrate:reset`, or `db:seed` without explicit instruction
- **NEVER** make DB schema changes without a written impact analysis first

### Routes & Authentication
- **NEVER** remove, rename, or change the HTTP method of existing named routes
- **NEVER** modify `AdminAuthenticate` or `AdminRole` middleware logic
- **NEVER** change admin route prefix or guard configuration
- **NEVER** bypass, weaken, or restructure the authentication system
- **NEVER** make auth changes without a dedicated security review

### Dependencies
- **NEVER** edit files in `vendor/`
- **NEVER** edit `composer.lock` or `package-lock.json` directly
- **NEVER** add composer or npm packages without explicit user approval
- **NEVER** change `.env` values without explicit instruction
- **NEVER** upgrade package versions without explicit instruction

### Frontend & Assets
- **NEVER** remove or rename CSS classes that are referenced in Blade templates
- **NEVER** edit `bundle.css` without also editing the source module CSS file
- **NEVER** edit module CSS without also syncing `bundle.css`
- **NEVER** break mobile responsiveness — every change must work at 375px, 768px, 1280px
- **NEVER** remove or change Font Awesome icon classes in use
- **NEVER** touch `public/assets/js/script.js` without reading the full file first
- **NEVER** remove or alter AOS/WOW.js configuration

### SEO & Structured Data
- **NEVER** remove JSON-LD blocks from `header.blade.php`
- **NEVER** add `noindex` to a public-facing page
- **NEVER** remove or alter canonical URL logic
- **NEVER** remove or shorten the `$seoOverride` array from any page
- **NEVER** break the `/{slug}` catch-all route for dynamic SEO pages
- **NEVER** modify sitemap controller without verifying XML output

### Business Logic
- **NEVER** touch payment, invoice, or coupon logic without explicit instruction
- **NEVER** alter `WhatsAppWebhookController` or `WhatsAppService`
- **NEVER** change email template content or recipients without explicit instruction
- **NEVER** modify `MeetingBookingController` or the JSON booking store without instruction
- **NEVER** modify `RecaptchaService` or disable reCAPTCHA verification
- **NEVER** modify `BlockedContact` logic

### Behaviour
- **NEVER** make changes to more files than strictly necessary
- **NEVER** commit with `git add .` or `git add -A` — always add specific files
- **NEVER** push to remote without user confirmation
- **NEVER** make a change and then say "it should work" — verify or say it is untested
- **NEVER** write a commit message that is vague (e.g., "fix", "update", "changes")

---

## Change-Control Rules

### No Coding Without Approval
Claude must present the full Implementation Plan from `AI_WORKFLOW.md` Phase 5
and receive explicit user approval ("yes", "go", "do it", "proceed") before
writing any code.

### Multi-File Changes Require Justification
If a task requires editing more than 2 files, Claude must:
1. List every file that will change and why
2. Explain why each file cannot be avoided
3. Get explicit approval before proceeding

### Database Modifications Require Impact Analysis
Any task touching the database must include:
- Which tables are affected
- Which models need updating
- Whether existing data is affected
- Whether a migration rollback is possible
- Whether any existing query will break

### Authentication Changes Require Security Review
Any change near auth middleware, admin routes, or session handling must include:
- A full security review section
- Confirmation that no auth bypass is introduced
- Confirmation that role separation is preserved

### Deployment-Impacting Changes Require Rollback Documentation
Any change that affects routes, middleware, config, or database must include:
- The exact `git revert` command to undo it
- The server commands to run after rollback
- The expected site state after rollback

---

## Mandatory Review Checkpoints

Every implementation plan must include assessments for all six:

### 1. Security Review
- Does this introduce any input that is not validated?
- Does this expose any data without authentication?
- Does this weaken any existing auth or CSRF protection?
- Does this create any SQL injection or XSS surface?
- Does this change affect security headers?

### 2. Performance Review
- Does this add any unindexed DB queries?
- Does this add any synchronous blocking operations?
- Does this increase page weight (CSS, JS, image)?
- Does this affect Core Web Vitals (LCP, CLS, INP)?
- Does this bypass any existing caching?

### 3. Scalability Review
- Does this work correctly under concurrent requests?
- Does this write to the SQLite file? (concurrent write risk)
- Does this write to the meeting booking JSON file? (file lock risk)
- Does this grow in cost proportionally with traffic?

### 4. Maintainability Review
- Will a developer unfamiliar with this codebase understand this change?
- Does this follow existing patterns in the project?
- Does this introduce any magic, hidden coupling, or clever tricks?
- Does this increase or decrease technical debt?

### 5. QA Review
- What is the happy path test?
- What are the edge cases that could break this?
- What existing features could this inadvertently affect?
- Is there any test coverage that needs updating?

### 6. Production Readiness Review
- Is this safe to deploy to the live site immediately?
- Does this require any server-side command after deployment?
- Does this require any `.env` change on the server?
- Is a rollback plan written and ready?

---

## What Requires Explicit Permission (Beyond Standard Approval)

These actions require the user to explicitly name them in their request:

| Action | Required phrase |
|--------|----------------|
| Adding a new route | "add a route" or "create a new route" |
| Adding a new Model | "add a model" or "create a new table" |
| Adding a migration | "add a migration" or "change the database" |
| Adding a config file | "add a config" or "create a new config" |
| Modifying `app/Services/` | "update the service" or "change the service" |
| Changing admin behaviour | "change the admin" or "update admin panel" |
| Editing `header.blade.php` structured data | "update schema" or "change structured data" |
| Installing a package | "install" or "add the package" |
| Modifying middleware | "change middleware" or "update middleware" |
| Touching email templates | "update the email" or "change the email template" |
