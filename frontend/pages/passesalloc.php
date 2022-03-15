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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<?php include "../navbar.php"; ?>


<br><br>
<div class="container">
<h4>Passes Allocation</h4>
<p>Table and Pie Chart of how the passes are allocated, grouped by the road operators</p>
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
    
    $response = sendrequest('http://'.$host.':9103/interoperability/api/PassesAlloc/'.$datefrom.'/'.$dateto, 'GET');
    
    if ($response["code"] != 200){
      ?>
      <div class="container-fluid  card d-flex justify-content-center">
      <div class="card-header d-flex justify-content-center">
      Response
      </div>
      <div class="card-body d-flex justify-content-center bg-danger text-white">
      Error <?php echo $response["code"];?>, try another search!
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
      $json = json_decode($response["response"]);
      
      echo '<table class="table table-hover caption-top">
      <caption>Table:</caption>
      <thead class = "table-primary">
      <tr>
      <th scope="col">#</th>
      <th scope="col">Operator</th>
      <th scope="col">Number of Passes</th>
      </tr>
      </thead>
      <tbody>';
      $i = 0;
      $operators = ' ';
      $number = " ";
      foreach ($json->OPList as $key => $operator) {
        
        echo     '<tr>
        <th scope="row">'.$i.'</th>
        <td>'.$operator->Operator.'</td>
        <td>'.$operator->NumberOfPasses.'</td>
        </tr>';
        $i += 1;
        $operators = $operators.'"'.$operator->Operator.'",';  
        $number = $number." ".$operator->NumberOfPasses.",";  
      }
      $opList = substr($operators, 0, -1);
      $numberList = substr($number, 0, -1);
      
      echo '</tbody> </table> </div> <div class="card-body d-flex justify-content-center ">';
      echo '<canvas id="pieChart" style="max-width: 500px;"></canvas>';
      echo "<script>";
      echo "
      var ctxP = document.getElementById('pieChart').getContext('2d');
      var myPieChart = new Chart(ctxP, {
        type: 'pie',
        data: {
          labels: [" . $opList . "],
          datasets: [{
            data: [" . $numberList . "],
            backgroundColor: ['#F7464A', '#46BFBD', '#FDB45C', '#c54bc9', '#4D5360', '#32a852', '#a7c23a'],
            hoverBackgroundColor: ['#FF5A5E', '#5AD3D1', '#FFC870', '#d86bdb', '#616774', '#40c263', '#c8e35b']
          }]
        },
        options: {
          responsive: true
        }
      });
      ";
      echo '</script>';
      echo '</div>';
    }
  }
}
?>

</body>


<?php include "../footer.php"; ?>


</html>