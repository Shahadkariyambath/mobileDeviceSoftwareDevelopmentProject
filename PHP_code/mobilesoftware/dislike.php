<?php
// This PHP is used to dislike reviews

include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_GET['review_id'])) {


    $result = fetchARecordWithOneWhereClause('review', 'review_id', $_GET['review_id']);
    $response = json_encode($result);
    $data = json_decode($response, true); // Decodes the JSON array into an associative array
    $dislikecount = $data[0]['dislikecount'];
    $dislikecount = intval($dislikecount); // Convert to integer
    $dislikecount++;

    // SQL preparation
    $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET dislikecount = :dislikecount WHERE review_id = :value ');

    $criteria = [
        'dislikecount' => $dislikecount,
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