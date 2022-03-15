<?php echo '<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
<div class="container-fluid">
<a class="navbar-brand" href="../index.php">
InterTolls
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent2">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item">
<a class="nav-link" aria-current="page" href="admin.php">Admin</a>
</li>
<li class="nav-item">
<a class="nav-link" href="settlements.php">Settlements</a>
</li>
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
Statistics
</a>
<ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
<li><a class="dropdown-item" href="passesalloc.php">Passes Allocation</a></li>
<li><a class="dropdown-item" href="passesperstation.php">Passes Per Station</a></li>
<li><a class="dropdown-item" href="awaypasses.php">Away Passes</a></li>
</ul>
</li>
</ul>
</div>
</div>
</nav>';
?>