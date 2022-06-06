<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link href="templates/styles/signup_login.css" rel="stylesheet">
</head>
<body>
    <!-- Bootstrap login page template -->
    <main class="form-signin">
        <?php
          if (!empty($error_msg)) {
            echo "<div class='alert alert-danger'>$error_msg</div>";
          }
        ?>
        <form action="?command=signup" method="post">
          <img class="mb-4 center" src="templates/styles/icon.png" alt="Generic financial logo" width="100" height="150">
          <h1 class="h3 mb-3 fw-normal">Greetings, register below</h1>
      
          <div class="form-floating">
            <input type="text" class="form-control" id="fname" name="fname" placeholder="First name">
            <label for="fname">First name</label>
            <div id="fnameWarning" class="form-text"></div>
          </div>

          <div class="form-floating">
            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name">
            <label for="lname">Last name</label>
            <div id="lnameWarning" class="form-text"></div>
          </div>

          <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            <label for="email">Email address</label>
            <div id="emailWarning" class="form-text"></div>
          </div>

          <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
            <div id="passwordWarning" class="form-text"></div>
          </div>
      
          <div class="checkbox mb-3">
            <input type="checkbox" id="terms_agreement" name="terms_agreement">
            <label for="terms_agreement"> I have read and accept the <em>Terms & Conditions</em></label>
          </div>

          <button class="w-100 btn btn-lg btn-primary" type="submit" id="submit" disabled>Register</button>
          <p class="mt-5 mb-3 text-muted" style="text-align: center">&copy; 2022 BFinancials</p>
        </form>
        
    </main>
    <!-- Load Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script>
      function nameValidation(name_id) {
        var name = document.getElementById(name_id);
        var submit = document.getElementById("submit");
        var nameWarning = document.getElementById(name_id+"Warning");
        if (/^[A-Za-z]+$/.test(name.value)) {
          name.classList.remove("is-invalid");
          submit.disabled = false;
          nameWarning.textContent = "";
        }
        else {
          name.classList.add("is-invalid");
          submit.disabled = true;
          nameWarning.textContent = "Please only enter letters";
        }
      }
      function lengthValidation(name_id) {
        var credential = document.getElementById(name_id);
        var submit = document.getElementById("submit");
        var warning = document.getElementById(name_id+"Warning");
        if (credential.value.length > 5) {
          credential.classList.remove("is-invalid");
          submit.disabled = false;
        }
        else {
          credential.classList.add("is-invalid");
          submit.disabled = true;
          warning.textContent = "Please enter a longer "+name_id;
        }
      }
      //Call anonymous function on first name ID
      document.getElementById("fname").addEventListener("keyup", function() {
        nameValidation("fname");
      });
      //Call anonymous function on last name ID
      document.getElementById("lname").addEventListener("keyup", function() {
        nameValidation("lname");
      });
      //Call anonymous function on email ID
      document.getElementById("email").addEventListener("keyup", function() {
        lengthValidation("email");
      });
      //Call anonymous function on password ID
      document.getElementById("password").addEventListener("keyup", function() {
        lengthValidation("password");
      });
    </script>
</body>
</html>