<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Search</title>

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
ini_set('display_errors', 'On');

$db = new mysqli('localhost', 'cs143', '', 'CS143');

function alert($message) {
  echo "<script type='text/javascript'>alert('$message');</script>";
}

$id = "";
$avgRate = "";
$first = "";
$last = "";
$matchingMovie = [];
$matchingDirector = [];
$matchingGenre = [];
$directorNames = ""; //concatenate all directors' names
$genreList = ""; //list of genres for this movie
$actorRows = []; //actors in this matching movie
$reviewRows = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }
  $id = $_GET["id"];

  $findMatchingMovieQuery = "SELECT id, title, year, rating, company FROM Movie WHERE id=$id";

  $movie = $db->query($findMatchingMovieQuery);
  if ($movie->num_rows > 0) {
      while($row = $movie->fetch_assoc()) {
        $matchingMovie = $row;
      }
  }

  $findMatchingActorsQuery = "SELECT Actor.id AS aid, Actor.first AS first, Actor.last AS last, MovieActor.role AS role FROM Actor JOIN MovieActor ON Actor.id = MovieActor.aid WHERE mid=$id";

  $actors = $db->query($findMatchingActorsQuery);
  if ($actors->num_rows > 0) {
      while($row = $actors->fetch_assoc()) {
        array_push($actorRows, $row);
      }
  }


  $findMatchingReviewQuery = "SELECT name, rating, comment FROM Review WHERE mid=$id";
  $findAvgRatingQuery = "SELECT ROUND(AVG(rating),1) AS avg FROM Review WHERE mid=$id";
  $findMatchingDirectorQuery = "SELECT first, last FROM Director WHERE id IN (SELECT did FROM MovieDirector WHERE mid = $id)";
  $findMatchingGenreQuery  = "SELECT genre FROM MovieGenre WHERE mid = $id";

  $avgRating = $db->query($findAvgRatingQuery);
  if($avgRating->num_rows > 0){
    while($row = $avgRating->fetch_assoc()) {
        $avgRate = $row["avg"];
      }
  }
  $director = $db->query($findMatchingDirectorQuery);
  if($director->num_rows > 0) {
    while($row = $director->fetch_assoc()) {
        array_push($matchingDirector, $row);
      }
  } 

  foreach ($matchingDirector as $row) {
    $directorNames = $directorNames . " " .$row["first"] ." ". $row["last"];
  }

  $genre = $db->query($findMatchingGenreQuery);
  if($genre->num_rows > 0) {
    while($row = $genre->fetch_assoc()) {
        array_push($matchingGenre, $row);
      }
  } 

  foreach ($matchingGenre as $row) {
    $genreList = $genreList . " " .$row["genre"];
  }

  
  $review = $db->query($findMatchingReviewQuery);
  if ($review->num_rows > 0) {
      while($row = $review->fetch_assoc()) {
        array_push($reviewRows, $row);
      }
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
          <h1 class="page-header">Info about movie <?php echo "'".$matchingMovie["title"]."'";?></h1>
 
           <h2>Matching Movie</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Director</th>
                  <th>Title</th>
                  <th>Genre</th>
                  <th>Year</th>
                  <th>Rating</th>
                  <th>Company</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                echo "<tr>";
                echo "<td>".$matchingMovie["id"]."</td>";
                echo "<td>".$directorNames."</td>";
                echo "<td>".$matchingMovie["title"]."</td>";
                echo "<td>".$genreList."</td>";
                echo "<td>".$matchingMovie["year"]."</td>";
                echo "<td>".$matchingMovie["rating"]."</td>";
                echo "<td>".$matchingMovie["company"]."</td>";
                echo "</tr>";
              ?>
              </tbody>
            </table>
          </div>

          <h2>Actors in this Movie:</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Role</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                foreach ($actorRows as $row){
                  echo "<tr>";
                  echo "<td><a href="."'"."./show_actor_info.php?id=".$row["aid"]."'".">".$row["first"]." ".$row["last"]."</a></td>";
                  echo "<td>".$row["role"]."</td>";
                  echo "</tr>";
                }
              ?>
              </tbody>
            </table>
          </div>

          <h2>Average Rating: <?php echo $avgRate; ?></h2>
          
          

          <h2>Reviews for This Movie:</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Reviewer Name</th>
                  <th>Rating</th>
                  <th>Comments</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                foreach ($reviewRows as $row){
                  echo "<tr>";
                  echo "<td>".$row["name"]."</td>";
                  echo "<td>".$row["rating"]."</td>";
                  echo "<td>".$row["comment"]."</td>";
                  echo "</tr>";
                }
              ?>
              </tbody>
            </table>
          </div>

          <?php 
          echo "<a href="."'"."./add_comments.php?title=".$matchingMovie["title"]."&mid=".$matchingMovie["id"]."'".">"."Click here to add review for this movie"."</a>"
          ?>
          

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
