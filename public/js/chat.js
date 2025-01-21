function initChat() {
    const chatInput = document.querySelector('main footer textarea');
    const chatMessages = document.querySelector('#chat');
    const sendButton = document.querySelector('main footer a');
    //const chatBox = document.getElementById('chat_message_area');
    //const owenerProfileBio = document.getElementById('owner_profile_bio');
    const menuButton = document.querySelector('.menu-button');
    const menu = document.querySelector('.menu');
    let unique_id;
    let inter;

    // Function to send messages
    sendButton.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if (message) {
            const messageData = {
                sender: "Me",
                content: message,
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            };

            displayMessage(messageData, "me");

            // Send the message to the server or save it
            sendMessageToServer(message);
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

    // Function to display messages dynamically in the chat UI
    function displayMessage(messageData, type) {
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

    // Function to send the message to the server
    function sendMessageToServer(message) {
        $.post('Message/send', { message: message }, function (data) {
            //Handle server response (e.g., confirmation, error handling)
            console.log('Message sent to the server:', data);
        });
    }

    // Fetch user list and details
    function getUserList() {
        $.ajax({
            url: 'Message/allUser',
            type: 'get',
            success: function (data) {
                if (data != "") {
                    document.getElementById('user_list').innerHTML = data;
                }
            }
        });
    }

    // Initialize chat and user list
    getUserList();
    setInterval(getUserList, 1000);
}

// Initialize the chat after the DOM is fully loaded
window.addEventListener('DOMContentLoaded', initChat);
