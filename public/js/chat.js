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

// Initialize the chat after the DOM is fully loaded
window.addEventListener('DOMContentLoaded', initChat);
