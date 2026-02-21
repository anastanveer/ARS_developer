# GA4 Conversion Playbook (Implemented Events)

Measurement ID: `G-S9CN4PVV3B`

## Events tracked in website JS
- `generate_lead`
  - Trigger: successful contact, pricing requirements, and general inquiry forms
- `sign_up`
  - Trigger: successful newsletter form submission
- `schedule_meeting`
  - Trigger: successful meeting booking flow
- `begin_checkout`
  - Trigger: direct order flow when checkout starts

## Event parameters sent
- `form_type`
- `project_type`
- `budget_range`
- `value` (when available)
- `currency` (GBP when value exists)
- `page_path`
- `page_location`
- `method`

## GA4 admin setup required
1. Go to GA4 Admin -> Events.
2. Mark these as conversions:
- `generate_lead`
- `schedule_meeting`
- `begin_checkout`
3. Keep `sign_up` as conversion only if newsletter growth is a KPI.

## QA checklist
1. Open website and submit each flow once.
2. Confirm in GA4 Realtime:
- event names appear
- parameters are attached
3. Confirm no JS console errors after form success.

