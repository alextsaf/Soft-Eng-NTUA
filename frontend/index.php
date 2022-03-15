<!DOCTYPE html>
<html lang="en">

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

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        InterTolls
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent2">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="pages/admin.php">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/settlements.php">Settlements</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Statistics
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
              <li><a class="dropdown-item" href="pages/passesalloc.php">Passes Allocation</a></li>
              <li><a class="dropdown-item" href="pages/passesperstation.php">Passes Per Station</a></li>
              <li><a class="dropdown-item" href="pages/awaypasses.php">Away Passes</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>



<br><br>
    <div class="container">
        <h4>Welcome to InterTolls Web Application</h4>
        <p>InterTolls was built to handle and assisst the interoperability of the Greek tolls system.
        <p>The system extracts and proccesses pass data, calculates and handles the Settlements between the different road operators. It also provides various Statistics considering all the pass instances since 2019. </p>
        <p>Use our NavBar to explore the operations performed by the system</p>
        <br><br>
        <h4>About</h4>
        <p>Project by team TL21-39. Members: <br>
        • Tsafos Alexandros</p>
      </div>

</body>


<div class="container position-sticky">

  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <span class="text-muted ms-3">softeng | 2021-2022 | TL21-39</span>
      <li class="ms-3"><a class="text-muted" href="https://github.com/ntua/TL21-39"><i class="bi bi-github" role = "img" aria-label="GitHub"></i>GitHub</a></li>
    </ul>
  </footer>
</div>


</html>