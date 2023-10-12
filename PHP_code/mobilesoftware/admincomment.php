<?php
// This PHP is used to add admin comment to the review
include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_POST['comment'])) {

    // Preparing the SQL statement
    $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET comment = :comment WHERE review_id = :value ');

    // Fetching admin comment and review_id details from the form
    $criteria = [
        'comment' => $_POST['comment'],
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
        <h3 style="text-align: center;">Please add your comment</h3>
        <button class="btn btn-primary" style="float: right;"><a href="index.php" class="text-light">Logout</a>

    </header>

    <main>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <div class="container" style="display: flex; align-items: center; justify-content: center;">

            <form method="POST" action="#">
                <div class="form-input">
                    <label>Add Comment: </label>
                    <textarea type="text" name="comment" id="comment" rows="8" cols="100" required
                        placeholder="Enter your review">
                    <?php
                    $result = fetchARecordWithOneWhereClause('review', 'review_id', $_GET['review_id']);
                    $response = json_encode($result);
                    $data = json_decode($response, true); // Decodes the JSON array into an associative array
                    $comment = $data[0]['comment'];
                    echo $comment;
                    ?></textarea>
                    <!-- Fetching the admin comment which is in the database -->
                    <!-- input textbox to add the admin comment -->
                </div>
                <div style="text-align: center;">
                    <!-- Input element for submitting a comment -->
                    <input type="submit" value="Add comment" class="btn-login" />
                </div>
            </form>
        </div>
    </main>

</body>

</html>