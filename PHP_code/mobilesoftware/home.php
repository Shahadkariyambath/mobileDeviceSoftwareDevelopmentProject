<!-- This is the home page and here it will list the all reviews from the database -->

<?php
include 'functions.php'; // this will include the connect.php file to access the mysql database 
?>

<!DOCTYPE html> <!-- html tag started -->
<html>

<head>
    <!-- html page header -->
    <title>Travel Company</title>
    <link rel="stylesheet" href="layout.css">
    <script>
        // below function check if logged in user/admin user_id and deleting review's created user_id is same or not, and then redirect to delete.php
        function showdeleteAlert(id, review_id) {
            var user_id = <?php echo $_SESSION['user_id']; ?>;
            console.log(id);

            if (user_id == id) {
                var url = 'delete.php?review_id=' + review_id;
                window.location.href = url;
            } else {
                alert("This is not your review");
            }

        }

        // beow function check if logged in user/admin user_id and updating review's created user_id is same or not, and then redirect to update.php
        function showupdateAlert(id, review_id) {
            var user_id = <?php echo $_SESSION['user_id']; ?>;
            console.log(id);

            if (user_id == id) {
                var url = 'update.php?review_id=' + review_id;
                window.location.href = url;
            } else {
                alert("This is not your review");
            }

        }


    </script>
</head>

<body>
    <header>
        <!-- html page header -->
        <h3 style="text-align: center;">Review List</h3>
        <button class="btn btn-primary" style="float: right;"><a href="index.php" class="text-light">Logout</a>
        </button>
    </header>
    <nav>
        <ul>
            <!-- These are the three links which are given in the home page -->
            <li><a href="addreview.php">Add New Review</a></li> <!-- redirect to addreview.php -->
            <li><a href="updateUser.php">Update Profile</a></li> <!-- redirect to updateUser.php -->

        </ul>
    </nav>
    <main>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

        <body>
            <div class="container">

                <table class="table">
                    <thead>
                        <p>Welcome
                            <!-- Show logged in user/admin name -->
                            <?php
                            echo $_SESSION['user_name'];
                            ?>
                        </p>
                        <tr>
                            <!-- Column names -->
                            <th scope="col">Place</th>
                            <th scope="col">Review</th>
                            <th scope="col">Admin comment</th>
                            <th scope="col"></th>
                            <th scope="col"></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Below code is used to fetch all the reviews 
                        $statement = fetchAllRecordsWithFetchAll("review");

                        // Below cade will display each review in the table 
                        if ($statement) {
                            foreach ($statement as $row) {
                                //for each row returned from database, each value will be assigned to a variable and the value will be echoed into database table.
                                $review_id = $row['review_id'];
                                $destination = $row['destination'];
                                $review = $row['review'];
                                $user_id = $row['user_id'];
                                $comment = $row['comment'];
                                $likecount = $row['likecount'];
                                $dislikecount = $row['dislikecount'];

                                if ($_SESSION['role'] == 'admin') {
                                    echo '<tr>
    <th scope="row">' . $destination . '</th>
    <td>' . $review . '</td>
    <td>' . $comment . '</td>
    <td>
    <button class="btn btn-primary" style="font-size: 15px; padding: 4px 8px;"><a href="like.php? review_id=' . $review_id . '" class="text-light">Like</a></button>
    ' . $likecount . '
    <button class="btn btn-primary" style="font-size: 15px;padding: 4px 8px;margin: 5px;"><a href="dislike.php? review_id=' . $review_id . '" class="text-light">Dislike</a></button>
    ' . $dislikecount . '
    <button class="btn btn-primary" style="font-size: 15px; padding: 4px 8px;"><a href="admincomment.php? review_id=' . $review_id . '" class="text-light">Add Admin Comment</a></button>

    </td>
    
    <td>
    <button class="btn btn-danger" style="font-size: 15px;padding: 4px 8px;margin: 5px"><a onclick="showdeleteAlert(' . $user_id . ',' . $review_id . ')" user_id=' . $user_id . ' review_id=' . $review_id . '" class="text-light">Delete</a></button>

   <button class="btn btn-info" style="font-size: 15px;padding: 4px 8px;margin: 5px"><a onclick="showupdateAlert(' . $user_id . ',' . $review_id . ')" review_id=' . $review_id . '" class="text-light">Update</a></button>
    
 </td>
  </tr>';
                                } else {
                                    echo '<tr>
                      <th scope="row">' . $destination . '</th>
                      <td>' . $review . '</td>
                      <td>' . $comment . '</td>
                      <td>
                      <div style="display: flex;align-items: center;padding-block-start: 7px;"">
                      <button class="btn btn-primary" "><a href="like.php? review_id=' . $review_id . '" class="text-light">Like</a></button>
                      
                      <span style="margin-left: 5px;"> ' . $likecount . '</span>
                      </div>
                      <div style="display: flex;align-items: center;padding-block-start: 7px;"">
                      <button class="btn btn-primary" "><a href="dislike.php? review_id=' . $review_id . '" class="text-light">Dislike</a> </button>
                      
                         <span style="margin-left: 5px;"> ' . $dislikecount . '</span>
                      </td>
                      </div>
                      
                      <td>
                      <button class="btn btn-danger" style="font-size: 15px;padding: 4px 8px;margin: 5px"><a onclick="showdeleteAlert(' . $user_id . ',' . $review_id . ')" user_id=' . $user_id . ' review_id=' . $review_id . '" class="text-light">Delete</a></button>
                
                     <button class="btn btn-info" style="font-size: 15px;padding: 4px 8px;margin: 5px"><a onclick="showupdateAlert(' . $user_id . ',' . $review_id . ')" review_id=' . $review_id . '" class="text-light">Update</a></button>
                     </div>
                   </td>
                    </tr>';
                                }

                            }
                        }

                        ?>

                    </tbody>
                </table>
            </div>
    </main>
</body>

</html>