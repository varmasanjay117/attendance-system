<?php

//login.php

include('database_connection.php');

session_start();

if(isset($_SESSION["admin_id"]))
{
  header('location:index.php');
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Welcome to Student Attendance System</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>

    <body>

        <div class="jumbotron text-center" style="margin-bottom:0">
            <h1>Student Attendance System</h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4" style="margin-top:20px;">
                    <div class="card">
                        <div class="card-header">Admin Login</div>
                        <div class="card-body">
                            <form method="post" id="admin_login_form">
                                <div class="form-group">
                                    <label>Enter Username</label>
                                    <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" />
                                    <span id="error_admin_user_name" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <input type="password" name="admin_password" id="admin_password" class="form-control" />
                                    <span id="error_admin_password" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="admin_login" id="admin_login" class="btn btn-info" value="Login" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>

    </body>

    </html>