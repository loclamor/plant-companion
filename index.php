<?php
require_once 'src/init.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link href="./custom.css" rel="stylesheet">
    <title><?= PlantCompanion::getInstance()->getTitle() ?></title>
    <link rel="icon" href="plant_companion_logo.png" />
    <link rel="manifest" href="manifest.json" />
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-light bg-light d-print-none">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="?">
	    	<img src="plant_companion_logo.png" alt="" height="24" class="d-inline-block align-text-top">
	    	Plant Companion
    	</a>
	    <?php if (PlantCompanion::getInstance()->getCurrentUser() !== false) { ?>
		    <form class="d-flex" method="POST" action="<?= PlantUrl::get('base', 'applyGroup') ?>">
		        <select class="form-select" name="selectedBaseListGroup">
					<?php foreach ($_SESSION['baseListGroup'] as $group) { ?>
						<option value="<?= $group->getId() ?>" <?= (int) $group->getId() === (int) $_SESSION['selectedBaseListGroup'] ? 'selected' : ''?>><?= $group->getName() ?></option>
					<?php } ?>
				</select>
				<input type="submit" value="Changer">
		    </form>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    
		    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link" href="<?= PlantUrl::get('vegetable', 'list', []) ?>">Plantes</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?= PlantUrl::get('action', 'list', []) ?>">Actions</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?= PlantUrl::get('photo', 'uploadMultipleV2', []) ?>"><span class="add_ico"><i class="bi bi-camera"></i></span></a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?= PlantUrl::get('calendar', 'fructification', []) ?>"><i class="bi bi-calendar-range"></i>&nbsp;Planning</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="<?= PlantUrl::get('print', 'bytype', []) ?>"><i class="bi bi-printer"></i></a>
		        </li>
		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            Paramétrage
		          </a>
		          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		            <li><a class="dropdown-item" href="<?= PlantUrl::get('type', 'list', []) ?>">Liste des types</a></li>
		            <li><a class="dropdown-item" href="<?= PlantUrl::get('portegreffe', 'list', []) ?>">Liste des portes-greffe</a></li>
		            <li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item" href="<?= PlantUrl::get('group', 'list', []) ?>">Liste des groupes</a></li>
		            <li><a class="dropdown-item" href="<?= PlantUrl::get('lieu', 'list', []) ?>">Liste des lieux</a></li>
		            <li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item" href="<?= PlantUrl::get('photo', 'list', []) ?>">Liste des photo</a></li>
	
		          </ul>
		        </li>
		      </ul>
		      <span class="navbar-text">
		      	 <a class="" href="<?= PlantUrl::get('login', 'logout', [], false) ?>"><i class="bi bi-person-x"></i>&nbsp;Se déconnecter</a>
	      	  </span>
		    </div>
		 <?php } ?>
	  </div>
	</nav>
  	<div class="container">
    	<?php echo PlantCompanion::getInstance()->getContent(); ?>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
    	// alert("width = " + screen.width + "x" + screen.height
    	// 	+ "\n DPR = " + window.devicePixelRatio
    	// 	+ "\n userAgent = " + navigator.userAgent);
    	//	document.getElementById('UserAgent').value = navigator.userAgent;
    </script>
  </body>
</html>