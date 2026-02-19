@php
    $page_title = 'Meeting Confirmed';
    $seoOverride = [
        'title' => 'Meeting Confirmation - ARSDeveloper',
        'description' => 'Private meeting confirmation page for ARSDeveloper bookings.',
        'keywords' => 'meeting confirmation arsdeveloper',
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
            <h1>Meeting <span>Confirmed</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Meeting Confirmed</li>
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
                    <p style="margin:0 0 8px;color:#1d5ea9;font-weight:700;">Reference: MTG-{{ str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <h2 style="margin:0 0 8px;">Your call is booked successfully</h2>
                    <p style="margin:0 0 16px;">We have also emailed your confirmation details and manage links.</p>

                    <div style="background:#f5f9ff;border:1px solid #d9e7fc;border-radius:12px;padding:14px 14px 10px;margin-bottom:18px;">
                        <p style="margin:0 0 8px;font-weight:700;color:#173f7f;">Meeting Details</p>
                        <ul style="margin:0;padding-left:18px;color:#3f587a;">
                            <li><strong>Date:</strong> {{ $meetingDateText }}</li>
                            <li><strong>Time:</strong> {{ $meetingTimeText }}</li>
                            <li><strong>Timezone:</strong> {{ $lead->meeting_timezone ?: 'Europe/London' }}</li>
                        </ul>
                    </div>

                    <div style="background:#f9fcff;border:1px solid #dce9fb;border-radius:12px;padding:14px 14px 10px;margin-bottom:18px;">
                        <p style="margin:0 0 8px;font-weight:700;color:#173f7f;">What to prepare (2 minutes)</p>
                        <ul style="margin:0;padding-left:18px;color:#3f587a;">
                            <li>Your website/store URL (if available)</li>
                            <li>Primary goal: qualified leads, sales growth, or better operations</li>
                            <li>Biggest challenge in one line (e.g. low conversions, slow site, weak SEO)</li>
                            <li>Expected budget range and timeline</li>
                        </ul>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        <a href="{{ route('meeting.manage', ['token' => $lead->meeting_token]) }}" class="thm-btn thm-btn-two" style="border:none;">
                            <span class="icon-right"></span> Manage Booking
                        </a>
                        <a href="{{ route('meeting.cancel', ['token' => $lead->meeting_token]) }}" class="thm-btn meeting-ghost-btn js-confirm-meeting-cancel">
                            <span class="icon-right"></span> Cancel Meeting
                        </a>
                    </div>

                    <p style="margin:16px 0 0;font-size:14px;color:#5f7390;">
                        Need help? Email us at <a href="mailto:{{ config('contact.inbox_email') }}">{{ config('contact.inbox_email') }}</a>
                    </p>
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
        var cancelLinks = document.querySelectorAll('.js-confirm-meeting-cancel');
        var cancelModal = document.getElementById('meetingCancelModal');
        var cancelNoBtn = document.getElementById('meetingCancelNoBtn');
        var cancelYesBtn = document.getElementById('meetingCancelYesBtn');
        var pendingHref = '';

        if (!cancelLinks.length || !cancelModal) {
            return;
        }

        function closeCancelModal() {
            cancelModal.style.display = 'none';
            pendingHref = '';
        }

        function openCancelModal(href) {
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

        cancelModal.addEventListener('click', function (event) {
            if (event.target === cancelModal) {
                closeCancelModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCancelModal();
            }
        });
    })();
</script>

@include('layouts.footer')
