<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add A New Movie</title>

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

function getFieldEmptyError($field){
  return (empty($field))? "Field cannot be empty" : "";
}
$movieErr = $actorErr = $roleErr = "";
$movieRows = [];
  $actorRows = [];

  //get all movie (title, year) from database
  $movieQuery = "SELECT id, title, year FROM Movie;";
  $movieResult = $db->query($movieQuery);

  $actorQuery = "SELECT id, first, last, dob FROM Actor;";
  $actorResult = $db->query($actorQuery);

  if ($movieResult->num_rows > 0) {
        // output data of each row
        while($row = $movieResult->fetch_assoc()) {
          array_push($movieRows, $row);
        }
    }


  if ($actorResult->num_rows > 0) {
        // output data of each row
        while($row = $actorResult->fetch_assoc()) {
          array_push($actorRows, $row);
         // alert($row['id']);
        }
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }

  $mid = $_POST["selectedMovieId"];
  $aid = $_POST["selectedActorId"];
  $role = $_POST["role"];
  $movieErr = getFieldEmptyError($mid);
  $actorErr = getFieldEmptyError($aid);
  $roleErr = getFieldEmptyError($role);

  // if(empty($movieErr) == false || empty($actorErr) == false || empty($roleErr) == false) {
  //   $result = "Please fill in the required fields";
  // }

  $stmt = $db->prepare("INSERT INTO MovieActor (mid, aid, role) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $mid, $aid, $role);
  if ($stmt->execute() === TRUE) {
    $result = "Add new movie actor relation successfully ";
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
        <li><a href="#">Add Actor to Movie</a></li>
        <li><a href="./add_director_to_movie.php">Add Director to Movie</a></li>
      </ul>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Add Actor to Movie</h1>
          <span class="error">* Required Fields</span><br>
          <form method="POST">
          <label>Movies<span class="error">* <?php echo $movieErr;?></span><br></label>
          <br>
            <select name="selectedMovieId" id = "selectedMovieId">
              <?php foreach($movieRows as $row) : ?>
                  <option value="<?php echo $row['id']; ?>"> <?php echo $row['title'] . " " . $row['year']; ?> </option>";
              <?php endforeach; ?>
            </select>
            <br>
            <br>
      
            <label>Actors<span class="error">* <?php echo $actorErr;?></span><br></label>
            <br>
            <select name="selectedActorId" id = "selectedActorId">
              <?php foreach($actorRows as $row) : ?>
                  <option value="<?php echo $row['id']; ?>"> <?php echo $row['first'] . " " . $row['last'] . " " . $row['dob']; ?> </option>";
              <?php endforeach; ?>
            </select>
            <br>
            <br>
            <label>Role <span class="error">* <?php echo $roleErr;?></span><br></label>
              <input type="text" class="form-control" name="role" id="role" value="<?php echo isset($_POST['role']) ? $_POST['role'] : '' ?>"><br>
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
