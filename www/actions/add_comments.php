<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add A New Comment</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .error {color: #FF0000;}
    </style>
  </head>
  <body>


<?php
// for debugging only
//ini_set('display_errors', 'On');

$db = new mysqli('localhost', 'cs143', '', 'CS143');

function alert($message) {
  echo "<script type='text/javascript'>alert('$message');</script>";
}

function getFieldEmptyError($field){
  return (empty($field))? "Field cannot be empty" : "";
}

$result = "";
$name = $rating = $comment = ""; //time stamp is not listed here
$ratingErr = $commentErr = "";

$title = $_GET["title"];
  $mid = $_GET["mid"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }
  $name = trim($_POST["name"]);
  $rating = $_POST["rating"];
  $comment = trim($_POST["comment"]);
  
 
  $ratingErr = getFieldEmptyError($rating);
  
  $name = mysql_escape_string($name);
  $comment = mysql_escape_string($comment);
  $time = time();
  $mysqldate = date( 'Y-m-d H:i:s', $time );

  $name = empty($name) ? "Anonymous" : $name;

  if(empty($ratingErr)){

    //get the movie id based on the movie title
    $insertReviewQuery = "INSERT INTO Review (name, time, mid, rating, comment) VALUES ('$name', '$mysqldate', $mid, $rating, '$comment')";
    // $queryResult = mysql_query($insertReviewQuery, $db);
    $queryResult = $db->query($insertReviewQuery);

    if($queryResult === TRUE) {
      $result = "New review created successfully for movie: " . $title;
    }
    else {
      $result = "Failed to add new review: " . $db->error;
      echo $result;
    }
    // $stmt = "";
    // $stmt = $db->prepare("INSERT INTO Review (name, time, mid, rating, comment) VALUES (?, ?, ?, ?, ?)");
    // $stmt->bind_param("sssss", $name, $time, $mid, $rating, $comment);
    //alert("success");
    // if ($stmt->execute() === TRUE) {
    //     alert("success");
    //     $result = "New review created successfully for movie: " . $title;
        
    // } else {
    //     $result = "Failed to add new review: " . $db->error;
    // }
    // $stmt->close();
  }else{
    $result = "Please fill in the required fields";
  }
  $db->close();
}

?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../index.php">Movie Database project</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form action="./search.php" method="GET" class="navbar-form navbar-right">
            <input name="search" type="text" class="form-control" placeholder="Search...">
            <input type="submit" style="display:none"/>
          </form>
        </div>
      </div>
    </nav>

    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li><a href="./add_person.php">Add Actor/Director</a></li>
        <li><a href="./add_movie.php">Add Movie</a></li>
        <li><a href="./add_actor_to_movie.php">Add Actor to Movie</a></li>
        <li><a href="./add_director_to_movie.php">Add Director to Movie</a></li>
      </ul>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Add Comments for <?php echo $title;?></h1>
          <span class="error">* Required Fields</span><br>
          <form method="POST">
              
              
              <label>Your Name</label>
              <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
            
              <br>
              
              <label>Review Rating<span class="error">* <?php echo $ratingErr;?></span><br></label><br>
                <select id = 'rating' name = "rating">
                  <option value=1>1</option>
                  <option value=2>2</option>
                  <option value=3>3</option>
                  <option value=4>4</option>
                  <option value=5>5</option>
                </select>
                <br>
                <br>
              <label>Additional Comments: </label><br>
              <input type="text" class="form-control" name="comment" id="comment" value="<?php echo isset($_POST['comment']) ? $_POST['comment'] : '' ?>">
              <br>
              <button type="submit" class="btn btn-success" >Add</button>
              <span><?php echo $result;?></span>
          </form>
        </div>
      </div>
    </div>
      
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>



  </body>
</html>
