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
// ini_set('display_errors', 'On');

$db = new mysqli('localhost', 'cs143', '', 'CS143');

function alert($message) {
  echo "<script type='text/javascript'>alert('$message');</script>";
}

// function correct_input($data) {
//   $data = trim($data);
//   $data = stripslashes($data);
//   $data = htmlspecialchars($data);
//   return $data;
// }

// function getNameError($name){
//   $error = "";
//   if (empty($name)) {
//     $error = "Name is required";
//   }else {
//     $name = correct_input($name);
//     if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
//       $name = "Only letters and white space allowed"; 
//     }
//   }
//   return $error;
// }

function getFieldEmptyError($field){
  return (empty($field))? "Field cannot be empty" : "";
}

function getYearError($field) {
  return (is_numeric($field) && $field > 1877) ? "" : "Year is invalid";
}

$result = "";
$title = $company = $year = $rating = $genre = "";
$titleErr = $companyErr = $yearErr = $ratingErr = $genreErr ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }
  $title = $_POST["title"];
  $company = $_POST["company"];
  $year = $_POST["year"];
  $rating = $_POST["rating"];
  $genre = $_POST["genre"];

  
  


  $titleErr = getFieldEmptyError($title);
  $companyErr = getFieldEmptyError($company);
  $yearErr = getYearError($year);
  $ratingErr = getFieldEmptyError($rating); //not sure
  $genreErr = getFieldEmptyError($genre);

  if(empty($titleErr) && empty($companyErr) && empty($yearErr) && empty($ratingErr) && empty($genreErr)){

    $maxMovieIdQuery = "SELECT id FROM MaxMovieID;";
    $queryResult = $db->query($maxMovieIdQuery);

    $nextId = 0;
   // alert("rows:".$queryResult->num_rows);
    if ($queryResult->num_rows > 0) {
        // output data of each row
        while($row = $queryResult->fetch_assoc()) {
            $nextId = $row["id"] + 1;
            //alert("next id is : ". $nextId);
        }
    }

    $stmt1 = $stmt2 = "";
    $stmt1 = $db->prepare("INSERT INTO Movie (id, title, year, rating, company) VALUES (?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssss", $nextId, $title, $year, $rating, $company);

    $allQueriesPassed = TRUE;

    
    


    if ($stmt1->execute() === TRUE) {
      if(!empty($genre)) {
      foreach($genre as $g) {
        $query = "INSERT INTO MovieGenre (mid, genre) VALUES ($nextId, '$g');";
        $qresult = $db->query($query);
        //alert("result is " . $qresult);
        if($qresult===FALSE){
          echo "error: ".$db->error;
        }
        
      }
    }
       if($allQueriesPassed === TRUE) {
          $result = "New movie record created successfully with ID: " . $nextId;
       }
       else {
          $result = "Failed to add new genre: " . $db->error;
       }
        
        //alert($genre);
        $updateMaxMovieId = "UPDATE MaxMovieID SET id={$nextId};";
        if ($db->query($updateMaxMovieId) === FAlSE) {
            $result = "New movie record created, but failed to update max movie ID: " . $db->error;
        }
    } else {
        $result = "Failed to add new movie record: " . $db->error;
    } 
    $stmt1->close();
    



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
        <li><a href="#">Add Movie</a></li>
        <li><a href="./add_actor_to_movie.php">Add Actor to Movie</a></li>
        <li><a href="./add_director_to_movie.php">Add Director to Movie</a></li>
      </ul>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Add Movie</h1>
          <span class="error">* Required Fields</span><br>
          <form method="POST">
              <label>Title: <span class="error">* <?php echo $titleErr;?></span><br></label>
              <input type="text" class="form-control" name="title" id="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '' ?>">
              <br>
              <label>Company<span class="error">* <?php echo $compantErr;?></span><br></label>
              <input type="text" class="form-control" name="company" id="company" value="<?php echo isset($_POST['company']) ? $_POST['company'] : '' ?>">
              <br>
              <label>Year<span class="error">* <?php echo $yearErr;?></span><br></label><br>
              <input type="text" class="form-control" name="year" id="year" value="<?php echo isset($_POST['year']) ? $_POST['year'] : '' ?>">
            <br>
            <label>MPAA Rating<span class="error">* <?php echo $ratingErr;?></span><br></label><br>
            <select id = 'rating' name = "rating">
              <option value=""></option>
              <option value="G">G</option>
              <option value="NC-17">NC-17</option>
              <option value="PG">PG</option>
              <option value="PG-13">PG-13</option>
              <option value="R">R</option>
              <option value="surrendre">surrendre</option>
            </select><br>
            <br>
            <label>Genre<span class="error">* <?php echo $genreErr;?></span><br></label><br>
            <input type="checkbox" name="genre[]" value="Action"> Action<br>
            <input type="checkbox" name="genre[]" value="Adult" > Adult<br>
            <input type="checkbox" name="genre[]" value="Adventure" > Adventure<br>
            <input type="checkbox" name="genre[]" value="Animation" > Animation<br>
            <input type="checkbox" name="genre[]" value="Comedy" > Comedy<br>
            <input type="checkbox" name="genre[]" value="Documentary" > Documentary<br>
            <input type="checkbox" name="genre[]" value="Drama" > Drama<br>
            <input type="checkbox" name="genre[]" value="Family" > Family<br>
            <input type="checkbox" name="genre[]" value="Honor" > Honor<br>
            <input type="checkbox" name="genre[]" value="Musical" > Musical<br>
            <input type="checkbox" name="genre[]" value="Mystery" > Mystery<br>
            <input type="checkbox" name="genre[]" value="Romance" > Romance<br>
            <input type="checkbox" name="genre[]" value="Sci-Fi" > Sci-Fi<br>
            <input type="checkbox" name="genre[]" value="Short" > Short<br>
            <input type="checkbox" name="genre[]" value="Thriller" > Thriller<br>
            <input type="checkbox" name="genre[]" value="War" > War<br>
            <input type="checkbox" name="genre[]" value="Western" > Western<br>
            <br>
            <button type="submit" class="btn btn-success" >Add</button>
            <span><?php echo $reuslt;?></span>
           </div>
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
