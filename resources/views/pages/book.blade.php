@php
    $page_title = 'Book a Call';
    $seoOverride = [
        'title' => 'Book a Free Discovery Call | ARS Developer UK',
        'description' => 'Book a free 30-minute planning call with ARS Developer. Discuss your website, CRM, ecommerce, or SEO project with direct founder access — no commitment required.',
        'keywords' => 'book a call uk software developer, free consultation uk web development, discovery call website developer, schedule meeting software agency uk',
    ];
    $meetingSlots = config('contact.meeting_slots', []);
    $meetingSlots = is_array($meetingSlots) ? $meetingSlots : [];
    $meetingSlots = array_values(array_filter(array_map(fn ($slot) => trim((string) $slot), $meetingSlots)));
    $timezoneOptions = config('contact.timezone_options', []);
    $timezoneOptions = is_array($timezoneOptions) ? $timezoneOptions : [];
    $timezoneOptions = array_values(array_filter(array_map(fn ($tz) => trim((string) $tz), $timezoneOptions)));
    if (!in_array('Europe/London', $timezoneOptions, true)) {
        $timezoneOptions[] = 'Europe/London';
    }
@endphp
@include('layouts.header')

<!-- Page Banner Start -->
<section class="page-header" style="background:linear-gradient(135deg,#0d1f38,#173153);padding:140px 0 56px;margin-top:0;">
    <div class="container">
        <div style="text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:100px;padding:6px 18px;margin-bottom:16px;">
                <i class="fas fa-calendar-alt" style="color:#38bdf8;font-size:12px;" aria-hidden="true"></i>
                <span style="font-size:12px;font-weight:700;color:#93c5fd;letter-spacing:.6px;text-transform:uppercase;font-family:Arial,sans-serif;">Free Discovery Call</span>
            </div>
            <h1 style="font-size:38px;font-weight:800;color:#fff;line-height:1.2;margin:0 0 14px;font-family:Arial,sans-serif;">Book a Free Planning Call</h1>
            <p style="font-size:16px;color:#94a3b8;margin:0 auto;max-width:520px;font-family:Arial,sans-serif;line-height:1.7;">30 minutes. No commitment. Direct conversation with the founder about your project goals, timeline, and budget.</p>
            <div style="display:flex;align-items:center;justify-content:center;gap:24px;margin-top:24px;flex-wrap:wrap;">
                @foreach(['No signup required','UK office hours','Instant confirmation email'] as $pt)
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;font-family:Arial,sans-serif;">
                    <i class="fas fa-check-circle" style="color:#22c55e;font-size:12px;" aria-hidden="true"></i> {{ $pt }}
                </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- Page Banner End -->

