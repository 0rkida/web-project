function initChat() {
    const chatInput = document.querySelector('#chat-input');
    const chatMessages = document.querySelector('#chat-messages');
    const sendButton = document.querySelector('#send-button');

    sendButton.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if (message) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('chat-message');
            messageElement.textContent = message;
            chatMessages.appendChild(messageElement);
            chatInput.value = '';
        }
    });

    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendButton.click();
        }
    });
}

window.addEventListener('DOMContentLoaded', initChat);