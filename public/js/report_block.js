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
