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
// ini_set('display_errors', 'On');

$db = new mysqli('localhost', 'cs143', '', 'CS143');

function alert($message) {
  echo "<script type='text/javascript'>alert('$message');</script>";
}

$searchString = "";
$movieRows = [];
$actorRows = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }
  $searchString = $_GET["search"];
  $keys = explode(" ", $searchString);
  $findMatchingMoviesQuery = "SELECT id, title, year, rating, company FROM Movie WHERE title LIKE " . "'" . "%$searchString%" . "';";

  $multiSearchQuery = "SELECT id, CONCAT(first, ' ', last) AS name, sex, dob, dod FROM Actor WHERE ";
  $count = 0;
  foreach($keys as $key){
    $multiSearchQuery = $multiSearchQuery . "CONCAT(first, ' ', last) LIKE '%$key%'";
    $count = $count + 1;
    if($count !== count($keys)){
      $multiSearchQuery = $multiSearchQuery . " AND ";
    }
    $multiSearchQuery . "ORDER BY first";
  }

  $singleSearchQuery = "SELECT id, CONCAT(first, ' ', last) AS name, sex, dob, dod FROM Actor WHERE (last LIKE " . "'" . "%$searchString%" . "'" .") OR (first LIKE " . "'" . "%$searchString%" . "') ORDER BY first;";

  $findMatchingActorsQuery = (count($keys)>1) ? $multiSearchQuery : $singleSearchQuery;

  $movies = $db->query($findMatchingMoviesQuery);
  if ($movies->num_rows > 0) {
      // output data of each row
      while($row = $movies->fetch_assoc()) {
        array_push($movieRows, $row);
      }
  }

  $actors = $db->query($findMatchingActorsQuery);
  if ($actors->num_rows > 0) {
      // output data of each row
      while($row = $actors->fetch_assoc()) {
        array_push($actorRows, $row);
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
          <form method="GET" class="navbar-form navbar-right">
            <input id="search" name="search" type="text" class="form-control" placeholder="Search...">
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
            <h1 class="page-header">Search result for <?php echo "'".$searchString."'";?></h1>
            <h2>Matching Movies</h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Company</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  foreach ($movieRows as $row){
                    echo "<tr>";
                    echo "<td><a href="."'"."./show_movie_info.php?id=".$row["id"]."'".">".$row["title"]."</a></td>";
                    echo "<td>".$row["year"]."</td>";
                    echo "<td>".$row["rating"]."</td>";
                    echo "<td>".$row["company"]."</td>";
                    echo "</tr>";
                  }
                ?>
                </tbody>
              </table>
            </div>
   
             <h2>Matching Actors</h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Sex</th>
                    <th>Date of Birth</th>
                    <th>Date of Death</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  foreach ($actorRows as $row){
                    echo "<tr>";
                    echo "<td><a href="."'"."./show_actor_info.php?id=".$row["id"]."'".">".$row["name"]."</a></td>";
                    echo "<td>".$row["sex"]."</td>";
                    echo "<td>".$row["dob"]."</td>";
                    echo "<td>".$row["dod"]."</td>";
                    echo "</tr>";
                  }
                ?>
                </tbody>
              </table>
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
