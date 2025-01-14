document.addEventListener('DOMContentLoaded', function() {
    // Simulate fetching report data (this would be fetched from the server in a real-world scenario)
    const reportsData = [
        {
            reportedUser: 'JohnDoe123',
            reason: 'Inappropriate behavior',
            reportedBy: 'JaneDoe456',
            actions: ['Warn', 'Block', 'Delete']
        },
        {
            reportedUser: 'SamSmith789',
            reason: 'Harassment',
            reportedBy: 'AliceBrown321',
            actions: ['Warn', 'Block', 'Delete']
        },
        // Add more report objects as needed
    ];

    // Function to populate the reports table
    function populateReportsTable(data) {
        const tableBody = document.querySelector('#reports-table tbody');
        tableBody.innerHTML = ''; // Clear any existing rows

        data.forEach(report => {
            const row = document.createElement('tr');

            // Create table cells for each report property
            const reportedUserCell = document.createElement('td');
            reportedUserCell.textContent = report.reportedUser;
            row.appendChild(reportedUserCell);

            const reasonCell = document.createElement('td');
            reasonCell.textContent = report.reason;
            row.appendChild(reasonCell);

            const reportedByCell = document.createElement('td');
            reportedByCell.textContent = report.reportedBy;
            row.appendChild(reportedByCell);

            const actionsCell = document.createElement('td');
            report.actions.forEach(action => {
                const actionButton = document.createElement('button');
                actionButton.textContent = action;
                actionButton.classList.add('action-btn');
                actionButton.addEventListener('click', function() {
                    handleAction(action, report.reportedUser);
                });
                actionsCell.appendChild(actionButton);
            });
            row.appendChild(actionsCell);

            // Append the row to the table body
            tableBody.appendChild(row);
        });
    }

    // Function to handle actions like 'Warn', 'Block', or 'Delete'
    function handleAction(action, reportedUser) {
        if (action === 'Warn') {
            alert(`Warning sent to ${reportedUser}`);
        } else if (action === 'Block') {
            alert(`${reportedUser} has been blocked`);
        } else if (action === 'Delete') {
            alert(`${reportedUser} has been deleted`);
        }
    }

    // Call the function to populate the table with data
    populateReportsTable(reportsData);
});

let body = document.querySelector("body"),
    modeToggle = body.querySelector(".mode-toggle"),
    sidebar = body.querySelector("nav"),
    sidebarToggle = body.querySelector(".sidebar-toggle"),
    content = body.querySelector(".content");

let getMode = localStorage.getItem("mode");
if (getMode && getMode === "dark") {
    body.classList.toggle("dark");
}

let getStatus = localStorage.getItem("status");
if (getStatus && getStatus === "close") {
    sidebar.classList.toggle("close");
    content.classList.toggle("table-shifted"); // Adjust table position
}

modeToggle.addEventListener("click", () => {
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
        localStorage.setItem("mode", "dark");
    } else {
        localStorage.setItem("mode", "light");
    }
});

sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    content.classList.toggle("table-shifted"); // Adjust table position

    if (sidebar.classList.contains("close")) {
        localStorage.setItem("status", "close");
    } else {
        localStorage.setItem("status", "open");
    }
});