<!-- Meeting Scheduler Start -->
<section class="meeting-scheduler" id="book-meeting" data-availability-url="{{ route('meeting.availability') }}" style="padding-top:56px;">
    <div class="container">
        <div class="meeting-scheduler__inner">
            <div class="row">
                <div class="col-xl-5">
                    <div class="meeting-scheduler__content">
                        <div class="section-title-two text-left sec-title-animation animation-style2">
                            <div class="section-title-two__tagline-box">
                                <div class="section-title-two__tagline-icon-box">
                                    <div class="section-title-two__tagline-icon-1"></div>
                                    <div class="section-title-two__tagline-icon-2"></div>
                                </div>
                                <span class="section-title-two__tagline">Schedule a meeting</span>
                            </div>
                            <h2 class="section-title-two__title title-animation">Book a planning call for your
                                <span>next project step</span></h2>
                        </div>
                        <p class="meeting-scheduler__text">Choose a suitable time, share a few project details, and get an instant confirmation. The flow is quick, clear, and built to remove back-and-forth.</p>
                        <p class="meeting-scheduler__timezone"><span class="icon-check"></span> Slots follow UK office hours, and your selected timezone is saved for reminders and confirmation.</p>
                        <ul class="list-unstyled meeting-scheduler__points">
                            <li><span class="icon-check"></span> 30-minute discovery call focused on your goals</li>
                            <li><span class="icon-check"></span> Website, CRM, SEO, Shopify, Wix, branding, and growth support</li>
                            <li><span class="icon-check"></span> Instant confirmation email with simple reschedule or cancel links</li>
                        </ul>
                        <p class="meeting-scheduler__micro-note"><span class="icon-check"></span> No signup required. Confirmation appears on screen as soon as booking is complete.</p>

                        {{-- Founder trust card --}}
                        <div style="margin-top:32px;background:linear-gradient(135deg,#f0f6ff,#e8f2ff);border:1px solid #bfdbfe;border-radius:16px;padding:20px 22px;display:flex;align-items:center;gap:14px;">
                            <img src="{{ asset('assets/images/my/anas.png') }}" alt="Anas Tanveer" style="width:52px;height:52px;border-radius:50%;object-fit:cover;object-position:center top;border:2px solid #1d93ff;flex-shrink:0;">
                            <div>
                                <p style="margin:0 0 2px;font-size:14px;font-weight:700;color:#0f1e35;font-family:Arial,sans-serif;">Anas Tanveer</p>
                                <p style="margin:0 0 4px;font-size:12px;color:#475569;font-family:Arial,sans-serif;">Founder &amp; Lead Developer · ARS Developer Ltd</p>
                                <p style="margin:0;font-size:12px;color:#1d4ed8;font-family:Arial,sans-serif;font-weight:600;">You'll speak directly with me — no account managers.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="meeting-scheduler__form-box">
                        <form class="meeting-form-validated meeting-scheduler__form" data-multistep="true" action="{{ route('contact.submit') }}" method="post" novalidate="novalidate" data-ga4-form-name="meeting_scheduler">
                            @csrf
                            <input type="hidden" name="form_type" value="meeting">
                            <input type="hidden" name="subject" value="Meeting Booking Request">
                            <div class="meeting-scheduler__steps">
                                <button type="button" class="meeting-scheduler__step-tab is-active" data-step-nav="1"><span>1</span> Pick Slot</button>
                                <button type="button" class="meeting-scheduler__step-tab" data-step-nav="2"><span>2</span> Your Details</button>
                                <button type="button" class="meeting-scheduler__step-tab" data-step-nav="3"><span>3</span> Confirm</button>
                            </div>
                            <div class="meeting-scheduler__step-pane is-active" data-booking-step="1">
                                <div class="meeting-scheduler__fast-lane">
                                    <p class="meeting-scheduler__fast-title"><span class="icon-check"></span> Fast booking: usually under 45 seconds</p>
                                    <div class="meeting-scheduler__quick-dates" data-quick-dates></div>
                                    <p class="meeting-scheduler__slot-status" data-slot-status>Pick a date and we will auto-select the first available slot.</p>
                                    <p class="meeting-scheduler__slot-status"><strong>Selected timezone:</strong> <span data-timezone-label>Europe/London</span></p>
                                </div>
                                <div class="meeting-scheduler__calendar-panel">
                                    <button type="button" class="meeting-scheduler__calendar-toggle" data-calendar-toggle aria-expanded="false" aria-controls="meeting-calendar-popover">
                                        <span class="icon-calendar"></span>
                                        Open visual calendar
                                    </button>
                                    <div class="meeting-scheduler__calendar-popover" id="meeting-calendar-popover" data-calendar-popover hidden>
                                        <div class="meeting-calendar meeting-calendar--embedded">
                                            <div class="meeting-scheduler__calendar-popover-head">
                                                <h3>Choose a date visually</h3>
                                                <button type="button" class="meeting-scheduler__calendar-close" data-calendar-close aria-label="Close calendar">&times;</button>
                                            </div>
                                            <h3 class="seo-hidden-heading">Meeting calendar overview</h3>
                                            <div class="meeting-calendar__head">
                                                <button type="button" class="meeting-calendar__nav meeting-calendar__prev" aria-label="Previous month">&lsaquo;</button>
                                                <h4 class="meeting-calendar__month">Month Year</h4>
                                                <button type="button" class="meeting-calendar__nav meeting-calendar__next" aria-label="Next month">&rsaquo;</button>
                                            </div>
                                            <div class="meeting-calendar__weekdays">
                                                <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                                            </div>
                                            <div class="meeting-calendar__grid"></div>
                                            <div class="meeting-calendar__legend">
                                                <span><i class="is-available"></i> Available</span>
                                                <span><i class="is-booked"></i> Booked</span>
                                                <span><i class="is-selected"></i> Selected</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <input type="date" name="meeting_date" min="{{ now()->toDateString() }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <select name="meeting_slot" required>
                                                <option value="">Select Time Slot</option>
                                                @foreach($meetingSlots as $slot)
                                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="meeting-scheduler__calendar-actions">
                                            <a href="#" class="meeting-scheduler__calendar-link meeting-scheduler__calendar-link--google" target="_blank" rel="noopener" data-ga4-event="add_to_calendar" data-ga4-label="google_calendar">Add to Google Calendar</a>
                                            <a href="#" class="meeting-scheduler__calendar-link meeting-scheduler__calendar-link--ics" data-ga4-event="add_to_calendar" data-ga4-label="ics_download">Download .ics (iPhone/Outlook)</a>
                                        </div>
                                        <div class="meeting-scheduler__step-actions">
                                            <button type="button" class="meeting-scheduler__btn thm-btn thm-btn-two" data-step-next><span class="icon-right"></span> Continue to Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="meeting-scheduler__step-pane" data-booking-step="2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <input type="text" name="name" placeholder="Full Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <input type="email" name="email" placeholder="Business Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <input type="text" name="phone" placeholder="Phone / WhatsApp" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <select name="project_type" required>
                                                <option value="">Project Type</option>
                                                <option value="Business Website">Business Website</option>
                                                <option value="Ecommerce Website (Shopify/WooCommerce)">Ecommerce Website (Shopify/WooCommerce)</option>
                                                <option value="Wix Website">Wix Website</option>
                                                <option value="CRM / Custom Software">CRM / Custom Software</option>
                                                <option value="SEO / Growth Marketing">SEO / Growth Marketing</option>
                                                <option value="Graphic Design + Branding">Graphic Design + Branding</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <select name="meeting_timezone" required>
                                                @foreach($timezoneOptions as $tz)
                                                    <option value="{{ $tz }}" @selected($tz === 'Europe/London')>{{ $tz }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="meeting-scheduler__input">
                                            <select name="budget_range">
                                                <option value="">Budget Range (Optional)</option>
                                                <option value="GBP 1k - 3k">GBP 1k - 3k</option>
                                                <option value="GBP 3k - 6k">GBP 3k - 6k</option>
                                                <option value="GBP 6k - 12k">GBP 6k - 12k</option>
                                                <option value="GBP 12k+">GBP 12k+</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="meeting-scheduler__input meeting-scheduler__input--textarea">
                                            <textarea name="message" placeholder="Optional: short note about your project"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="meeting-scheduler__step-actions">
                                            <button type="button" class="meeting-scheduler__btn meeting-scheduler__btn--ghost" data-step-prev>Back</button>
                                            <button type="button" class="meeting-scheduler__btn thm-btn thm-btn-two" data-step-next><span class="icon-right"></span> Review Booking</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="meeting-scheduler__step-pane" data-booking-step="3">
                                <div class="meeting-scheduler__review">
                                    <h4>Confirm your booking details</h4>
                                    <div class="meeting-scheduler__review-grid">
                                        <div><span>Date</span><strong data-review="meeting_date">-</strong></div>
                                        <div><span>Time Slot</span><strong data-review="meeting_slot">-</strong></div>
                                        <div><span>Name</span><strong data-review="name">-</strong></div>
                                        <div><span>Email</span><strong data-review="email">-</strong></div>
                                        <div><span>Phone</span><strong data-review="phone">-</strong></div>
                                        <div><span>Project Type</span><strong data-review="project_type">-</strong></div>
                                        <div><span>Budget</span><strong data-review="budget_range">-</strong></div>
                                        <div><span>Message</span><strong data-review="message">-</strong></div>
                                    </div>
                                    <p class="meeting-scheduler__review-note">After booking, you will instantly get confirmation with reschedule/cancel links.</p>
                                </div>
                                <div class="meeting-scheduler__step-actions">
                                    <button type="button" class="meeting-scheduler__btn meeting-scheduler__btn--ghost" data-step-prev>Back</button>
                                    <button type="submit" class="meeting-scheduler__btn thm-btn thm-btn-two"><span class="icon-right"></span> Confirm Booking</button>
                                </div>
                            </div>
                        </form>
                        <div class="result"></div>
                        <div class="meeting-scheduler__next-box">
                            <h4>What happens next?</h4>
                            <ul class="list-unstyled">
                                <li><span class="icon-check"></span> Your slot is confirmed instantly on screen.</li>
                                <li><span class="icon-check"></span> Confirmation email arrives with manage/cancel links.</li>
                                <li><span class="icon-check"></span> We review your goals and come prepared with relevant UK web/IT recommendations.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Meeting Scheduler End -->

@include('layouts.footer')
