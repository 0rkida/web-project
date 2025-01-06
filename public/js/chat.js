function initChat() {
    const chatInput = document.querySelector('#chat-input');
    const chatMessages = document.querySelector('#chat-messages');
    const sendButton = document.querySelector('#send-button');

    sendButton.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if (message) {
            const messageElement = {
                sender: "You",
                content: message,
                timestamp: new Date().toLocaleDateString(),
                status: "unread"
            };

            displayMessage(messageData, "sent");

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

function displayMessage(messageData, type) {
    console.log('Displaying message:', messageData);  // Debugging line

    const chatMessages = document.querySelector('#chat-messages');
    const messageElement = document.createElement('div');
    messageElement.classList.add('chat-message', type);

    const senderElement = document.createElement('span');
    senderElement.classList.add('sender');
    senderElement.textContent = messageData.sender;

    const contentElement = document.createElement('span');
    contentElement.classList.add('content');
    contentElement.textContent = messageData.content;

    const timestampElement = document.createElement('span');
    timestampElement.classList.add('timestamp');
    timestampElement.textContent = messageData.timestamp;

    const statusElement = document.createElement('span');
    statusElement.classList.add('status');
    statusElement.textContent = messageData.status;

    messageElement.appendChild(senderElement);
    messageElement.appendChild(contentElement);
    messageElement.appendChild(timestampElement);
    messageElement.appendChild(statusElement);

    chatMessages.appendChild(messageElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}


window.addEventListener('DOMContentLoaded', initChat);