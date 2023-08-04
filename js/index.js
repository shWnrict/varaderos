$(document).ready(function () {
    // Show the age verification modal on page load
    $('#ageModal').modal('show');

    // Handle the "No, I'm not" button click
    $('#notOver18Btn').on('click', function () {
        alert("You must be 18 years or older to enter this site.");
        window.location.href = "index.html"; // Redirect to a different page for underage users
    });
});

