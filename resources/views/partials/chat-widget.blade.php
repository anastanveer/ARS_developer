@php
    $chatBrandName = config('company.legal_name', 'ARSDeveloper');
    $chatPhoneDigits = preg_replace('/\D+/', '', (string) config('company.phone', '+447478034328'));
    $chatLogo = asset('assets/images/resources/uk.png');
@endphp

<div
    class="site-chat"
    data-site-chat
    data-bootstrap-url="{{ route('chat.bootstrap') }}"
    data-profile-url="{{ route('chat.profile') }}"
    data-message-url="{{ route('chat.message') }}"
    data-conversation-url="{{ url('/chat/conversation') }}"
    data-whatsapp-url="https://wa.me/{{ $chatPhoneDigits }}"
>
    <button type="button" class="site-chat__toggle" data-chat-toggle aria-expanded="false" aria-controls="site-chat-panel">
        <span class="site-chat__toggle-icon"><i class="fas fa-comments"></i></span>
        <span class="site-chat__toggle-label">Live Chat</span>
    </button>

    <div class="site-chat__panel" id="site-chat-panel" hidden>
        <div class="site-chat__head">
            <div class="site-chat__brand">
                <button type="button" class="site-chat__back" data-chat-back aria-label="Back to options" hidden>
                    <i class="fas fa-arrow-left"></i>
                </button>
                <img src="{{ $chatLogo }}" alt="{{ $chatBrandName }} logo" class="site-chat__brand-logo">
                <div>
                    <strong>{{ $chatBrandName }}</strong>
                    <span>Quick support</span>
                </div>
            </div>
            <button type="button" class="site-chat__close" data-chat-close aria-label="Close chat">&times;</button>
        </div>

        <div class="site-chat__body">
            <div class="site-chat__messages" data-chat-messages></div>

            <div class="site-chat__capture" data-chat-capture>
                <div class="site-chat__prechat" data-chat-prechat hidden>
                    <div class="site-chat__prechat-copy">
                        <strong>Tell us a bit about yourself so we can make this personal.</strong>
                    </div>
                    <div class="site-chat__fields">
                        <input type="text" placeholder="Full name" data-chat-name>
                        <input type="email" placeholder="Business email address" data-chat-email>
                    </div>
                    <label class="site-chat__check">
                        <input type="checkbox" data-chat-newsletter>
                        <span>Sign up for our newsletter</span>
                    </label>
                    <button type="button" class="site-chat__profile-submit" data-chat-profile-submit>Send</button>
                </div>

                <div class="site-chat__start" data-chat-start>
                    <div class="site-chat__start-copy">
                        <strong>How can we help?</strong>
                        <span>Pick a quick topic to start faster.</span>
                    </div>
                    <button type="button" class="site-chat__choice" data-chat-prompt="I need help with a website or software development project.">
                        <span class="site-chat__choice-icon"><i class="fas fa-laptop-code"></i></span>
                        <span class="site-chat__choice-content">
                            <span class="site-chat__choice-title">Website development</span>
                            <span class="site-chat__choice-text">Custom builds, portals, ecommerce, and delivery planning.</span>
                        </span>
                        <span class="site-chat__choice-arrow"><i class="fas fa-arrow-right"></i></span>
                    </button>
                    <button type="button" class="site-chat__choice" data-chat-prompt="I want help with SEO, speed, or lead generation improvements.">
                        <span class="site-chat__choice-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="site-chat__choice-content">
                            <span class="site-chat__choice-title">SEO support</span>
                            <span class="site-chat__choice-text">Visibility, traffic, content direction, and conversion help.</span>
                        </span>
                        <span class="site-chat__choice-arrow"><i class="fas fa-arrow-right"></i></span>
                    </button>
                    <button type="button" class="site-chat__choice" data-chat-prompt="I want pricing, timeline, or project scope details.">
                        <span class="site-chat__choice-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="site-chat__choice-content">
                            <span class="site-chat__choice-title">Pricing and scope</span>
                            <span class="site-chat__choice-text">Budget range, timeline, and next-step guidance.</span>
                        </span>
                        <span class="site-chat__choice-arrow"><i class="fas fa-arrow-right"></i></span>
                    </button>
                    <button type="button" class="site-chat__choice site-chat__choice--primary" data-chat-choice="chat">
                        <span class="site-chat__choice-icon"><i class="fas fa-paper-plane"></i></span>
                        <span class="site-chat__choice-content">
                            <span class="site-chat__choice-title">Chat with us</span>
                            <span class="site-chat__choice-text">Start a direct conversation now.</span>
                        </span>
                        <span class="site-chat__choice-arrow"><i class="fas fa-arrow-right"></i></span>
                    </button>
                    <div class="site-chat__nav" data-chat-nav>
                        <button type="button" class="site-chat__nav-item is-active" data-chat-nav-home aria-label="Home"><i class="fas fa-home"></i></button>
                        <button type="button" class="site-chat__nav-item" data-chat-choice="chat" aria-label="Chat"><i class="far fa-comment-alt"></i></button>
                        <a href="https://wa.me/{{ $chatPhoneDigits }}" class="site-chat__nav-item" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="site-chat__capture-flow" data-chat-flow hidden>
                    <div class="site-chat__intro-bubble" data-chat-intro>Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.</div>
                </div>
            </div>

            <div class="site-chat__composer" data-chat-composer hidden>
                <div class="site-chat__preview" data-chat-preview hidden></div>
                <textarea rows="1" placeholder="Write your message..." data-chat-message></textarea>
                <div class="site-chat__actions">
                    <div class="site-chat__actions-left"></div>
                    <div class="site-chat__actions-right">
                        <button type="button" class="site-chat__tool site-chat__tool--emoji" data-chat-emoji-toggle aria-label="Add emoji">😊</button>
                        <label class="site-chat__tool" aria-label="Upload image">
                            <input type="file" accept="image/png,image/jpeg,image/webp,image/gif" hidden data-chat-file>
                            <i class="fas fa-paperclip"></i>
                        </label>
                        <button type="button" class="site-chat__send" data-chat-send aria-label="Send message"><span class="site-chat__send-icon">➜</span></button>
                    </div>
                </div>
                <div class="site-chat__emoji-picker" data-chat-emoji-picker hidden>
                    <button type="button">😀</button>
                    <button type="button">😊</button>
                    <button type="button">🙂</button>
                    <button type="button">😉</button>
                    <button type="button">😍</button>
                    <button type="button">🤝</button>
                    <button type="button">👍</button>
                    <button type="button">🙌</button>
                    <button type="button">👏</button>
                    <button type="button">👋</button>
                    <button type="button">🙏</button>
                    <button type="button">✅</button>
                    <button type="button">⭐</button>
                    <button type="button">📩</button>
                    <button type="button">💬</button>
                    <button type="button">📞</button>
                    <button type="button">📅</button>
                    <button type="button">📈</button>
                    <button type="button">🚀</button>
                    <button type="button">🔥</button>
                    <button type="button">🎯</button>
                    <button type="button">💡</button>
                    <button type="button">❤️</button>
                    <button type="button">🎉</button>
                </div>
                <div class="site-chat__footer">
                    <div class="site-chat__powered">
                        <img src="{{ $chatLogo }}" alt="{{ $chatBrandName }} logo" class="site-chat__powered-logo">
                        <span>Powered by {{ $chatBrandName }}</span>
                    </div>
                    <span class="site-chat__limit">Images only, up to 4MB</span>
                </div>
                <p class="site-chat__help" data-chat-help></p>
            </div>
        </div>
    </div>
</div>
