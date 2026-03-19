/**
 * Sayenko Design Chatbot — frontend widget
 *
 * Uses the Fetch API with streaming (ReadableStream) to display Claude's
 * response incrementally as Server-Sent Events arrive from admin-ajax.php.
 */
(function () {
	'use strict';

	// -----------------------------------------------------------------------
	// DOM refs
	// -----------------------------------------------------------------------

	var widget    = document.getElementById('sayenko-chatbot-widget');
	if (!widget) return;

	var toggle    = document.getElementById('sayenko-chatbot-toggle');
	var chatWin   = document.getElementById('sayenko-chatbot-window');
	var closeBtn  = document.getElementById('sayenko-chatbot-close');
	var msgList   = document.getElementById('sayenko-chatbot-messages');
	var input     = document.getElementById('sayenko-chatbot-input');
	var sendBtn   = document.getElementById('sayenko-chatbot-send');
	var iconChat  = document.getElementById('sayenko-chatbot-icon-chat');
	var iconX     = document.getElementById('sayenko-chatbot-icon-x');

	// -----------------------------------------------------------------------
	// State
	// -----------------------------------------------------------------------

	var isOpen    = false;
	var isLoading = false;

	/**
	 * Conversation history for multi-turn context.
	 * Each entry: { role: 'user'|'assistant', content: string }
	 * We keep the full history; Claude Opus 4.6 has a 200K token context window.
	 * @type {{ role: string, content: string }[]}
	 */
	var history = [];

	// -----------------------------------------------------------------------
	// Open / close
	// -----------------------------------------------------------------------

	function openChat() {
		isOpen = true;
		chatWin.classList.remove('sayenko-chatbot-hidden');
		chatWin.setAttribute('aria-hidden', 'false');
		toggle.setAttribute('aria-expanded', 'true');
		iconChat.style.display = 'none';
		iconX.style.display    = '';
		scrollToBottom();
		input.focus();
	}

	function closeChat() {
		isOpen = false;
		chatWin.classList.add('sayenko-chatbot-hidden');
		chatWin.setAttribute('aria-hidden', 'true');
		toggle.setAttribute('aria-expanded', 'false');
		iconChat.style.display = '';
		iconX.style.display    = 'none';
	}

	toggle.addEventListener('click', function () {
		isOpen ? closeChat() : openChat();
	});

	closeBtn.addEventListener('click', closeChat);

	// Close on Escape key.
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && isOpen) closeChat();
	});

	// -----------------------------------------------------------------------
	// Message rendering helpers
	// -----------------------------------------------------------------------

	function scrollToBottom() {
		msgList.scrollTop = msgList.scrollHeight;
	}

	/**
	 * Append a new message bubble and return the element.
	 *
	 * @param {string} text
	 * @param {'user'|'assistant'|'error'} role
	 * @returns {HTMLElement}
	 */
	function appendMessage(text, role) {
		var el = document.createElement('div');
		el.className = 'sayenko-chatbot-message sayenko-chatbot-message--' + role;
		el.textContent = text;
		msgList.appendChild(el);
		scrollToBottom();
		return el;
	}

	function addTypingIndicator() {
		var el = document.createElement('div');
		el.id = 'sayenko-chatbot-typing';
		el.className = 'sayenko-chatbot-typing';
		el.setAttribute('aria-label', 'Assistant is typing');
		el.innerHTML = '<span></span><span></span><span></span>';
		msgList.appendChild(el);
		scrollToBottom();
		return el;
	}

	function removeTypingIndicator() {
		var el = document.getElementById('sayenko-chatbot-typing');
		if (el) el.parentNode.removeChild(el);
	}

	// -----------------------------------------------------------------------
	// Input helpers
	// -----------------------------------------------------------------------

	function setLoading(loading) {
		isLoading         = loading;
		sendBtn.disabled  = loading;
		input.disabled    = loading;
	}

	// Auto-grow textarea up to 5 lines.
	input.addEventListener('input', function () {
		this.style.height = 'auto';
		this.style.height = Math.min(this.scrollHeight, 120) + 'px';
	});

	// Send on Enter (Shift+Enter = newline).
	input.addEventListener('keydown', function (e) {
		if (e.key === 'Enter' && !e.shiftKey) {
			e.preventDefault();
			sendMessage();
		}
	});

	sendBtn.addEventListener('click', sendMessage);

	// -----------------------------------------------------------------------
	// Core send logic — streaming via fetch + ReadableStream
	// -----------------------------------------------------------------------

	async function sendMessage() {
		var text = input.value.trim();
		if (!text || isLoading) return;

		// Clear input immediately.
		input.value = '';
		input.style.height = 'auto';
		setLoading(true);

		// Render user bubble and record in history.
		appendMessage(text, 'user');
		history.push({ role: 'user', content: text });

		// Show typing indicator while waiting for the first token.
		addTypingIndicator();

		/** @type {HTMLElement|null} */
		var assistantEl  = null;
		var fullResponse = '';

		try {
			var formData = new FormData();
			formData.append('action', 'sayenko_chat');
			formData.append('nonce',   sayenkoChatbot.nonce);
			formData.append('message', text);

			// Send all history EXCEPT the message we just added (sent as `message`).
			var prevHistory = history.slice(0, -1);
			prevHistory.forEach(function (msg, i) {
				formData.append('history[' + i + '][role]',    msg.role);
				formData.append('history[' + i + '][content]', msg.content);
			});

			var response = await fetch(sayenkoChatbot.ajaxUrl, {
				method: 'POST',
				body:   formData,
			});

			if (!response.ok) {
				throw new Error('HTTP ' + response.status);
			}

			var reader  = response.body.getReader();
			var decoder = new TextDecoder();
			var buffer  = '';

			// eslint-disable-next-line no-constant-condition
			while (true) {
				var readResult = await reader.read();
				if (readResult.done) break;

				buffer += decoder.decode(readResult.value, { stream: true });

				// Process every complete SSE line in the buffer.
				var newlinePos;
				while ((newlinePos = buffer.indexOf('\n')) !== -1) {
					var line = buffer.slice(0, newlinePos).trim();
					buffer   = buffer.slice(newlinePos + 1);

					if (!line.startsWith('data: ')) continue;

					var data = line.slice(6);
					if (data === '[DONE]') {
						reader.cancel();
						break;
					}

					var parsed;
					try {
						parsed = JSON.parse(data);
					} catch (e) {
						continue;
					}

					if (parsed.error) {
						removeTypingIndicator();
						assistantEl = null;
						appendMessage(parsed.error, 'error');
						setLoading(false);
						return;
					}

					if (parsed.text) {
						// First text token: swap typing indicator for real bubble.
						if (!assistantEl) {
							removeTypingIndicator();
							assistantEl = appendMessage('', 'assistant');
						}
						fullResponse += parsed.text;
						assistantEl.textContent = fullResponse;
						scrollToBottom();
					}
				}
			}

			// Guard: remove typing indicator if no text arrived.
			removeTypingIndicator();

			if (fullResponse) {
				history.push({ role: 'assistant', content: fullResponse });
			} else if (!assistantEl) {
				appendMessage("I'm sorry, I didn't receive a response. Please try again.", 'error');
			}

		} catch (err) {
			removeTypingIndicator();
			appendMessage('Something went wrong. Please try again.', 'error');
			// Remove the user message we speculatively added to history.
			history.pop();
		}

		setLoading(false);
		input.focus();
	}
}());
