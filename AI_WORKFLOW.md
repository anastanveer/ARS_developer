# AI_WORKFLOW.md — Mandatory 8-Phase Execution Protocol

> This protocol is not optional. Every task — no matter how small — must pass
> through all 8 phases in order. Skipping phases is a failure mode.
> Code is never generated before Phase 6 approval.

---

## The Core Principle

**Analyze first. Code never without approval.**

The correct response to "fix X" is not to fix X.
The correct response is to understand X, analyse the risk, identify the affected
files, write a plan, and then wait for the user to say "proceed."

This system is production. It serves real clients. Guessing is not engineering.

---

## Phase 1 — Understand

**Goal:** Fully understand what is being asked before forming any opinion.

Actions:
- Read the request at least twice
- Identify: what needs to change, what must stay exactly the same
- Identify: which system this touches (public page, admin, chat, SEO, DB, auth, API)
- Cross-reference with `CLAUDE.md` — critical paths
- Cross-reference with `PROJECT_RULES.md` — absolute prohibitions
- If the request is ambiguous, ask ONE specific clarifying question before proceeding

**Output:** A plain-English statement of what the task is and what it is not.

---

## Phase 2 — Inspect Project Structure

**Goal:** Build real knowledge of the affected area before forming a plan.

Actions:
- Read the relevant blade/controller/model/config/CSS files
- Identify the route(s) involved and their middleware chain
- Identify the model(s) involved and their relationships
- Check `PROJECT_MAP.md` for route and model reference
- Check `ARCHITECTURE_ANALYSIS.md` for system-level context
- Check `CHANGELOG_AI.md` for recent changes to the affected area
- Identify existing patterns in the codebase that must be followed

**Output:** A factual summary of what exists today — not what will change.

**Rule:** Claude must never propose a solution without having read the relevant files.
Proposing based on memory or assumptions is not permitted.

---

## Phase 3 — Risk Analysis

**Goal:** Identify every way this change could fail before touching anything.

Answer all of the following:

```
Blast radius:    What breaks if this goes wrong?
Reversibility:  Can this be undone with git revert? How?
Data risk:      Does this touch the database or stored files?
Auth risk:      Does this path through any auth middleware?
SEO risk:       Does this affect any page that is indexed?
CSS risk:       Does this require bundle.css sync?
JS risk:        Does this interact with script.js?
Email risk:     Does this trigger any email send?
WhatsApp risk:  Does this trigger any WhatsApp message?
```

Risk rating: **LOW** / **MEDIUM** / **HIGH** / **CRITICAL**

- LOW: isolated blade or CSS change, no logic, no routes, no DB
- MEDIUM: touches shared layout, config, or a route
- HIGH: touches auth, middleware, services, or database
- CRITICAL: touches admin auth, payments, or live data

**Rule:** A HIGH or CRITICAL rated task requires the user to be explicitly informed
of the risk level before any plan is presented.

---

## Phase 4 — Impact Analysis

**Goal:** Identify every file and system affected — direct and indirect.

List:
- Files that WILL be edited (with reason for each)
- Files that WILL be created (with reason for each)
- Files that WILL NOT be touched (confirmation this is correct)
- Systems affected: routes / auth / DB / SEO / CSS / JS / email / WhatsApp / admin
- Downstream effects: if file A changes, does file B or C break?

**Dual-system checks (always):**
- [ ] Is this a CSS change? → bundle.css sync required?
- [ ] Is this a new public page? → sitemap update required?
- [ ] Is this a new route? → named route conflict check required?
- [ ] Is this a config change? → `php artisan optimize` required on server?
- [ ] Is this a DB change? → migration + model + fillable all consistent?

---

## Phase 5 — Implementation Plan

**Goal:** Write the complete plan before writing any code.

Format:

```
## Implementation Plan

### What will change
- File 1: exactly what will change and why
- File 2: exactly what will change and why

### What will NOT change
- List of files confirmed untouched

### Approach
- Step-by-step description of the implementation
- Reference existing patterns being followed

### Alternatives considered
- What other approaches were ruled out and why

### Estimated risk: LOW / MEDIUM / HIGH / CRITICAL
```

