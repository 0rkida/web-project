// Get modal elements
const reportModal = document.getElementById("report-modal");
const blockModal = document.getElementById("block-modal");

// Get button elements
const reportBtn = document.querySelector(".report-btn");
const blockBtn = document.querySelector(".block-btn");

// Get close button elements
const closeReport = document.getElementById("close-report");
const closeBlock = document.getElementById("close-block");

// Get the reason select and other textareas
const reportReason = document.getElementById("report-reason");
const blockReason = document.getElementById("block-reason");
const reportOther = document.getElementById("report-other");
const blockOther = document.getElementById("block-other");

// Get form elements
const reportForm = document.getElementById("report-form");
const blockForm = document.getElementById("block-form");

// Show/hide textarea based on selection
reportReason.addEventListener("change", function() {
    if (this.value === "other") {
        reportOther.style.display = "block";
    } else {
        reportOther.style.display = "none";
    }
});

blockReason.addEventListener("change", function() {
    if (this.value === "other") {
        blockOther.style.display = "block";
    } else {
        blockOther.style.display = "none";
    }
});

// Open report modal
reportBtn.addEventListener("click", () => {
    reportModal.style.display = "block";
});

// Open block modal
blockBtn.addEventListener("click", () => {
    blockModal.style.display = "block";
});

// Close report modal
closeReport.addEventListener("click", () => {
    reportModal.style.display = "none";
});

// Close block modal
closeBlock.addEventListener("click", () => {
    blockModal.style.display = "none";
});

// Close modals if clicked outside of them
window.addEventListener("click", (event) => {
    if (event.target === reportModal) {
        reportModal.style.display = "none";
    }
    if (event.target === blockModal) {
        blockModal.style.display = "none";
    }
});

// Handle report form submission
reportForm.addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting

    // Get the selected reason and other input (if any)
    const reason = reportReason.value;
    const otherReason = reportOther.value;

    // Validate form data
    if (!reason) {
        alert("Please select a reason for reporting.");
        return;
    }

    // If "Other" was selected, validate that the "Other Reason" is filled out
    if (reason === "other" && !otherReason) {
        alert("Please provide a reason for reporting.");
        return;
    }

    // Prepare data to be sent to the server
    const reportData = {
        reason: reason,
        otherReason: otherReason || "",
    };

    // Here, you would send the data to the server
    // For now, we'll just log it
    console.log("Report submitted:", reportData);

    // Close the modal after submission
    reportModal.style.display = "none";

    // Optionally, reset the form
    reportForm.reset();
});

// Handle block form submission
blockForm.addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting

    // Get the selected reason and other input (if any)
    const reason = blockReason.value;
    const otherReason = blockOther.value;

    // Validate form data
    if (!reason) {
        alert("Please select a reason for blocking.");
        return;
    }

    // If "Other" was selected, validate that the "Other Reason" is filled out
    if (reason === "other" && !otherReason) {
        alert("Please provide a reason for blocking.");
        return;
    }

    // Prepare data to be sent to the server
    const blockData = {
        reason: reason,
        otherReason: otherReason || "",
    };

    // Here, you would send the data to the server
    // For now, we'll just log it
    console.log("Block submitted:", blockData);

    // Close the modal after submission
    blockModal.style.display = "none";

    // Optionally, reset the form
    blockForm.reset();
});
