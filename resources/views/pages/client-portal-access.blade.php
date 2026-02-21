@php
    $page_title = 'Client Portal Access';
    $seoOverride = [
        'title' => 'Client Portal Access - ARSDeveloper',
        'description' => 'Secure entry page for existing ARSDeveloper clients to access private project portals.',
        'keywords' => 'client portal access arsdeveloper',
        'robots' => 'noindex, nofollow',
        'canonical' => route('client.portal.access'),
        'type' => 'WebPage',
    ];
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Client <span>Portal Access</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Client Portal Access</li>
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
                    <h2 style="margin-bottom:10px;">Open Your Project Dashboard</h2>
                    <p style="margin-bottom:14px;">You can open your dashboard in 1 step. Paste the full portal link from email, or just the portal token.</p>
                    <div style="background:#f5f9ff;border:1px solid #d9e7fc;border-radius:12px;padding:14px 14px 10px;margin-bottom:18px;">
                        <p style="margin:0 0 8px;font-weight:700;color:#173f7f;">Where will I get portal token/link?</p>
                        <ul style="margin:0;padding-left:18px;color:#3f587a;">
                            <li>Check ARS emails like invoice or payment update.</li>
                            <li>You will see a link like: <code>https://yourdomain.com/client-portal/abc123...</code></li>
                            <li>If you paste the full link here, we will auto-detect token.</li>
                        </ul>
                    </div>

                    <form id="client-portal-access-form">
                        <label for="portal-token" style="display:block;font-weight:700;margin-bottom:8px;">Portal Link or Token</label>
                        <input id="portal-token" type="text" placeholder="Paste full portal link or token" required
                               style="width:100%;height:52px;padding:0 14px;border:1px solid #cddbf4;border-radius:10px;outline:none;">
                        <p id="portal-token-help" style="margin:8px 0 0;font-size:13px;color:#5f7390;">Example token format: <code>Q4x8...kP2n</code> (long secure code)</p>
                        <p id="portal-token-error" style="display:none;margin:8px 0 0;font-size:13px;color:#b42318;font-weight:600;">Please enter a valid portal link or token.</p>
                        <button type="submit" class="thm-btn thm-btn-two" style="margin-top:14px;border:none;">
                            <span class="icon-right"></span> Open Client Portal
                        </button>
                    </form>

                    <p style="margin-top:16px;font-size:14px;color:#5f7390;">If token/link is missing, contact us at <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> and we will resend your portal access.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        var form = document.getElementById('client-portal-access-form');
        var tokenInput = document.getElementById('portal-token');
        var errorNode = document.getElementById('portal-token-error');

        if (!form || !tokenInput) {
            return;
        }

        function extractToken(value) {
            var raw = String(value || '').trim();
            if (!raw) {
                return '';
            }

            if (raw.indexOf('http://') === 0 || raw.indexOf('https://') === 0) {
                try {
                    var url = new URL(raw);
                    var parts = url.pathname.split('/').filter(Boolean);
                    var idx = parts.indexOf('client-portal');
                    if (idx >= 0 && parts[idx + 1]) {
                        return parts[idx + 1].trim();
                    }
                } catch (e) {
                    return '';
                }
            }

            return raw;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (errorNode) {
                errorNode.style.display = 'none';
            }

            var token = extractToken(tokenInput.value);
            var isValid = /^[A-Za-z0-9_-]{20,}$/.test(token);
            if (!isValid) {
                if (errorNode) {
                    errorNode.style.display = 'block';
                }
                tokenInput.focus();
                return;
            }

            var cleaned = encodeURIComponent(token);
            window.location.href = '/client-portal/' + cleaned;
        });
    })();
</script>

@include('layouts.footer')
