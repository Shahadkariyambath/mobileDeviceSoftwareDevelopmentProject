<?php
// This PHP is used to like the review

include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_GET['review_id'])) {


    $result = fetchARecordWithOneWhereClause('review', 'review_id', $_GET['review_id']);
    $response = json_encode($result);
    $data = json_decode($response, true); // Decodes the JSON array into an associative array
    $likecount = $data[0]['likecount'];
    $likecount = intval($likecount); // Convert to integer
    $likecount++;

    // SQL preparation
    $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET likecount = :likecount WHERE review_id = :value ');

    $criteria = [
        'likecount' => $likecount,
        'value' => $_GET['review_id']
    ];


    if ($stmt->execute($criteria)) {
        //if there is an matching row, then the page will redirected to home.php
        header('location:home.php');
    } else {
        // if there is no matching row then it will print " Something went Wrong"
        echo " Something went Wrong";
    }
}

?>