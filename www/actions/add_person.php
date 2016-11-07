<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Add person</title>

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
    <link rel="stylesheet" href="../assets/css/jquery-ui.css">
    <script src="../assets/js/jquery-1.12.4.js"></script>
    <script src="../assets/js/jquery-ui.js"></script>
    <!-- Javascript -->
    <script>
       $(function() {
          $( "#datepicker1" ).datepicker();
          $( "#datepicker2" ).datepicker();
       });
    </script>

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

function correct_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function getNameError($name){
  $error = "";
  if (empty($name)) {
    $error = "Name is required";
  }else {
    $name = correct_input($name);
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $name = "Only letters and white space allowed"; 
    }
  }
  return $error;
}

function getFieldEmptyError($field){
  return (empty($field))? "Field cannot be empty" : "";
}

$result = "";
$type = $first = $last = $sex = $dob = "";
$typeErr = $firstNameErr = $lastNameErr = $sexErr = $dobErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
  }
  $type = $_POST["personType"];
  $first = $_POST["first"];
  $last = $_POST["last"];
  $sex = $_POST["sex"];
  $dob = $_POST["dob"];
  $dod = $_POST["dod"];

  $dob=date("Y-m-d",strtotime($dob));
  $dod = ($dod=="") ? null : date("Y-m-d",strtotime($dod));

  $typeErr = getFieldEmptyError($type);
  $firstNameErr = getNameError($first);
  $lastNameErr = getNameError($last);
  $sexErr = getFieldEmptyError($sex);
  $dobErr = getFieldEmptyError($dob);

  if(empty($typeErr) && empty($firstNameErr) && empty($firstNameErr) && empty($sexErr) && empty($dobErr)){

    $maxPersonIdQuery = "SELECT id FROM MaxPersonID;";
    $queryResult = $db->query($maxPersonIdQuery);

    $nextId = 0;
    if ($queryResult->num_rows > 0) {
        // output data of each row
        while($row = $queryResult->fetch_assoc()) {
            $nextId = $row["id"] + 1;
        }
    }

    $stmt = "";
    if($type == 'Actor'){
      $stmt = $db->prepare("INSERT INTO Actor (id, first, last, sex, dob, dod) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssss", $nextId, $first, $last, $sex, $dob, $dod);
    }else{
      $stmt = $db->prepare("INSERT INTO Director (id, first, last, dob, dod) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $nextId, $first, $last, $dob, $dod);
    }

    if ($stmt->execute() === TRUE) {
        $result = "New person record created successfully with ID: " . $nextId;
        $updateMaxPersonId = "UPDATE MaxPersonID SET id={$nextId};";
        if ($db->query($updateMaxPersonId) === FAlSE) {
            $result = "New person record created, but failed to update max person ID: " . $db->error;
        }
    } else {
        $result = "Failed to add new person record: " . $db->error;
    }
    $stmt->close();
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
        <li><a href="#">Add Actor/Director</a></li>
        <li><a href="./add_movie.php">Add Movie</a></li>
        <li><a href="./add_actor_to_movie.php">Add Actor to Movie</a></li>
        <li><a href="./add_director_to_movie.php">Add Director to Movie</a></li>
      </ul>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Add Person</h1>

          <h2 class="Add person">Add Actor/Director</h2>
          <span class="error">* Required Fields</span><br>
          <form method="POST">
              <label>Person Type<span class="error">* <?php echo $typeErr;?></span><br></label>
              <fieldset id="personType">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="personType" id="actor" value="Actor" <?php if (isset($_POST['personType']) && $_POST['personType'] == 'Actor')  echo ' checked="checked"';?>>
                    Actor
                  </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="personType" id="director" value="Director" <?php if (isset($_POST['personType']) && $_POST['personType'] == 'Director')  echo ' checked="checked"';?>>
                    Director
                  </label>
                </div>
              </fieldset>
              <div class="form-group">
              <label for="first">First Name<span class="error">* <?php echo $firstNameErr;?></span><br></label>
              <input type="text" class="form-control" name="first" id="first" placeholder="John" value="<?php echo isset($_POST['first']) ? $_POST['first'] : '' ?>">
              <label for="last">Last Name<span class="error">* <?php echo $lastNameErr;?></span><br></label>
              <input type="text" class="form-control" name="last" id="first" placeholder="Doe" value="<?php echo isset($_POST['last']) ? $_POST['last'] : '' ?>">
              <fieldset class="form-group">

              <label>Gender<span class="error">* <?php echo $sexErr;?></span><br></label>
              <fieldset id="sex">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sex" id="male" value="Male" <?php if (isset($_POST['sex']) && $_POST['sex'] == 'Male')  echo ' checked="checked"';?>>
                  Male
                </label>
              </div>
              <div class="form-check">
              <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sex" id="female" value="Female" <?php if (isset($_POST['sex']) && $_POST['sex'] == 'Female')  echo ' checked="checked"';?>>
                  Female
                </label>
              </div>
            </fieldset>

            <label for="dob">Date of Birth<span class="error">* <?php echo $dobErr;?></span><br></label><br>
            <input type="text" name="dob" id="datepicker1" placeholder="mm/dd/yyyy" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : '' ?>"><br>
            <label for="dod">Date of Death</label><br>
            <input type="text" name="dod" id="datepicker2" placeholder="mm/dd/yyyy" value="<?php echo isset($_POST['dod']) ? $_POST['dod'] : '' ?>"><br>
            <button type="submit" class="btn btn-success" >Add</button>
            <span><?php echo $result;?></span>
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
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>



  </body>
</html>
