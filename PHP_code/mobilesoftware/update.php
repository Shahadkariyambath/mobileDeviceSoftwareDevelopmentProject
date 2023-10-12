<?php
// This PHP is used to update the review

include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_POST['place'])) {

    // SQL prepration
    $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET destination = :destination , review = :review WHERE review_id = :value ');

    $criteria = [
        'destination' => $_POST['place'],
        'review' => $_POST['review'],
        'value' => $_GET['review_id']
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
                    <input type="text" name="place" value="<?php
                    $result = fetchARecordWithOneWhereClause('review', 'review_id', $_GET['review_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $destination = $data[0]['destination'];
                    echo $destination;
                    ?>" style="margin-left: 10px;" />
                    <!-- Fetching the review place which is in the database -->
                    <!-- input textbox to add the updated review place -->
                </div>
                <div class="form-input">
                    <label>Review: </label>
                    <textarea type="text" name="review" id="review" rows="8" cols="100" required
                        placeholder="Enter your review">
                    <?php
                    $result = fetchARecordWithOneWhereClause('review', 'review_id', $_GET['review_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $review = $data[0]['review'];
                    echo $review;
                    ?></textarea>
                    <!-- Fetching the review which is in the database -->
                    <!-- input textbox to add the updated review -->
                </div>
                <div style="text-align: center;">
                    <input type="submit" value="Update review" class="btn-login" />
                    <!-- Input element for submitting a updated review details -->
                </div>
            </form>
        </div>
    </main>

</body>

</html>