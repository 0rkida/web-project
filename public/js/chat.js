function initChat() {
    const chatInput = document.querySelector('main footer textarea');
    const chatMessages = document.querySelector('#chat');
    const sendButton = document.querySelector('main footer a');
    const userList = document.querySelector('aside ul'); // The user list container
    const searchInput = document.querySelector('aside header input'); // The search input field

    // Function to send messages
    const sendMessage = () => {
        const message = chatInput.value.trim();
        if (message) {
            const messageData = {
                sender: ", Me",
                content: message,
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            };

            displayMessage(messageData, "me");
            chatInput.value = ''; // Clear input field
        }
    };

    sendButton.addEventListener('click', () => {
        const message = chatInput.value.trim();
        const receiverId = document.querySelector('main header').dataset.receiverId; // Add a data attribute for receiver_id

        if (message && receiverId) {
            fetch('/Message/postMessage', {
                method: 'POST',
                body: JSON.stringify({ message: message, receiver_id: receiverId }),
                headers: { 'Content-Type': 'application/json' },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.message);
                        displayMessage({ sender: "Me", content: message, timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }, "me");
                        chatInput.value = ''; // Clear the input
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            console.error('Message content or receiver ID is missing.');
        }
    });


    // Allow sending message by pressing Enter
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
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

    // Function to fetch user list and update UI
    // const getUserList = () => {
    //     fetch('/message')
    //         .then((response) => response.text())
    //         .then((data) => {
    //             if (data && userList) {
    //                 userList.innerHTML = data;
    //             }
    //         })
    //         //.catch((error) => console.error('Error fetching user list:', error));
    // };

    // Search functionality
    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.trim();

        if (searchTerm) {
            fetch('/Message/searchUsers', {
                method: 'POST',
                body: JSON.stringify({ searchTerm: searchTerm }),
                headers: { 'Content-Type': 'application/json' },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate user list with search results
                        userList.innerHTML = '';
                        data.users.forEach(user => {
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                <img src="${user.avatar}" alt="">
                                <div>
                                    <h2>${user.username}</h2>
                                    <h3><span class="status ${user.status}"></span> ${user.status}</h3>
                                </div>
                            `;
                            userList.appendChild(listItem);
                        });
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            // Clear search and reload the full user list
            getUserList();
        }
    });
    // Initialize chat and user list
    getUserList();
    setInterval(getUserList, 1000);
}
let currentUserId = null;
let users = [];

document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch_users.php')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Log the fetched data
            users = data;
            displayUsers(users);
        })
        .catch(error => console.error('Error fetching user data:', error));

    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredUsers = users.filter(user => user.full_name.toLowerCase().includes(searchTerm));
        displayUsers(filteredUsers);
    });
});

function displayUsers(users) {
    const userList = document.getElementById('user-list');
    userList.innerHTML = '';
    users.forEach(user => {
        const userItem = document.createElement('li');
        userItem.innerHTML = `
            <img src="${user.profile_picture}" alt="Profile Picture">
            <div>
                <h2>${user.full_name}</h2>
                <h3>
                    <span class="status ${new Date(user.last_online) > new Date(Date.now() - 5 * 60 * 1000) ? 'green' : 'orange'}"></span>
                    ${new Date(user.last_online) > new Date(Date.now() - 5 * 60 * 1000) ? 'online' : 'offline'}
                </h3>
            </div>
        `;
        userItem.addEventListener('click', () => {
            currentUserId = user.user_id;
            const chatHeaderImg = document.querySelector('header#chat-header img');
            chatHeaderImg.src = user.profile_picture;
            chatHeaderImg.style.display = 'block';
            document.querySelector('header#chat-header h2').textContent = `Chat with ${user.full_name}`;
            document.querySelector('header#chat-header h3').textContent = `Status: ${new Date(user.last_online) > new Date(Date.now() - 5 * 60 * 1000) ? 'online' : 'offline'}`;
            document.querySelector('header#chat-header .menu-dropdown a').href = `report_block.html?action=report&user=${user.user_id}`;
            loadChatMessages(user.user_id);
        });
        userList.appendChild(userItem);
    });
}

function loadChatMessages(userId) {
    fetch(`fetch_messages.php?user_id=${userId}`)
        .then(response => response.json())
        .then(messages => {
            const chat = document.getElementById('chat');
            chat.innerHTML = '';
            messages.forEach(message => {
                const messageItem = document.createElement('li');
                messageItem.className = message.sender === 'me' ? 'me' : 'you';
                messageItem.innerHTML = `
                    <div class="entete">
                        <h2>${message.sender}</h2>
                        <h3>${message.timestamp}</h3>
                    </div>
                    <div class="message">
                        ${message.text}
                    </div>
                `;
                chat.appendChild(messageItem);
            });
        })
        .catch(error => console.error('Error fetching chat messages:', error));
}

document.getElementById('send-button').addEventListener('click', function(event) {
    event.preventDefault();
    sendMessage();
});

document.getElementById('message-input').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});

function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const messageText = messageInput.value.trim();
    if (messageText === '' || currentUserId === null) return;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Add the message to the chat
                const chat = document.getElementById('chat');
                const messageItem = document.createElement('li');
                messageItem.className = 'me';
                messageItem.innerHTML = `
                    <div class="entete">
                        <h2>me</h2>
                        <h3>${new Date().toLocaleString()}</h3>
                    </div>
                    <div class="message">
                        ${messageText}
                    </div>
                `;
                chat.appendChild(messageItem);
                messageInput.value = '';
            } else {
                console.error('Error sending message:', response.error);
            }
        }
    };
    xhr.send(JSON.stringify({
        receiver_id: currentUserId,
        message: messageText
    }));
}

// Initialize the chat after the DOM is fully loaded
window.addEventListener('DOMContentLoaded', initChat);
