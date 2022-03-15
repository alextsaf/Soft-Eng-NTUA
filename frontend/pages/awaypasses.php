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
<div class="container">
<h4>Away Passes Allocation</h4>
<p>Table of how the passes are allocated, grouped by the road operators</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
<div class="row">
<div class="col-sm">
Date From:<input class="container justify-content-center" type="date" id="datefrom" name="datefrom"></input>
</div>
<div class="col-sm">
Date To:<input class="container justify-content-center" type="date" id="dateto" name="dateto"></input>
</div>
</div>
<br>
<div class="container d-flex justify-content-center">
<button class="btn btn-primary" type="submit">Select</button>
</div>
</form>
</div>


<?php
$host = setAPIName();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $flag = 0;
  if (empty($_POST["datefrom"])) {
    $flag = 1;
  } else {
    $datefrom = $_POST["datefrom"];
    $datefromseperated = explode("-", $datefrom, 4);
    $datefrom = $datefromseperated[0].$datefromseperated[1].$datefromseperated[2];    
  }
  if (empty($_POST["dateto"])) {
    $flag = 1;
  } else {
    $dateto = $_POST["dateto"];
    $datetoseperated = explode("-", $dateto, 4);
    $dateto = $datetoseperated[0].$datetoseperated[1].$datetoseperated[2];
  }
  
  if ($flag == 1){
    ?>
    <br><br>
    <div class="container-fluid  card d-flex justify-content-center">
    <div class="card-header d-flex justify-content-center">
    Response
    </div>
    <div class="card-body d-flex justify-content-center bg-danger text-white">
    All Fields Required!
    </div>
    <?php  
  }
  
  else{
    ?>
    <br><br>
    <div class="container-fluid  card d-flex justify-content-center">
    <div class="card-header d-flex justify-content-center">
    Response
    </div>

    <?php 
    
    $operators = ['aodos', 'gefyra', 'kentriki_odos', 'olympia_odos', 'nea_odos', 'egnatia', 'moreas'];
    
 
    $printHeader = 1;
    foreach ($operators as $key => $value) {

    $response = sendrequest('http://'.$host.':9103/interoperability/api/Chargesby/'.$value.'/'.$datefrom.'/'.$dateto, 'GET');
    if ($response["code"] != 200){
      ?>
      <div class="card-body d-flex justify-content-center bg-danger text-white">
      Error <?php echo $response["code"];?>, try another search!
      </div>
      <?php 
      break; 
    }
    else{
    if ($printHeader == 1){
      echo '    <div class="card-body d-flex justify-content-center ">
      <table class="table table-hover">
      <thead class = "table-primary">
      <tr>
      <th scope="col">Home Operator</th>
      <th scope="col">Visiting Operator</th>
      <th scope="col">Number of Passes</th>
      <th scope="col">Total Cost</th>
      </tr>
      </thead>
      <tbody>';
      $printHeader = 0;
    }
    $json = json_decode($response["response"]);
    
    foreach ($json->PPOList as $key => $pass) {
      // echo $value;
      // $pass = json_decode($value);
      echo     '<tr>
      <td>'.$value.'</td>
      <td>'.$pass->VisitingOperator.'</td>
      <td>'.$pass->NumberOfPasses.'</td>
      <td>'.$pass->PassesCost.'€</td>
      </tr>';
    }
  }
}
    echo '</tbody> </table> </div>';
  }
}
?>

</body>


<?php include "../footer.php"; ?>


</html>