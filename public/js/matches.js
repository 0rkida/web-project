document.addEventListener("DOMContentLoaded", () => {
    const likedUsersContainer = document.getElementById("liked-users");
    const mutualMatchesContainer = document.getElementById("mutual-matches");

    // Retrieve data from localStorage
    const likedUsers = JSON.parse(localStorage.getItem("likedYou")) || [
        { name: "Stive", age: 25, image: "img/stive.jpg" },
        { name: "Bob", age: 23, image: "img/bob.jpg" },
        { name: "James", age: 21, image: "img/james.jpg" }
    ];
    const mutualMatches = JSON.parse(localStorage.getItem("mutualMatches")) || [];

    // Render users in a section
    function renderUsers(container, users, isLikedSection = false) {
        container.innerHTML = ""; // Clear container
        if (users.length === 0) {
            container.innerHTML = "<p>No users found.</p>";
            return;
        }

        users.forEach((user, index) => {
            const userCard = document.createElement("div");
            userCard.className = "profile-card";
            userCard.innerHTML = `
                <img src="${user.image}" alt="${user.name}">
                <h3>${user.name}</h3>
                <p>${user.age} years old</p>
                ${isLikedSection ? `
                    <button class="like-btn" data-index="${index}">Like</button>
                    <button class="dislike-btn" data-index="${index}">Dislike</button>
                ` : ""}
            `;

            if (isLikedSection) {
                const likeBtn = userCard.querySelector(".like-btn");
                const dislikeBtn = userCard.querySelector(".dislike-btn");

                likeBtn.addEventListener("click", () => likeUser(index));
                dislikeBtn.addEventListener("click", () => dislikeUser(index));
            }

            container.appendChild(userCard);
        });
    }

    // Move a user from "Liked" to "Matches" section
    function likeUser(index) {
        const user = likedUsers[index];
        likedUsers.splice(index, 1); // Remove from likedUsers
        mutualMatches.push(user); // Add to mutualMatches

        // Update localStorage
        localStorage.setItem("likedYou", JSON.stringify(likedUsers));
        localStorage.setItem("mutualMatches", JSON.stringify(mutualMatches));

        // Re-render both sections
        renderUsers(likedUsersContainer, likedUsers, true);
        renderUsers(mutualMatchesContainer, mutualMatches);
    }

    // Remove a user from the "Liked" section
    function dislikeUser(index) {
        likedUsers.splice(index, 1); // Remove from likedUsers

        // Update localStorage
        localStorage.setItem("likedYou", JSON.stringify(likedUsers));

        // Re-render the "Liked You" section
        renderUsers(likedUsersContainer, likedUsers, true);
    }

    // Initial rendering
    renderUsers(likedUsersContainer, likedUsers, true);
    renderUsers(mutualMatchesContainer, mutualMatches);
});
