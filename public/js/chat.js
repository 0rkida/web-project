function initChat() {
    const chatInput = document.querySelector('main footer textarea');
    const chatMessages = document.querySelector('#chat');
    const sendButton = document.querySelector('main footer a');
    const chatBox = document.getElementById('chat_message_area');
    const owenerProfileBio = document.getElementById('owner_profile_bio');
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

    // Function to send the message to the server (optional)
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

    // Fetch and display individual user details
    function getUserDetails(uniq_id) {
        $.post('Message/getIndividual', { data: uniq_id }, function (data) {
            const res_data = JSON.parse(data);
            setUserDetails(res_data);
        });
    }

    // Set user details (profile bio, email, etc.)
    function setUserDetails(data) {
        const user_name = `${data[0]['user_fname']} ${data[0]['user_lname']}`;
        const avtar = `../upload/${data[0]['user_avtar']}`;
        $('#name_last_seen h6').html(user_name);
        $('#chat_profile_image').css('background-image', `url(${avtar})`);
        $('#new_message_avtar').css('background-image', `url(${avtar})`);
        $('#user_details_container_avtar').css('background-image', `url(${avtar})`);
        $('#details_of_user h5').html(user_name);
        $('#details_of_bio').html(data[0]['bio'] || "--Not Given--");
    }

    // Block/Unblock functionality
    function getBlockUserData() {
        $.post('Message/getBlockUserData', { uniq: unique_id }, function (data) {
            const jsonData = JSON.parse(data);
            if (jsonData.length > 0) {
                $('#messageText').attr('disabled', true);
                $('#messageText').attr('placeholder', 'This user is not receiving messages at this time.');
                $('#send_message').attr('disabled', true);
            } else {
                $('#messageText').removeAttr('disabled');
                $('#messageText').attr('placeholder', 'Start Typing...');
                $('#send_message').removeAttr('disabled');
            }
        });
    }

    // Update profile bio (edit bio)
    $('#edit_icon').click(function () {
        $('#main').addClass('blur');
        $('#update_container').show();
        $('#update_bio').focus();
    });

    // Event listeners for the user list and user profile
    $('#user_list').on('click', '.innerBox', function () {
        unique_id = $(this).find('#user_avtar').children('#hidden_id').val();
        getUserDetails(unique_id);
        getBlockUserData();
        setInterval(getBlockUserData, 1000);
    });

    // Show/Hide menu on hover
    menuButton.addEventListener('mouseover', () => {
        menu.classList.add('open');
    });
    menuButton.addEventListener('mouseout', () => {
        menu.classList.remove('open');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) {
            menu.classList.remove('open');
        }
    });

    // Initialize chat and user list
    getUserList();
    setInterval(getUserList, 1000);
}

// Initialize the chat after the DOM is fully loaded
window.addEventListener('DOMContentLoaded', initChat);
