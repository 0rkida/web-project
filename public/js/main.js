// Retrieve the error message from PHP (if set) and show it in the modal
var errorMessage = "Gabim! Email ose fjalëkalim i gabuar!";

// If there's an error message, show the modal and display the error
if (errorMessage) {

    document.getElementById('errorMessage').textContent = errorMessage;
    var modal = document.getElementById('errorModal');
    modal.style.display = "block";

    // Close the modal when the user clicks on <span> (x)
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}
