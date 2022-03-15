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
<h4>Operator Settlements</h4>
<br>
<p>Make a Settlement. Choose operators and period of Settlement:</p>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
<div class="row">
<div class="col-sm">
<p>Choose Operator:</p>
<select class="form-select" name = "op1" id="op1">
<option value="aodos">AO</option>
<option value="gefyra">GE</option>
<option value="kentriki_odos">KO</option>
<option value="olympia_odos">OO</option>
<option value="egnatia_odos">EG</option>
<option value="moreas">MO</option>
<option value="nea_odos">NE</option>
</select>
</div>
</div>
<br>
<div class="row">
<div class="col-sm">
<p>Choose Operator:</p>
<select class="form-select" name = "op2" id="op2">
<option value="aodos">AO</option>
<option value="gefyra">GE</option>
<option value="kentriki_odos">KO</option>
<option value="olympia_odos">OO</option>
<option value="egnatia_odos">EG</option>
<option value="moreas">MO</option>
<option value="nea_odos">NE</option>
</select>
<br>
</div>
</div>
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
</div>



<?php
$host = setAPIName();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $flag = 0;
  if (empty($_POST["op1"])) {
    $flag = 1;
  } else {
    $op1 = $_POST["op1"];
  }
  if (empty($_POST["op2"])) {
    $flag = 1;
  } else {
    $op2 = $_POST["op2"];
  }
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
  if ($op1 == $op2) {
    $flag = 2;
  }
  if ($flag == 2){
    ?>
    <br><br>
    <div class="container-fluid  card d-flex justify-content-center">
    <div class="card-header d-flex justify-content-center">
    Response
    </div>
    <div class="card-body d-flex justify-content-center bg-danger text-white">
    An operator company cannot owe money to itself!
    </div>
    <?php  
  }
  
  else if ($flag == 1){
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
    <div class="card-body d-flex justify-content-center ">
    <?php  
    $response1 = sendrequest('http://'.$host.':9103/interoperability/api/PassesCost/'.$op1.'/'.$op2.'/'.$datefrom.'/'.$dateto, 'GET');
    $response2 = sendrequest('http://'.$host.':9103/interoperability/api/PassesCost/'.$op2.'/'.$op1.'/'.$datefrom.'/'.$dateto, 'GET');


    if (($response1["code"] != 200) || ($response2["code"] != 200)){
      ?>
      <div class="card-body d-flex justify-content-center bg-danger text-white">
      Error <?php echo $response1["code"];
      ?>, try another search!
      </div>
      <?php  
    }
    else{
    $json1 = json_decode($response1["response"]);
    $json2 = json_decode($response2["response"]);
    $diff = $json1->PassesCost - $json2->PassesCost;
    
    if ($diff >= 0){
      $opdeb = $op2;
      $opcred = $op1;
    }
    else {
      $opdeb = $op1;
      $opcred = $op2;
      $diff = $diff*(-1);
    }
    echo $op2." owes ".$op1." ".$json1->PassesCost."€ with ".$json1->NumberOfPasses." passes. <br>";
    echo $op1." owes ".$op2." ".$json2->PassesCost."€ with ".$json2->NumberOfPasses." passes. <br>";
    echo "For the period from ".$_POST["datefrom"]." to ".$_POST["datefrom"].", ".$opdeb." owes ".$opcred." ".$diff."€.";
    ?>
    </div>
    <div class = "container d-flex justify-content-center">
    
    <?php
    
    $response1 = sendrequest('http://'.$host.':9103/interoperability/api/PassesAnalysis/'.$op1.'/'.$op2.'/'.$datefrom.'/'.$dateto, 'GET');
    $response2 = sendrequest('http://'.$host.':9103/interoperability/api/PassesAnalysis/'.$op2.'/'.$op1.'/'.$datefrom.'/'.$dateto, 'GET');
    $json1 = json_decode($response1["response"]);
    $json2 = json_decode($response2["response"]);
    
    echo '<table class="table table-hover caption-top">
    <caption>Detailed View:</caption>
    <thead class = "table-primary">
    <tr>
    <th scope="col">Pass ID</th>
    <th scope="col">Home Operator</th>
    <th scope="col">Visiting Operator</th>
    <th scope="col">Timestamp</th>
    <th scope="col">Vehicle ID</th>
    <th scope="col">Charge</th>
    </tr>
    </thead>
    <tbody>';
    $offset = $json1->NumberOfPasses;
    foreach ($json1->PassesList as $key => $pass1) {
      
      echo     '<tr>
      <td>'.$pass1->PassID.'</td>
      <td>'.$op1.'</td>
      <td>'.$op2.'</td>
      <td>'.$pass1->TimeStamp.'</td>
      <td>'.$pass1->VehicleID.'</td>
      <td>'.$pass1->Charge.'€</td>
      </tr>';
    }
    foreach ($json2->PassesList as $key => $pass2) {
      
      echo     '<tr>
      <td>'.$pass2->PassID.'</td>
      <td>'.$op2.'</td>
      <td>'.$op1.'</td>
      <td>'.$pass2->TimeStamp.'</td>
      <td>'.$pass2->VehicleID.'</td>
      <td>'.$pass2->Charge.'€</td>
      </tr>';
    }
    echo '</tbody> </table> </div>';
  }
}
}
?>
</div>
</div>
<div class="container-fluid  card d-flex justify-content-center"></div>

</body>


<?php include "../footer.php"; ?>

</html>