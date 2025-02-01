<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&amp;display=swap" rel="stylesheet" />
    <link href="css/theme-03.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="loginForm" method="post">
        <div class="forny-container"> 
            <div class="forny-inner">
                <div class="form-group">
                    <div class="input-group">
                        <div class="heading">
                            <h1 class="navbar-brand"><span>Client Dash Board</span></h1>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <h4>Client Login</h4>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input name="txtEmail" type="text" id="txtEmail" class="form-control" placeholder="User Id" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input name="txtpass" type="password" id="txtpass" class="form-control" placeholder="Password" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="cb1">
                            <label class="custom-control-label" for="cb1">
                                Remember me
                            </label>
                        </div>
                        <div class="col-6 text-right">
                            <a href="#">Forgot password?</a>
                        </div>
                    </div>
                </div>

                <!-- Display error message if login fails -->
                <div id="errorMessage" style="color: red; display: none;"></div>

                <div class="form-group">
                    <div class="input-group">
                        <input type="submit" name="Button1" value="Login" id="Button1" class="btn btn-primary" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function(e) {
                e.preventDefault(); // Prevent the default form submission

                var email = $("#txtEmail").val();
                var password = $("#txtpass").val();

                $.ajax({
                    url: "login.php", // The PHP script for handling the login
                    type: "POST",
                    data: {
                        txtEmail: email,
                        txtpass: password
                    },
                    success: function(response) {
                        if (response == "success") {
                            // Redirect to the desired page after successful login
                            window.location.href = "index2.php";
                        } else {
                            // Show error message if login fails
                            $("#errorMessage").text(response).show();
                        }
                    },
                    error: function() {
                        alert("An error occurred while processing your request.");
                    }
                });
            });
        });
    </script>

    <script src="Scripts/bootstrap.js"></script>
    <script src="Scripts/bootstrap.min.js"></script>
    <script src="Scripts/bootstrap-table.init.js"></script>
</body>
</html>
