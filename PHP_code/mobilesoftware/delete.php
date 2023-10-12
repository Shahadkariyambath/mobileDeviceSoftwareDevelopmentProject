<?php
// This PHP is used to delete review
include 'functions.php'; // this will include the connect.php file to access the mysql database 

if (isset($_GET['review_id'])) {
    $id = $_GET['review_id'];
    $result = deleteRecord('review', 'review_id', $id);

    if ($result) {
        //if there is an matching row, then the page will redirected to home.php
        header('location:home.php');
    } else {
        // if there is no matching row then it will print " Something went Wrong"
        echo " Something went Wrong";
    }
}

?>