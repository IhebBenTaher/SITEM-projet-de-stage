<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="index.html" class="logo d-flex align-items-center">
    <img src="assets/img/logo.png" alt="">
    <span class="d-none d-lg-block">ProAction</span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<!-- <div class="search-bar">
  <form class="search-form d-flex align-items-center" method="POST" action="#">
    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
  </form>
</div> -->
<!-- End Search Bar -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <!-- <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li> -->
    <!-- End Search Icon-->
    <li class="nav-item dropdown" id="tacheemployeaffectnotif">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-card-checklist"></i>
        <span class="badge bg-primary badge-number" id="nbtacheemployeaffectnotif"></span>
      </a>
      <ul id="tacheemployeaffectnotiflist" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
      </ul>
    </li>
    <li class="nav-item dropdown" id="tacheemployenotif">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-list-task"></i>
        <span class="badge bg-primary badge-number" id="nbtacheemployenotif"></span>
      </a>
      <ul id="tacheemployenotiflist" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
      </ul>
    </li>
    <li class="nav-item dropdown" id="avanceemployenotif">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-cash"></i>
        <span class="badge bg-primary badge-number" id="nbavanceemployenotif"></span>
      </a>
      <ul id="avanceemployenotiflist" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
      </ul>
    </li>
    <li class="nav-item dropdown" id="congesemployenotif">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-airplane"></i>
        <span class="badge bg-primary badge-number" id="nbcongesemployenotif"></span>
      </a>
      <ul id="congesemployenotiflist" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
      </ul>
    </li>
        <li class="nav-item dropdown pe-3">
      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img id="headerimageprofile" alt="Profile" class="rounded-circle">
        <span id="headerusernameprofile" class="d-none d-md-block dropdown-toggle ps-2">
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?php
            echo isset($_SESSION["username"])?$_SESSION["username"]:"";
        ?></h6>
          <span><?php
            echo isset($_SESSION["role"])?$_SESSION["role"]:"";
        ?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="profile.php">
            <i class="bi bi-person"></i>
            <span>Mon Profil</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="profile.php">
            <i class="bi bi-gear"></i>
            <span>Gérer mon profil</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Se déconnecter</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->
<!-- <script src="myjs.js"></script> -->
</header><!-- End Header -->
<div class="modal fade" id="detailsemployetachenotif" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de la tâche <span id="de_idemployetachenotif"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div style="margin-bottom: 25px;">
          <h6>Titre: </h6>
          <span id="de_titreemployetachenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Description: </h6>
          <span id="de_descriptionemployetachenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Deadline: </h6>
          <span id="de_deadlineemployetachenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Projet: </h6>
          <span id="de_projetemployetachenotif"></span>
        </div>
        <div id="dede_nomemployetachenotif" style="margin-bottom: 25px;">
          <h6>Employé: </h6>
          <span id="de_nomemployetachenotif"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detailsemployeavancenotif" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de l'avance <span id="de_idemployeavancenotif"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div style="margin-bottom: 25px;">
          <h6>Employé: </h6>
          <span id="de_nomemployeavancenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Valeur: </h6>
          <span id="de_valeuremployeavancenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Date: </h6>
          <span id="de_dateemployeavancenotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Status: </h6>
          <span id="de_statusemployeavancenotif"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detailsemployecongesnotif" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails du congès <span id="de_idemployecongesnotif"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div style="margin-bottom: 25px;">
          <h6>Employé: </h6>
          <span id="de_nomemployecongesnotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Durée: </h6>
          <span id="de_dureeemployecongesnotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Date: </h6>
          <span id="de_dateemployecongesnotif"></span>
        </div>
        <div style="margin-bottom: 25px;">
          <h6>Status: </h6>
          <span id="de_statusemployecongesnotif"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>