<!DOCTYPE html>
<html lang="en">

<?php include "../function.php"; ?>


<head>
<title>InterTolls Main</Main>
</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">   
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<?php include "../navbar.php"; ?>



<br><br>
<div class="container-md">
<h4>Admin Endpoints</h4>
<br>
<p>Here lies a way to make admin API requests:</p>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
<div class="btn-group d-flex justify-content-center" role="group">
<button type="submit" class="btn btn-primary" name = "Endpoint" value="healthcheck">healthcheck</button>
<button type="submit" class="btn btn-primary" name = "Endpoint" value="resetoperators">resetoperators</button>
<button type="submit" class="btn btn-primary" name = "Endpoint" value="resetstations">resetstations</button>
<button type="submit" class="btn btn-primary" name = "Endpoint" value="resetvehicles">resetvehicles</button>
<button type="submit" class="btn btn-primary" name = "Endpoint" value="resetpasses">resetpasses</button>
<button type="submit" class="btn btn-primary" name = "Endpoint" value="fillpasses">fillpasses</button>
</div>
</form>
</div>
<?php
$host = setAPIName();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  if ($_POST["Endpoint"] == "healthcheck"){
    $response = sendrequest('http://'.$host.':9103/interoperability/api/admin/healthcheck', 'GET');
  }
  else{
    $endpoint = $_POST["Endpoint"];
    $response = sendrequest('http://'.$host.':9103/interoperability/api/admin/' . $endpoint, 'POST');
  }
  if ($response["code"] != 200){
    $json = json_decode($response["response"]);
      ?>
      <br><br>
      <div class="container-fluid  card d-flex justify-content-center">
      <div class="card-header d-flex justify-content-center">
      Response
      </div>
      <div class="card-body d-flex justify-content-center bg-danger text-white">
      Failed!
      </div>
  </div>
      <?php  
    }
    else{
  ?>
  <br><br>
  <div class="container-fluid card d-flex justify-content-center">
  <div class="card-header d-flex justify-content-center">
  Response
  </div>
  <div class="card-body d-flex justify-content-center bg-success text-white">
  Success!
      </div>
  </div>
  <?php
}
}
?>



</body>

<?php include "../footer.php"; ?>

</html>