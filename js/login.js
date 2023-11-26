function redirectToRegister() {
    window.location.href = 'register.html';
}

$(document).ready(function () {
    $("#login").submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "php/login.php",
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log(response);

                if (response && response.status === 200) {
                   
                    console.log("Login successful");
                    console.log("User Email: " + response.email);
                    console.log("User Password: " + response.password);
                    window.location.href = './profile.html';

                } else if (response && response.status === 400) {
                    
                    console.error("Login failed: " + response.message);
                    $("#errorMessage").text("Incorrect username or password");
                    $("#password").val("");
                } else {
                    console.error("Unexpected response format");
                    $("#errorMessage").text("An unexpected error occurred. Please try again.");
                }
            },
            error: function () {
                console.error("Error in AJAX request");
                $("#errorMessage").text("An unexpected error occurred. Please try again.");
            }
        });
    });
});
