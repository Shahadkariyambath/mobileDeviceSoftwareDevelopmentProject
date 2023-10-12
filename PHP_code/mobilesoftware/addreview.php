<?php
// This PHP is used to add new review

require_once 'functions.php'; // this will include the connect.php file to access the mysql database 
$response = array();

if (isset($_POST['place'])) {

    // Fetching newly added place and review details from the form

    $data = [
        "destination" => $_POST['place'],
        "review" => $_POST['review'],
        "user_id" => $_SESSION['user_id']
    ];

    // Insert newly added place and review into the database

    $result = insert("review", $data);

    if ($result) {
        //if there is an matching row, then the page will redirected to home.php
        header('location:home.php');
    } else {
        // if there is no matching row then it will print " Something went Wrong"
        echo " Something went Wrong";
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
        <h3 style="text-align: center;">Please enter your review</h3>
        <button class="btn btn-primary" style="float: right;"><a href="index.php" class="text-light">Logout</a>

    </header>

    <main>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <div class="container" style="display: flex; align-items: center; justify-content: center;">

            <form method="POST" action="#">

                <div class="form-input my-5">
                    <label>Place: </label>
                    <input type="text" name="place" placeholder="Enter the place name" style="margin-left: 10px;" />
                    <!-- input textbox to inserting the Place -->
                </div>
                <div class="form-input">
                    <label>Review: </label>
                    <textarea type="text" name="review" id="review" rows="8" cols="100" required
                        placeholder="Enter your review"></textarea>
                    <!-- input textbox to inserting the Review -->
                </div>
                <div style="text-align: center;">
                    <!-- Input element for addming new review -->
                    <input type="submit" value="Add review" class="btn-login" />
                    <!-- input button to entered in respective fields -->
                </div>

            </form>
        </div>
    </main>

</body>

</html>