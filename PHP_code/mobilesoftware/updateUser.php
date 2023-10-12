<?php
// This PHP is used to update the user profile

include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_POST['password']) || isset($_POST['confirm_password'])) {
    // check if password and confirm password is null or not
    if ($_POST['password'] === "" && $_POST['confirm_password'] === "") {

        if (isset($_POST['name'])) {

            //SQL preparation
            $stmt = $GLOBALS['pdo']->prepare('UPDATE USER SET email = :email , age = :age , mobile = :mobile WHERE id = :value ');

            $criteria = [
                'email' => $_POST['name'],
                'age' => $_POST['age'],
                'mobile' => $_POST['mobile'],
                'value' => $_SESSION['user_id']
            ];
            $stmt->execute($criteria);


            if ($stmt->execute($criteria)) {
                //if there is an matching row, then the page will redirected to home.php
                header('location:home.php');
            } else {
                // if there is no matching row then it will print " Something went Wrong"
                echo " Something went Wrong";
            }
        }

    } else {
        // check if password and confirm password is equal or not and then it will updat in the database
        if ($_POST['password'] === $_POST['confirm_password']) {
            echo '<script>alert("The Password is equal");</script>';

            if (isset($_POST['name'])) {

                $stmt = $GLOBALS['pdo']->prepare('UPDATE USER SET email = :email , age = :age , mobile = :mobile , password = :password  WHERE id = :value ');

                $criteria = [
                    'email' => $_POST['name'],
                    'age' => $_POST['age'],
                    'mobile' => $_POST['mobile'],
                    'password' => $_POST['password'],
                    'value' => $_SESSION['user_id']
                ];
                $stmt->execute($criteria);


                if ($stmt->execute($criteria)) {
                    //if there is an matching row, then the page will redirected to home.php
                    header('location:home.php');
                } else {
                    // if there is no matching row then it will print " Something went Wrong"
                    echo " Something went Wrong";
                }
            }

        } else {
            // The below code will display an alert which will show that "The Password should be equal"
            echo '<script>alert("The Password should be equal");</script>';
        }
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
        <h3 style="text-align: center;">Please update your profile</h3>
        <button class="btn btn-primary" style="float: right;"><a href="index.php" class="text-light">Logout</a>

    </header>

    <main>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <div class="container" style="display: flex; align-items: center; justify-content: center;">

            <form method="POST" action="#">
                <div class="form-input my-4">
                    <label>Name: </label>
                    <input type="text" name="name" value="<?php
                    $result = fetchARecordWithOneWhereClause('user', 'id', $_SESSION['user_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $destination = $data[0]['email'];
                    echo $destination;
                    ?>" style="margin-left: 15px;" />
                    <!-- Fetching the user name which is in the database -->
                    <!-- input textbox to add the updated user name -->
                </div>
                <div class="form-input my-4">
                    <label>Age: </label>
                    <input type="text" name="age" value="<?php
                    $result = fetchARecordWithOneWhereClause('user', 'id', $_SESSION['user_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $destination = $data[0]['age'];
                    echo $destination;
                    ?>" style="margin-left: 30px;" />
                    <!-- Fetching the user age which is in the database -->
                    <!-- input textbox to add the updated user age -->
                </div>
                <div class="form-input my-4">
                    <label>Mobile: </label>
                    <input type="text" name="mobile" value="<?php
                    $result = fetchARecordWithOneWhereClause('user', 'id', $_SESSION['user_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $destination = $data[0]['mobile'];
                    echo $destination;
                    ?>" style="margin-left: 10px;" />
                    <!-- Fetching the user mobile number which is in the database -->
                    <!-- input textbox to add the updated user mobile number -->
                </div>

                <div class="form-input my-4">
                    <label>Password: </label>
                    <input type="password" name="password" value="" id="password" style="margin-left: 10px;" />
                    <!-- input textbox to inserting the new passowrd -->
                </div>

                <div class="form-input my-4">
                    <label>Confirm passowrd: </label>
                    <input type="password" name="confirm_password" value="" id="confirm_password"
                        style="margin-left: 10px;" />
                    <!-- input textbox to inserting the confirm password -->
                </div>

                <div style="text-align: center;">
                    <input type="submit" value="Update profile" class="btn-login" />
                    <!-- Input element for submitting a updated profile details -->
                </div>

            </form>
        </div>
    </main>

</body>

</html>