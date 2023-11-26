function refresh() {
  $.ajax({
      type: "GET",
      url: "php/profile.php",
      success: function (response) {
          console.log(response);
          try {
              response = JSON.parse(response);
              if (response.status === 'success') {
                  // Update your HTML elements with the received data
                  // Example: $("#firstname").text(response.firstname);
                  // Add similar lines for other fields
              } else {
                  console.error("Error: " + response.message);
              }
          } catch (error) {
              console.error("Error parsing JSON: " + error);
          }
      },
      error: function (xhr, status, error) {
          console.error("AJAX request failed: " + status + ", " + error);
      },
  });
}
// For updateForm
$(document).ready(function () {
  console.log("JS REE");

  $("#updateForm").submit(function (event) {
    console.log("Came inside function");
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "php/profile1.php",
      data: $(this).serialize() + "&action=updateForm", // Include action parameter
      success: function (response) {
        try {
          console.log("Success ajax ");
          // Attempt to parse the JSON response
          var jsonResponse = JSON.parse(response);
          // Check if the response is successful
          if (jsonResponse.status === "success") {
            refresh();
            console.log("Data updated successfully");
          } else {
            console.error("Error updating data: " + jsonResponse.message);
          }
        } catch (error) {
          console.error("Error parsing JSON: " + error.message);
        }
      },
      error: function (error) {
        console.error("AJAX request failed: " + error.status + ", " + error.statusText);
      },
    });
  });
});// Move the refresh function outside of the document.ready// Calculate age based on the date of birth
function calculateAge(dateOfBirth) {
  const today = new Date();
  const birthDate = new Date(dateOfBirth);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}// Move the refresh function outside of the document.ready
function refresh() {
  $.ajax({
    type: "GET",
    url: "php/profile.php",
    success: function (response) {
      console.log("Raw response:", response); 
      try {
        response = JSON.parse(response);
        if (response.status === 'success') {
          
          if (response.data.name !== null) {
            $("#user-info .row:nth-child(1) .user-info span").text(response.data.name);
            $("#user-info .row:nth-child(2) .user-info span").text(response.data.email);
            $("#user-info .row:nth-child(3) .user-info span").text(response.data.dob);
            
            $("#user-info .row:nth-child(4) .user-info span").text(calculateAge(response.data.dob) + ' yrs');
  
            $("#user-info .row:nth-child(5) .user-info span").html(response.data.address.replace(/\n/g, "<br />"));
            $("#user-info .row:nth-child(6) .user-info span").text(response.data.bio);

            $("#name").val(response.data.name);
            $("#dob").val(response.data.dob);
            $("#address").val(response.data.address);
            $("#bio").val(response.data.bio);
            $("#linkedin").val(response.data.linkedin);
            $("#github").val(response.data.github);
            $("#twitter").val(response.data.twitter);

          }
          // Add similar checks for other fields
        } else {
          console.error("Error: " + response.message);
        }
      } catch (error) {
        console.error("Error parsing JSON: " + error);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX request failed: " + status + ", " + error);
    },
  });
}

// Call the refresh function as soon as the document starts loading


// Call the refresh function when the document is ready
$(document).ready(function () {
 refresh(); // ... (other code remains unchanged)
});
