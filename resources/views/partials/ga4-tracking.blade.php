@php
    $ga4MeasurementId = 'G-S9CN4PVV3B';
    $ga4DebugMode = app()->environment('local');
    $ga4FlashEvent = session('ga4_flash_event');
@endphp
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4MeasurementId }}"></script>
<script>
    (function () {
        var measurementId = @json($ga4MeasurementId);
        var debugMode = @json($ga4DebugMode);
        var flashEvent = @json($ga4FlashEvent);

        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () {
            window.dataLayer.push(arguments);
        };

        if (!window.__arsGa4Configured) {
            window.gtag('js', new Date());
            window.gtag('config', measurementId, { debug_mode: debugMode });
            window.__arsGa4Configured = true;
        }

        function cleanParams(params) {
            var cleaned = {};
            Object.keys(params || {}).forEach(function (key) {
                var value = params[key];
                if (value === null || value === undefined || value === '') {
                    return;
                }
                cleaned[key] = value;
            });
            return cleaned;
        }

        function toNumber(value) {
            if (value === null || value === undefined) {
                return null;
            }
            var normalized = String(value).replace(/[^0-9.\-]/g, '');
            if (!normalized) {
                return null;
            }
            var parsed = parseFloat(normalized);
            return isNaN(parsed) ? null : parsed;
        }

        function formValue(form, name) {
            if (!form) {
                return '';
            }
            var field = form.querySelector('[name="' + name + '"]');
            return field ? String(field.value || '').trim() : '';
        }

        function elementLabel(node) {
            if (!node) {
                return '';
            }
            return String(
                node.getAttribute('data-ga4-label')
                || node.getAttribute('aria-label')
                || node.textContent
                || node.value
                || ''
            ).replace(/\s+/g, ' ').trim().slice(0, 120);
        }

        window.arsTrackGa4Event = function (eventName, params) {
            if (!eventName) {
                return;
            }

            var payload = cleanParams(params || {});
            if (debugMode) {
                payload.debug_mode = true;
            }

            try {
                if (typeof window.gtag === 'function') {
                    window.gtag('event', eventName, payload);
                    return;
                }
            } catch (e) {
                // Silent fallback below.
            }

            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(Object.assign({ event: eventName }, payload));
        };

        if (!window.__arsGa4ListenersBound) {
            document.addEventListener('click', function (event) {
                var trigger = event.target.closest('[data-ga4-event], button[type="submit"], a.thm-btn, a.btn, .sticky-quick-actions__item, .support-link, .quick-link, .meeting-scheduler__btn');
                if (!trigger || trigger.getAttribute('data-ga4-ignore') === 'true') {
                    return;
                }

                var eventName = trigger.getAttribute('data-ga4-event') || 'button_click';
                window.arsTrackGa4Event(eventName, {
                    button_text: elementLabel(trigger),
                    page_path: window.location.pathname,
                    page_location: window.location.href,
                    destination_url: trigger.getAttribute('href') || undefined,
                    button_type: trigger.tagName.toLowerCase(),
                });
            }, true);

            document.addEventListener('submit', function (event) {
                var form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                var eventName = form.getAttribute('data-ga4-submit-event');
                if (!eventName) {
                    return;
                }

                var params = {
                    form_name: form.getAttribute('data-ga4-form-name') || form.id || undefined,
                    form_action: form.getAttribute('action') || undefined,
                    method: (form.getAttribute('method') || 'get').toLowerCase(),
                    page_path: window.location.pathname,
                    page_location: window.location.href,
                    form_type: formValue(form, 'form_type') || undefined,
                    project_type: formValue(form, 'project_type') || undefined,
                    budget_range: formValue(form, 'budget_range') || undefined,
                    meeting_date: formValue(form, 'meeting_date') || undefined,
                    meeting_slot: formValue(form, 'meeting_slot') || undefined,
                    rating: formValue(form, 'rating') || undefined,
                    search_term: formValue(form, 'q') || undefined,
                    invoice_id: formValue(form, 'invoice_id') || undefined,
                };

                var valueField = form.getAttribute('data-ga4-value-field');
                if (valueField) {
                    var numericValue = toNumber(formValue(form, valueField));
                    if (numericValue !== null) {
                        params.value = numericValue;
                    }
                }

                var staticCurrency = form.getAttribute('data-ga4-currency');
                if (staticCurrency) {
                    params.currency = staticCurrency;
                }

                window.arsTrackGa4Event(eventName, params);
            }, true);

            window.__arsGa4ListenersBound = true;
        }

        if (flashEvent && flashEvent.name) {
            var fireFlashEvent = function () {
                window.arsTrackGa4Event(flashEvent.name, flashEvent.params || {});
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fireFlashEvent, { once: true });
            } else {
                fireFlashEvent();
            }
        }
    })();
</script>
