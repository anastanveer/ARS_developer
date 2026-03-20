(function () {
    var root = document.querySelector('[data-site-chat]');
    if (!root) {
        return;
    }

    var toggle = root.querySelector('[data-chat-toggle]');
    var closeBtn = root.querySelector('[data-chat-close]');
    var backBtn = root.querySelector('[data-chat-back]');
    var panel = root.querySelector('.site-chat__panel');
    var messagesBox = root.querySelector('[data-chat-messages]');
    var captureBox = root.querySelector('[data-chat-capture]');
    var prechatBox = root.querySelector('[data-chat-prechat]');
    var nameInput = root.querySelector('[data-chat-name]');
    var emailInput = root.querySelector('[data-chat-email]');
    var newsletterInput = root.querySelector('[data-chat-newsletter]');
    var profileSubmitBtn = root.querySelector('[data-chat-profile-submit]');
    var phoneInput = root.querySelector('[data-chat-phone]');
    var startBox = root.querySelector('[data-chat-start]');
    var flowBox = root.querySelector('[data-chat-flow]');
    var composerBox = root.querySelector('[data-chat-composer]');
    var introBubble = root.querySelector('[data-chat-intro]');
    var choiceButtons = root.querySelectorAll('[data-chat-choice]');
    var promptButtons = root.querySelectorAll('[data-chat-prompt]');
    var messageInput = root.querySelector('[data-chat-message]');
    var sendBtn = root.querySelector('[data-chat-send]');
    var helpText = root.querySelector('[data-chat-help]');
    var emojiToggle = root.querySelector('[data-chat-emoji-toggle]');
    var emojiPicker = root.querySelector('[data-chat-emoji-picker]');
    var fileInput = root.querySelector('[data-chat-file]');
    var previewBox = root.querySelector('[data-chat-preview]');

    var bootstrapUrl = root.getAttribute('data-bootstrap-url');
    var profileUrl = root.getAttribute('data-profile-url');
    var messageUrl = root.getAttribute('data-message-url');
    var conversationBaseUrl = root.getAttribute('data-conversation-url');
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = localStorage.getItem('ars_chat_token') || '';
    var pollTimer = null;
    var inactivityTimer = null;
    var selectedFile = null;
    var selectedMode = 'chat';
    var idleSessionMs = 60000;
    var manualStartView = false;
    var imageModal = null;
    var imageModalImg = null;
    var pendingSendAfterProfile = false;

    function setOpen(isOpen) {
        panel.hidden = !isOpen;
        root.classList.toggle('site-chat--open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    function ensureImageModal() {
        if (imageModal) {
            return;
        }

        imageModal = document.createElement('div');
        imageModal.className = 'site-chat__image-modal';
        imageModal.hidden = true;
        imageModal.innerHTML =
            '<div class="site-chat__image-backdrop" data-chat-image-close></div>' +
            '<div class="site-chat__image-dialog" role="dialog" aria-modal="true" aria-label="Image preview">' +
                '<button type="button" class="site-chat__image-close" data-chat-image-close aria-label="Close preview">&times;</button>' +
                '<img class="site-chat__image-full" alt="Chat image preview">' +
            '</div>';

        panel.appendChild(imageModal);
        imageModalImg = imageModal.querySelector('.site-chat__image-full');

        imageModal.addEventListener('click', function (event) {
            if (event.target.closest('[data-chat-image-close]')) {
                closeImageModal();
            }
        });
    }

    function openImageModal(src) {
        ensureImageModal();
        if (!imageModal || !imageModalImg) {
            return;
        }
        imageModalImg.setAttribute('src', src);
        imageModal.hidden = false;
        root.classList.add('site-chat--modal-open');
    }

    function closeImageModal() {
        if (!imageModal || imageModal.hidden) {
            return;
        }
        imageModal.hidden = true;
        root.classList.remove('site-chat--modal-open');
        if (imageModalImg) {
            imageModalImg.setAttribute('src', '');
        }
    }

    function stopPolling() {
        if (pollTimer) {
            window.clearInterval(pollTimer);
            pollTimer = null;
        }
        if (inactivityTimer) {
            window.clearTimeout(inactivityTimer);
            inactivityTimer = null;
        }
    }

    function showPrechatScreen() {
        manualStartView = false;
        selectedMode = 'chat';
        root.classList.remove('site-chat--whatsapp');
        stopPolling();
        if (captureBox) {
            captureBox.hidden = false;
        }
        if (prechatBox) {
            prechatBox.hidden = false;
        }
        if (startBox) {
            startBox.hidden = true;
        }
        if (messagesBox) {
            messagesBox.hidden = true;
        }
        if (flowBox) {
            flowBox.hidden = true;
        }
        if (composerBox) {
            composerBox.hidden = false;
        }
        if (backBtn) {
            backBtn.hidden = false;
        }
        if (emojiPicker) {
            emojiPicker.hidden = true;
        }
        helpText.textContent = 'Please confirm your details first.';
    }

    function clearDraftState() {
        selectedFile = null;
        if (messageInput) {
            messageInput.value = '';
            resizeComposer();
        }
        if (fileInput) {
            fileInput.value = '';
        }
        setPreview();
    }

    function resetConversationSession() {
        stopPolling();
        token = '';
        localStorage.removeItem('ars_chat_token');
        if (messagesBox) {
            messagesBox.innerHTML = '';
            messagesBox.hidden = true;
        }
        clearDraftState();
    }

    function showActiveConversation() {
        manualStartView = false;
        if (captureBox) {
            captureBox.hidden = true;
        }
        if (prechatBox) {
            prechatBox.hidden = true;
        }
        if (startBox) {
            startBox.hidden = true;
        }
        if (flowBox) {
            flowBox.hidden = true;
        }
        if (composerBox) {
            composerBox.hidden = false;
        }
        if (messagesBox) {
            messagesBox.hidden = false;
        }
        if (backBtn) {
            backBtn.hidden = false;
        }
    }

    function restartInactivityTimer() {
        if (inactivityTimer) {
            window.clearTimeout(inactivityTimer);
        }
        if (!token) {
            return;
        }
        inactivityTimer = window.setTimeout(function () {
            resetConversationSession();
            showStartScreen();
            helpText.textContent = 'Previous session closed due to inactivity. Start a new chat below.';
        }, idleSessionMs);
    }

    function resizeComposer() {
        if (!messageInput) {
            return;
        }
        var maxHeight = window.innerWidth <= 767 ? 96 : 104;
        var minHeight = window.innerWidth <= 767 ? 26 : 28;
        messageInput.style.height = 'auto';
        var nextHeight = Math.max(minHeight, Math.min(messageInput.scrollHeight, maxHeight));
        messageInput.style.height = nextHeight + 'px';
        messageInput.style.overflowY = messageInput.scrollHeight > maxHeight ? 'auto' : 'hidden';
    }

    function showStartScreen() {
        manualStartView = !!token;
        pendingSendAfterProfile = false;
        selectedMode = 'chat';
        root.classList.remove('site-chat--whatsapp');
        stopPolling();
        if (captureBox) {
            captureBox.hidden = false;
        }
        if (prechatBox) {
            prechatBox.hidden = true;
        }
        if (messagesBox) {
            messagesBox.hidden = true;
        }
        if (startBox) {
            startBox.hidden = false;
        }
        if (flowBox) {
            flowBox.hidden = true;
        }
        if (composerBox) {
            composerBox.hidden = true;
        }
        if (backBtn) {
            backBtn.hidden = true;
        }
        if (introBubble) {
            introBubble.textContent = 'Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.';
        }
        helpText.textContent = token ? 'Your previous chat is saved. Choose an option to continue.' : '';
        if (emojiPicker) {
            emojiPicker.hidden = true;
        }
    }

    function applyMode(mode) {
        selectedMode = 'chat';
        root.classList.remove('site-chat--whatsapp');

        if (messageInput) {
            messageInput.value = '';
            resizeComposer();
        }

        if (token) {
            showActiveConversation();
            helpText.textContent = 'Replies will continue here.';
            startPolling();
            restartInactivityTimer();
            return;
        }

        if (startBox) {
            startBox.hidden = true;
        }
        if (messagesBox) {
            messagesBox.hidden = true;
        }
        if (flowBox) {
            flowBox.hidden = true;
        }
        if (composerBox) {
            composerBox.hidden = false;
        }
        if (backBtn) {
            backBtn.hidden = false;
        }
        if (introBubble) {
            introBubble.textContent = 'Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.';
        }
        helpText.textContent = '';
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderMessages(items, conversation) {
        var html = (items || []).map(function (item) {
            var sender = item.sender_type || 'system';
            var hasAttachment = !!item.attachment_url;
            var visibleBody = item.body;
            if (hasAttachment && String(item.body || '').trim() === '[image]') {
                visibleBody = '';
            }
            var body = visibleBody ? '<div>' + escapeHtml(visibleBody).replace(/\n/g, '<br>') + '</div>' : '';
            var attachment = item.attachment_url
                ? '<a class="site-chat__attachment-link" href="' + item.attachment_url + '" target="_blank" rel="noopener noreferrer"><img class="site-chat__attachment" src="' + item.attachment_url + '" alt="Uploaded image"></a>'
                : '';
            return '<div class="site-chat__bubble site-chat__bubble--' + sender + '">' + body + attachment + '</div>';
        }).join('');

        if (conversation && conversation.admin_typing) {
            html += '<div class="site-chat__typing"><span></span><span></span><span></span></div>';
        }

        messagesBox.innerHTML = html;
        if (items && items.length) {
            messagesBox.hidden = manualStartView;
            if (!manualStartView) {
                messagesBox.scrollTop = messagesBox.scrollHeight;
            }
        }
        restartInactivityTimer();
    }

    function setConversation(conversation) {
        if (!conversation || !conversation.token) {
            return;
        }

        token = conversation.token;
        localStorage.setItem('ars_chat_token', token);
        selectedMode = conversation.preferred_channel || selectedMode;
        root.classList.toggle('site-chat--whatsapp', selectedMode === 'whatsapp');
        if (!manualStartView) {
            showActiveConversation();
        }
        helpText.textContent = conversation.preferred_channel === 'whatsapp'
            ? 'WhatsApp reply mode selected.'
            : 'Replies will continue here.';
        restartInactivityTimer();
    }

    function setPreview() {
        if (!selectedFile) {
            previewBox.hidden = true;
            previewBox.innerHTML = '';
            return;
        }

        previewBox.hidden = false;
        previewBox.innerHTML = '<span>' + escapeHtml(selectedFile.name) + '</span><button type="button" data-chat-remove-file>&times;</button>';
    }

    function bootstrap() {
        var url = bootstrapUrl + (token ? ('?token=' + encodeURIComponent(token)) : '');
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.conversation) {
                    setConversation(data.conversation);
                    renderMessages(data.messages || [], data.conversation || null);
                    showActiveConversation();
                } else if (messagesBox) {
                    messagesBox.innerHTML = '';
                    messagesBox.hidden = true;
                    showStartScreen();
                }
            })
            .catch(function () {});
    }

    function pollConversation() {
        if (!token) {
            return;
        }

        fetch(conversationBaseUrl + '/' + encodeURIComponent(token), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.conversation) {
                    setConversation(data.conversation);
                    renderMessages(data.messages || [], data.conversation || null);
                    showActiveConversation();
                } else if (messagesBox) {
                    messagesBox.innerHTML = '';
                    messagesBox.hidden = true;
                }
            })
            .catch(function () {});
    }

    function startPolling() {
        if (pollTimer) {
            window.clearInterval(pollTimer);
        }

        pollTimer = window.setInterval(pollConversation, 4000);
    }

    function sendMessage() {
        var message = messageInput.value.trim();
        if (!message && !selectedFile) {
            helpText.textContent = 'Please enter a message or upload an image.';
            return;
        }

        if (!token) {
            pendingSendAfterProfile = true;
            showPrechatScreen();
            if (nameInput) {
                nameInput.focus();
            }
            return;
        }

        var phoneValue = phoneInput ? phoneInput.value.trim() : '';
        var nameValue = nameInput ? nameInput.value.trim() : '';
        var emailValue = emailInput ? emailInput.value.trim() : '';

        if (selectedMode === 'whatsapp' && !phoneValue) {
            helpText.textContent = 'A WhatsApp number is required for WhatsApp replies.';
            if (phoneInput) { phoneInput.focus(); }
            return;
        }

        var payload = new FormData();
        payload.append('token', token);
        payload.append('name', nameValue);
        payload.append('email', emailValue);
        payload.append('phone', phoneValue);
        payload.append('message', message);
        payload.append('preferred_channel', selectedMode);
        payload.append('page_url', window.location.pathname);
        if (selectedFile) {
            payload.append('image', selectedFile);
        }

        sendBtn.disabled = true;
        helpText.textContent = 'Sending...';

        fetch(messageUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : ''
            },
            body: payload
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error(data.message || 'Could not send message.');
                    }
                    return data;
                });
            })
            .then(function (data) {
                setConversation(data.conversation);
                renderMessages(data.messages || [], data.conversation || null);
                messageInput.value = '';
                fileInput.value = '';
                selectedFile = null;
                setPreview();
                emojiPicker.hidden = true;
                helpText.textContent = 'Message sent. Our team will reply shortly.';
                resizeComposer();
                startPolling();
                restartInactivityTimer();
            })
            .catch(function (error) {
                helpText.textContent = error.message || 'Your message could not be sent.';
            })
            .finally(function () {
                sendBtn.disabled = false;
            });
    }

    function submitProfile() {
        if (!profileUrl || !nameInput || !emailInput || !profileSubmitBtn) {
            return;
        }

        var nameValue = nameInput.value.trim();
        var emailValue = emailInput.value.trim();

        if (!nameValue) {
            helpText.textContent = 'Please enter your full name.';
            nameInput.focus();
            return;
        }

        if (!emailValue) {
            helpText.textContent = 'Please enter your business email.';
            emailInput.focus();
            return;
        }

        var payload = new FormData();
        payload.append('name', nameValue);
        payload.append('email', emailValue);
        payload.append('newsletter_opt_in', newsletterInput && newsletterInput.checked ? '1' : '0');
        payload.append('page_url', window.location.pathname);

        profileSubmitBtn.disabled = true;
        helpText.textContent = 'Saving your details...';

        fetch(profileUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : ''
            },
            body: payload
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error(data.message || 'Could not save your details.');
                    }
                    return data;
                });
            })
            .then(function (data) {
                setConversation(data.conversation);
                renderMessages(data.messages || [], data.conversation || null);
                showActiveConversation();
                startPolling();
                restartInactivityTimer();
                if (pendingSendAfterProfile) {
                    pendingSendAfterProfile = false;
                    sendMessage();
                    return;
                }
                helpText.textContent = 'Your chat is ready. You can send your message now.';
                if (messageInput) {
                    messageInput.focus();
                }
            })
            .catch(function (error) {
                helpText.textContent = error.message || 'Your details could not be saved.';
            })
            .finally(function () {
                profileSubmitBtn.disabled = false;
            });
    }

    toggle.addEventListener('click', function () {
        setOpen(panel.hidden);
        if (!panel.hidden) {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }
    });

    closeBtn.addEventListener('click', function () {
        setOpen(false);
    });

    if (backBtn) {
        backBtn.addEventListener('click', function () {
            if (prechatBox && !prechatBox.hidden) {
                pendingSendAfterProfile = false;
            }
            showStartScreen();
        });
    }

    if (profileSubmitBtn) {
        profileSubmitBtn.addEventListener('click', submitProfile);
    }

    if (emailInput) {
        emailInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitProfile();
            }
        });
    }

    choiceButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            applyMode(button.getAttribute('data-chat-choice'));
        });
    });

    promptButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var prompt = button.getAttribute('data-chat-prompt') || '';
            applyMode('chat');
            if (messageInput) {
                messageInput.value = prompt;
                resizeComposer();
            }
            sendMessage();
        });
    });

    sendBtn.addEventListener('click', sendMessage);

    messageInput.addEventListener('input', function () {
        resizeComposer();
        restartInactivityTimer();
    });

    messageInput.addEventListener('keydown', function (event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
            sendMessage();
        }
    });

    if (emojiToggle && emojiPicker) {
        emojiToggle.addEventListener('click', function () {
            emojiPicker.hidden = !emojiPicker.hidden;
        });

        emojiPicker.addEventListener('click', function (event) {
            var button = event.target.closest('button');
            if (!button) {
                return;
            }
            messageInput.value += button.textContent;
            emojiPicker.hidden = true;
            resizeComposer();
            messageInput.focus();
        });

        document.addEventListener('click', function (event) {
            if (emojiPicker.hidden) {
                return;
            }
            if (event.target.closest('[data-chat-emoji-toggle]') || event.target.closest('[data-chat-emoji-picker]')) {
                return;
            }
            emojiPicker.hidden = true;
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            selectedFile = fileInput.files[0] || null;
            setPreview();
            restartInactivityTimer();
        });
    }

    if (previewBox) {
        previewBox.addEventListener('click', function (event) {
            if (!event.target.closest('[data-chat-remove-file]')) {
                return;
            }
            selectedFile = null;
            fileInput.value = '';
            setPreview();
        });
    }

    if (messagesBox) {
        messagesBox.addEventListener('click', function (event) {
            var attachmentLink = event.target.closest('.site-chat__attachment-link');
            if (!attachmentLink) {
                return;
            }
            event.preventDefault();
            openImageModal(attachmentLink.getAttribute('href'));
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });

    resizeComposer();
    showStartScreen();

    bootstrap();
    startPolling();
})();
