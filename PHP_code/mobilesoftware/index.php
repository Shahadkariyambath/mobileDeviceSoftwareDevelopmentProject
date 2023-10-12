<!-- login.php is used for login into the page using username and password data -->

<?php
include 'functions.php'; // this will include the connect.php file to access the mysql database 
?>


<?php

if (isset($_POST['username'])) {

    $uname = $_POST['username']; //the username is assigned to a variable uname
    $password = $_POST['password']; //password is assigned to a variable password

    $result = fetchARecordWithTwoWhereClause('user', 'email', $uname, 'password', $password);

    if ($result) {

        $response = json_encode($result);
        $data = json_decode($response, true); // Decodes the JSON array into an associative array
        $id = $data[0]['id'];
        $name = $data[0]['email'];
        $role = $data[0]['email'];

        // Used session to store user details
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['role'] = $role;

        //if there is an matching row, then the page will redirected to home.php
        header('location:home.php');
    } else {
        // if there is no matching row then it will print " Something went Wrong"
        echo '<script>alert("You have entered an incorrect username or password.");</script>';
    }

}
?>

<!DOCTYPE html> <!-- html tag started -->
<html>

<head>
    <title>Travel Company</title>
    <link rel="stylesheet" href="layout.css">
</head>

<body>
    <header>
        <!-- html page header -->
        <h3 style="text-align: center;">Login Page</h3>
    </header>

    <main>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <div class="container" style="display: flex; align-items: center; justify-content: center; ">

            <form method="POST" action="#">
                <div class="form-input my-4">
                    <label>Username: </label>
                    <input type="username" name="username" required placeholder="Enter the Username" />
                    <!-- input textbox to inserting the username -->
                </div>
                <div class="form-input my-3">
                    <label>Password: </label>
                    <input type="password" name="password" required placeholder="Enter the password" />
                    <!-- input textbox to inserting the password -->
                </div>
                <div style="text-align: center;">
                    <input type="submit" type="submit" value="LOG IN" class="btn-login" />
                    <!-- Input element for submitting a login details -->
                </div>
            </form>
        </div>
    </main>

</body>

</html>