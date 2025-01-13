function initChat() {
    const chatInput = document.querySelector('main footer textarea');
    const chatMessages = document.querySelector('#chat');
    const sendButton = document.querySelector('main footer a');

    // Event listener for the Send button
    sendButton.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if (message) {
            const messageData = {
                sender: "Me",
                content: message,
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            };

            displayMessage(messageData, "me");
            chatInput.value = ''; // Clear input field
        }
    });

    // Allow sending message by pressing Enter
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendButton.click();
        }
    });
}

// Function to display messages dynamically in the chat UI
function displayMessage(messageData, type) {
    const chatMessages = document.querySelector('#chat');

    const listItem = document.createElement('li');
    listItem.classList.add(type);

    const entete = document.createElement('div');
    entete.classList.add('entete');

    const sender = document.createElement('h2');
    sender.textContent = messageData.sender;

    const timestamp = document.createElement('h3');
    timestamp.textContent = messageData.timestamp;

    const statusIndicator = document.createElement('span');
    statusIndicator.classList.add('status', type === 'me' ? 'blue' : 'green');

    if (type === 'me') {
        entete.appendChild(timestamp);
        entete.appendChild(sender);
        entete.appendChild(statusIndicator);
    } else {
        entete.appendChild(statusIndicator);
        entete.appendChild(sender);
        entete.appendChild(timestamp);
    }

    const triangle = document.createElement('div');
    triangle.classList.add('triangle');

    const message = document.createElement('div');
    message.classList.add('message');
    message.textContent = messageData.content;

    listItem.appendChild(entete);
    listItem.appendChild(triangle);
    listItem.appendChild(message);

    chatMessages.appendChild(listItem);

    // Auto-scroll to the latest message
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.querySelector('.menu-button');
    const menu = document.querySelector('.menu');

    // Show the menu when the button is hovered
    menuButton.addEventListener('mouseover', () => {
        menu.classList.add('open'); // Show the menu
    });

    // Hide the menu when the mouse leaves the menu button area
    menuButton.addEventListener('mouseout', () => {
        menu.classList.remove('open'); // Hide the menu
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) { // If the click is outside the menu
            menu.classList.remove('open'); // Hide the menu
        }
    });
});


window.addEventListener('DOMContentLoaded', initChat);
