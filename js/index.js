function validatePassword(password) {
    
    var lengthValid = password.length >= 8 && password.length <= 20;
    var lowercaseValid = /[a-z]/.test(password);
    var uppercaseValid = /[A-Z]/.test(password);
    var symbolValid = /[@#$%^&]/.test(password);
    var numberValid = /\d/.test(password);

    return lengthValid && lowercaseValid && uppercaseValid && symbolValid && numberValid;
}

function checkEmailConstraint(email) {

    var allowedDomainsRegex = /@(gmail\.com|outlook\.com)$/i;
    return allowedDomainsRegex.test(email);
}

function updatePasswordMatchStatus() {
    console.log("Updating password match status...");
    var password1 = $('#password1').val();
    var password2 = $('#password2').val();
    if (password1 !== password2 && password1 !== '' && password2 !== '') {
        $('#passwordError').text('Passwords do not match');
        $('#password1Tick').css('visibility', 'visible');
        $('#password2Tick').css('visibility', 'visible');
    } else {
        $('#passwordError').text('');
        $('#password1Tick').css('visibility', 'visible');
        $('#password2Tick').css('visibility', 'visible');
    }
}



$(document).ready(function () {
   
    updatePasswordMatchStatus();

    
    $('#password1, #password2').keyup(function () {
        updatePasswordMatchStatus(); 
    });

    $('#register').submit(function (e) {
        e.preventDefault();
        updatePasswordMatchStatus(); 

        var password1 = $('#password1').val();
        var password2 = $('#password2').val();

       
        if (password1 !== password2 || (password1 !== '' && password2 === '')) {
            $('#passwordError').text('Passwords do not match');
            $('#password1Tick').css('visibility', 'hidden');
            $('#password2Tick').css('visibility', 'hidden');
            alert("Passwords do not match");
            return; 
        }

        if (
            $('#passwordError').text() === '' &&
            $('#password1').val() !== '' &&
            $('#password2').val() !== '' &&
            (validatePassword($('#password1').val()))
        ) {
            var email = $('#email').val();

           
            if (!checkEmailConstraint(email)) {
                $('#emailError').text('Invalid email domain');
                $('#emailTick').css('visibility', 'hidden');
            } else {
                
                $.ajax({
                    type: 'POST',
                    url: 'php/index.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response.trim().toLowerCase() === 'record inserted successfully') {
                            window.location.href = './login.html';
                        } else {
                           
                            alert(response); 
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed: ' + status + ', ' + error);
                    },
                });
            }
        }else{
            $("#errorMessage").text("Password minimum 8 contain");
        }
    });
});