**This plan is presented to the user. No code is written yet.**

---

## Phase 6 — Approval Gate

**No code is generated before this phase is complete.**

Claude must explicitly state:

> "Implementation plan is ready. Please review and approve to proceed.
> Type 'proceed', 'yes', or 'go' to begin implementation."

**Accepted approval signals:** "yes", "go", "proceed", "do it", "ok go ahead", "approved"

**If the user modifies the plan:** Return to Phase 5 with the updated plan before asking for approval again.

**If the user says "just do it" without a plan having been presented:** Claude must still present the plan first. Speed is not a reason to skip safety.

---

## Phase 7 — Execute (Minimal Safe Change)

**Goal:** Implement exactly what was approved — nothing more.

Rules during execution:
- Make the smallest change that achieves the goal
- Follow existing code style and indentation exactly
- Do not clean up surrounding code that is not part of the task
- Do not add comments unless explaining a genuinely non-obvious decision
- Do not introduce new abstractions, helpers, or patterns
- Do not rename variables or methods that are not part of the task
- If an unexpected issue is discovered mid-implementation, STOP and report it

**File editing order:**
1. Edit source files first (module CSS, blade, controller, model, config)
2. Sync derived files second (bundle.css after module CSS)
3. Add to version control last (specific files only, never `git add .`)

**Commit format:**
```bash
git add path/to/specific/file path/to/other/file
git commit -m "type(scope): short description — reason for change"
git push origin main
```

Types: `fix` `feat` `seo` `style` `docs` `config` `perf` `refactor`

---

## Phase 8 — Post-Implementation Handoff

**Goal:** Leave the user with everything needed to verify, test, and recover.

Always output all six sections:

### 1. What Changed
- File-by-file summary: what changed and why
- Commit hash

### 2. Testing Plan
From `TESTING_CHECKLIST.md` — the specific checks relevant to this change:
- What to test manually in the browser
- What URL to visit
- What to look for
- What adjacent features to verify haven't regressed

### 3. Six Review Checkpoints
Mini-assessment for each:
- Security Review: [pass/flag + note]
- Performance Review: [pass/flag + note]
- Scalability Review: [pass/flag + note]
- Maintainability Review: [pass/flag + note]
- QA Review: [pass/flag + note]
- Production Readiness Review: [pass/flag + note]

### 4. Rollback Plan
```bash
# Option 1: revert commit
git revert HEAD --no-edit
git push origin main

# Server
cd /home/arsdojcg/arsdeveloper.co.uk
git pull origin main
php artisan optimize
```

### 5. Server Deploy Command
```bash
cd /home/arsdojcg/arsdeveloper.co.uk
git pull origin main
php artisan optimize
```

### 6. Changelog Entry
Add to `CHANGELOG_AI.md`:
```
### [YYYY-MM-DD] — description
- **Files**: list
- **Type**: type
- **Commit**: hash
- **Risk**: level
- **Tested**: Yes/Partial/No
- **Notes**: context
```

---

## Four-Role Engineering Lens

Apply all four simultaneously during Phases 3, 4, and 7:

| Role | Primary question |
|------|----------------|
| **Principal Engineer** | Is this the right approach? Does it match existing patterns? |
| **Security Reviewer** | Does this expose data, weaken auth, or create injection risk? |
| **QA Lead** | What edge cases break this? What adjacent features regress? |
| **Performance Engineer** | Does this add queries, block the HTTP response, or increase page weight? |

---

## Shortcut Reference

| If the user says... | Claude does... |
|--------------------|---------------|
| "fix X" | Phase 1-6, present plan, wait for approval |
| "just fix it quickly" | Still Phase 1-6, present plan, wait for approval |
| "go ahead" (with no plan presented) | Present plan first, then ask for approval |
| "yes" / "proceed" / "go" after plan | Phase 7 execute |
| "what files will you change?" | Phase 4 impact analysis |
| "is this safe?" | Phase 3 risk analysis |
| "add a new feature" | Phase 1-6, HIGH risk flag if touches routes/DB/auth |
| "commit and push" | Phase 7 commit only — no new code |
| "read the code first" | Phase 2 inspect only — no plan yet |
