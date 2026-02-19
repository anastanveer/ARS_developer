@php
    $page_title = 'Manage Meeting';
    $seoOverride = [
        'title' => 'Manage Meeting - ARSDeveloper',
        'description' => 'Private page to reschedule or cancel ARSDeveloper meeting bookings.',
        'keywords' => 'manage meeting arsdeveloper',
        'robots' => 'noindex, nofollow',
        'type' => 'WebPage',
    ];
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Manage <span>Meeting</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Manage Meeting</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 110px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div style="background:#fff;border:1px solid #d6e4fb;border-radius:18px;padding:28px;box-shadow:0 12px 30px rgba(16,57,117,0.08);">
                    @if(session('success'))
                        <div style="margin:0 0 14px;padding:12px;border-radius:10px;background:#e9f9ef;border:1px solid #bde8cc;color:#0f6f3f;font-weight:600;">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div style="margin:0 0 14px;padding:12px;border-radius:10px;background:#ffeceb;border:1px solid #ffd0cd;color:#9b2b22;font-weight:600;">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div style="margin:0 0 14px;padding:12px;border-radius:10px;background:#ffeceb;border:1px solid #ffd0cd;color:#9b2b22;font-weight:600;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <p style="margin:0 0 8px;color:#1d5ea9;font-weight:700;">Reference: MTG-{{ str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <h2 style="margin:0 0 8px;">Current booking details</h2>
                    <p style="margin:0 0 16px;">Status: <strong>{{ str_replace('_', ' ', ucfirst($lead->status)) }}</strong></p>

                    <div style="background:#f5f9ff;border:1px solid #d9e7fc;border-radius:12px;padding:14px 14px 10px;margin-bottom:18px;">
                        <ul style="margin:0;padding-left:18px;color:#3f587a;">
                            <li><strong>Date:</strong> {{ $meetingDateText }}</li>
                            <li><strong>Time:</strong> {{ $meetingTimeText }}</li>
                            <li><strong>Timezone:</strong> {{ $lead->meeting_timezone ?: 'Europe/London' }}</li>
                        </ul>
                    </div>

                    <h3 style="margin:0 0 10px;">Reschedule Meeting</h3>
                    <form id="meeting-manage-form" method="post" action="{{ route('meeting.reschedule', ['token' => $lead->meeting_token]) }}" style="margin-bottom:14px;">
                        @csrf
                        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
                            <div>
                                <label for="meetingDate" style="display:block;margin-bottom:6px;font-weight:700;">New Date</label>
                                <input id="meetingDate" type="date" name="meeting_date" required min="{{ now()->toDateString() }}" value="{{ old('meeting_date', optional($lead->meeting_date)->format('Y-m-d')) }}"
                                       style="width:100%;height:52px;padding:0 14px;border:1px solid #cddbf4;border-radius:10px;outline:none;">
                            </div>
                            <div>
                                <label for="meetingSlot" style="display:block;margin-bottom:6px;font-weight:700;">New Slot</label>
                                <select id="meetingSlot" name="meeting_slot" required style="width:100%;height:52px;padding:0 14px;border:1px solid #cddbf4;border-radius:10px;outline:none;">
                                    <option value="">Select Time Slot</option>
                                    @foreach($meetingSlots as $slot)
                                        <option value="{{ $slot }}" @selected(old('meeting_slot', $lead->meeting_slot) === $slot)>{{ $slot }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p style="margin:8px 0 0;font-size:13px;color:#5f7390;">Booked slots are hidden automatically for selected date.</p>
                        <button type="submit" class="thm-btn thm-btn-two" style="margin-top:14px;border:none;">
                            <span class="icon-right"></span> Save New Schedule
                        </button>
                    </form>

                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        <a href="{{ route('meeting.cancel', ['token' => $lead->meeting_token]) }}" class="thm-btn meeting-ghost-btn js-confirm-meeting-cancel">
                            <span class="icon-right"></span> Cancel Meeting
                        </a>
                        <a href="{{ route('meeting.confirmation', ['token' => $lead->meeting_token]) }}" class="thm-btn thm-btn-two" style="border:none;">
                            <span class="icon-right"></span> View Confirmation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="meetingCancelModal" style="position:fixed;inset:0;background:rgba(8,18,38,.55);display:none;align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div role="dialog" aria-modal="true" aria-labelledby="meetingCancelTitle" style="width:min(480px,100%);background:#fff;border:1px solid #d6e4fb;border-radius:16px;padding:22px;box-shadow:0 22px 48px rgba(12,45,97,.28);">
        <h3 id="meetingCancelTitle" style="margin:0 0 8px;font-size:24px;">Cancel This Meeting?</h3>
        <p style="margin:0 0 16px;color:#4b6285;">If you continue, meeting status will be updated to cancelled and client/admin email notifications will be sent.</p>
        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
            <button type="button" id="meetingCancelNoBtn" class="thm-btn meeting-ghost-btn" style="border:none;">No, Keep Meeting</button>
            <button type="button" id="meetingCancelYesBtn" class="thm-btn thm-btn-two" style="border:none;">Yes, Cancel</button>
        </div>
    </div>
</div>

<script>
    (function () {
        var dateInput = document.getElementById('meetingDate');
        var slotInput = document.getElementById('meetingSlot');
        if (!dateInput || !slotInput) {
            return;
        }

        var endpoint = @json(route('meeting.availability'));
        var excludeToken = @json($lead->meeting_token);

        function applySlotAvailability(bookedSlots) {
            var blocked = Array.isArray(bookedSlots) ? bookedSlots : [];
            var hasAvailable = false;
            var currentValue = slotInput.value;

            Array.prototype.slice.call(slotInput.options).forEach(function (opt) {
                if (!opt.value) return;
                var isBlocked = blocked.indexOf(opt.value) !== -1;
                opt.disabled = isBlocked;
                opt.hidden = isBlocked;
                if (!isBlocked) hasAvailable = true;
            });

            if (!hasAvailable) {
                slotInput.value = '';
                slotInput.disabled = true;
                if (slotInput.options[0]) {
                    slotInput.options[0].textContent = 'No slots available on this date';
                }
                return;
            }

            slotInput.disabled = false;
            if (slotInput.options[0]) {
                slotInput.options[0].textContent = 'Select Time Slot';
            }

            var currentOption = null;
            Array.prototype.slice.call(slotInput.options).forEach(function (opt) {
                if (opt.value === currentValue) {
                    currentOption = opt;
                }
            });

            if (!currentValue || !currentOption || currentOption.disabled) {
                var firstFree = Array.prototype.slice.call(slotInput.options).find(function (opt) {
                    return opt.value && !opt.disabled;
                });
                slotInput.value = firstFree ? firstFree.value : '';
            }
        }

        function loadAvailability() {
            var dateVal = dateInput.value;
            if (!dateVal) return;
            var url = endpoint + '?date=' + encodeURIComponent(dateVal) + '&exclude_token=' + encodeURIComponent(excludeToken);
            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.json(); })
                .then(function (payload) {
                    applySlotAvailability(payload.booked_slots || []);
                })
                .catch(function () {
                    applySlotAvailability([]);
                });
        }

        dateInput.addEventListener('change', loadAvailability);
        loadAvailability();

        var cancelLinks = document.querySelectorAll('.js-confirm-meeting-cancel');
        var cancelModal = document.getElementById('meetingCancelModal');
        var cancelNoBtn = document.getElementById('meetingCancelNoBtn');
        var cancelYesBtn = document.getElementById('meetingCancelYesBtn');
        var pendingHref = '';

        function closeCancelModal() {
            if (!cancelModal) return;
            cancelModal.style.display = 'none';
            pendingHref = '';
        }

        function openCancelModal(href) {
            if (!cancelModal) return;
            pendingHref = href || '';
            cancelModal.style.display = 'flex';
        }

        cancelLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                openCancelModal(link.getAttribute('href'));
            });
        });

        if (cancelNoBtn) {
            cancelNoBtn.addEventListener('click', closeCancelModal);
        }

        if (cancelYesBtn) {
            cancelYesBtn.addEventListener('click', function () {
                if (!pendingHref) {
                    closeCancelModal();
                    return;
                }
                window.location.href = pendingHref;
            });
        }

        if (cancelModal) {
            cancelModal.addEventListener('click', function (event) {
                if (event.target === cancelModal) {
                    closeCancelModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCancelModal();
            }
        });
    })();
</script>

@include('layouts.footer')
