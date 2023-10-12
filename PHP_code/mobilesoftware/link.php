<?php

// This PHP has the all API used in android studio

require_once 'functions.php'; // this will include the connect.php file to access the mysql database 

$response = array();

if (isset($_GET['op'])) {

  // below if condition is used to user/admin to login in
  if (isset($_GET['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

      $result = fetchARecordWithTwoWhereClause('user', 'email', $email, 'password', $password);
      if ($result) {
        $response['result'] = $result;
        echo json_encode($response);

      } else {

        $response['error'] = true;
        $response['message'] = 'Sorry, Wrong Credentials';
        echo json_encode($response);

      }

    } else {

      $response['error'] = true;
      $response['message'] = "Username and password are required.";
      echo json_encode($response);

    }

  } else

    // below if condition is used to user/admin to update the review
    if (isset($_GET['updateReview'])) {

      $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET destination = :destination , review = :review WHERE review_id = :value ');

      $criteria = [
        'destination' => $_POST['destination'],
        'review' => $_POST['review'],
        'value' => $_POST['id']
      ];
      $stmt->execute($criteria);

    } else

      // below if condition is used to user/admin to update user/admin profile
      if (isset($_GET['updateUser'])) {

        $stmt = $GLOBALS['pdo']->prepare('UPDATE USER SET email = :email , age = :age , mobile = :mobile WHERE id = :value ');

        $criteria = [
          'email' => $_POST['name'],
          'age' => $_POST['age'],
          'mobile' => $_POST['mobile'],
          'value' => $_POST['id']
        ];
        $stmt->execute($criteria);

      } else

        // below if condition is used to user/admin to update user/admin profile with password
        if (isset($_GET['updateUserwithPassword'])) {

          $stmt = $GLOBALS['pdo']->prepare('UPDATE USER SET email = :email , age = :age , mobile = :mobile , password = :password  WHERE id = :value ');

          $criteria = [
            'email' => $_POST['name'],
            'age' => $_POST['age'],
            'mobile' => $_POST['mobile'],
            'password' => $_POST['password'],
            'value' => $_POST['id']
          ];
          $stmt->execute($criteria);

        } else

          // below if condition is used to user/admin to add new review
          if (isset($_GET['addreview'])) {

            $data = [
              "destination" => $_POST['place'],
              "review" => $_POST['review'],
              "user_id" => $_POST['userid']
            ];
            $result = insert("review", $data);
            if ($result) {

              $response['error'] = false;
              $response['message'] = 'Added successfully';

            } else {

              $response['error'] = true;
              $response['message'] = 'Ooops, not added';

            }
          } else

            // below if condition is used to admin to add admin comment
            if (isset($_GET['addcomment'])) {

              $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET comment = :comment WHERE review_id = :value ');

              $criteria = [
                'comment' => $_POST['comment'],
                'value' => $_POST['userid']
              ];
              $stmt->execute($criteria);

              if ($stmt->execute($criteria)) {

                echo "Admin comment Added";

              } else {

                echo " Something went Wrong";

              }

            } else

              // below if condition is used to fetch all the reviews in the database
              if (isset($_GET['get_data'])) {

                define('HOST', 'localhost');
                define('PASS', '');
                define('DB', 'travelcompany');
                define('USER', 'root');

                $conn = mysqli_connect(HOST, USER, PASS, DB);

                $emailsql = "select * from `review`";
                $emailcheck = mysqli_query($conn, $emailsql);
                $rows = array();
                while ($row = mysqli_fetch_assoc($emailcheck)) {
                  $rows[] = $row;
                }
                $compactJsonString = json_encode($rows);
                echo $compactJsonString . PHP_EOL;
              } else

                // below if condition is used to like the reviews
                if (isset($_GET['likereview'])) {

                  $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET likecount = :likecount WHERE review_id = :value ');

                  $criteria = [
                    'likecount' => $_POST['likecount'],
                    'value' => $_POST['userid']
                  ];

                  $stmt->execute($criteria);
                  echo "Review Liked";

                } else

                  // below if condition is used to dislike the reviews
                  if (isset($_GET['dislikereview'])) {

                    $stmt = $GLOBALS['pdo']->prepare('UPDATE REVIEW SET dislikecount = :dislikecount WHERE review_id = :value ');

                    $criteria = [
                      'dislikecount' => $_POST['dislikecount'],
                      'value' => $_POST['userid']
                    ];

                    $stmt->execute($criteria);
                    echo "Review Disliked";

                  } else

                    // below if condition is used to delete a review
                    if (isset($_GET['delete'])) {

                      $data = [
                        "review_id" => $_POST['userid'],
                      ];
                      $result = deleteRecord('review', 'review_id', $_POST['userid']);
                      echo "Record Deleted";
                    }

}


?>