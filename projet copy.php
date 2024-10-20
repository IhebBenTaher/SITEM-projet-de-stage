<?php 
   session_start();
   if(!isset($_SESSION['username']))
   {
    header('Location: login.php');
   }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app gestion de projet";
$titredetail="";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id_projet, titre, description,type,nom,prenom,image FROM projet p,categorie c,employe e
where p.id_categorie=c.id_categorie and p.id_chef=e.id_employe";
$result = $conn->query($sql);
$sql = "SELECT * FROM categorie";
$resultcategorie = $conn->query($sql);
$resultcat = $conn->query($sql);
$sql = "SELECT * FROM employe where role='chef de projet'";
$resultchef = $conn->query($sql);
$resultche = $conn->query($sql);
$sql = "SELECT * FROM employe where role='employe'";
$resultemp = $conn->query($sql);
$resultempl = $conn->query($sql);
?>

<body>
<?php
require_once('header.php');
require_once('adminsidebar.php');
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Projets</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Projets</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <button id="show_form_projet" type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" style="margin-right: 30px;">+ Nouveau</button>
                </div>
                <div class="modal fade" id="Registration-Projet" tabindex="-1" data-bs-backdrop="false">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Nouveau projet</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="projets/insert.php" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                  <label for="inputText" class="col-sm-3 col-form-label">Titre</label>
                                  <div class="col-sm-9">
                                    <input name="titre" type="text" class="form-control" id="projetTitre">
                                  </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                      <textarea id="projetDescription" name="description" class="form-control" style="height: 100px"></textarea>
                                    </div>
                                  </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-3 col-form-label">Image</label>
                                    <div class="col-sm-9">
                                      <input name="image" class="form-control" type="file" id="image">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Catégorie</label>
                                    <div class="col-sm-9">
                                      <select name="categorie" id="projetCategorie" class="form-select" aria-label="Default select example">
                                      <?php if ($resultcategorie->num_rows > 0) {
                        while($row = $resultcategorie->fetch_assoc()) {
                          echo '<option value="'.$row["id_categorie"].'">'.$row["type"].'</option>';
                        }}?>
                                      </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Chef de projet</label>
                                    <div class="col-sm-9">
                                      <select name="chef" id="projetChef" class="form-select" aria-label="Default select example">
                                      <?php if ($resultchef->num_rows > 0) {
                        while($row = $resultchef->fetch_assoc()) {
                          echo '<option value="'.$row["id_employe"].'">'.$row["nom"].' '.$row["prenom"].'</option>';
                        }}?>
                                      </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Employés</label>
                                    <div class="col-sm-9">
                                      <select multiple name="chef" id="projetEmploye" class="form-select" aria-label="Default select example">
                                      <?php if ($resultemp->num_rows > 0) {
                        while($row = $resultemp->fetch_assoc()) {
                          echo '<option value="'.$row["id_employe"].'">'.$row["nom"].' '.$row["prenom"].'</option>';
                        }}?>
                                      </select>
                                    </div>
                                </div>
                                <button type="button" id="btn-register-projet" class="btn btn-primary">Ajouter</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="updateprojet" tabindex="-1" data-bs-backdrop="false">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Modifier projet <span id="up_idprojet"></span></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row mb-3">
                                  <label for="inputText" class="col-sm-3 col-form-label">Titre</label>
                                  <div class="col-sm-9">
                                    <input type="text" id="up_titreprojet" class="form-control">
                                  </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                      <textarea id="up_descriptionprojet" class="form-control" style="height: 100px"></textarea>
                                    </div>
                                  </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-3 col-form-label">Image</label>
                                    <div class="col-sm-9">
                                      <input id="up_imageprojet" class="form-control" type="file" id="formFile">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Catégorie</label>
                                    <div class="col-sm-9">
                                      <select id="up_categorieprojet" class="form-select" aria-label="Default select example">
                                        <?php if ($resultcat->num_rows > 0) {
                                          while($row = $resultcat->fetch_assoc()) {
                                            echo '<option value="'.$row["id_categorie"].'">'.$row["type"].'</option>';
                                          }}?>
                                      </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Chef de projet</label>
                                    <div class="col-sm-9">
                                      <select id="up_chefprojet" class="form-select" aria-label="Default select example">
                                        <?php if ($resultche->num_rows > 0) {
                        while($row = $resultche->fetch_assoc()) {
                          echo '<option value="'.$row["id_employe"].'">'.$row["nom"].' '.$row["prenom"].'</option>';
                        }}?>
                                      </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Employés</label>
                                    <div class="col-sm-9">
                                      <select multiple id="up_employeprojet" class="form-select" aria-label="Default select example">
                                        <?php if ($resultempl->num_rows > 0) {
                        while($row = $resultempl->fetch_assoc()) {
                          echo '<option value="'.$row["id_employe"].'">'.$row["nom"].' '.$row["prenom"].'</option>';
                        }}?>
                                      </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          <button id="btn_update_projet" type="button" class="btn btn-primary">Modifier</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="detailsprojet" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Détails du projet <span id="de_idprojet"></span> </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <img id="de_imageProjet" style="margin-bottom: 25px;width: 100px;height: 100px;" src="" alt="">
                          <div style="margin-bottom: 25px;">
                            <h6>Titre: </h6>
                            <span id="de_titreprojet"></span>
                          </div>
                          <div style="margin-bottom: 25px;">
                            <h6>Catégorie: </h6>
                            <span id="de_categorieprojet"></span>
                          </div>
                          <div style="margin-bottom: 25px;">
                            <h6>Description: </h6>
                            <span id="de_descriptionprojet"></span>
                          </div>
                          <div style="margin-bottom: 25px;">
                            <h6>Chef du projet: </h6>
                            <span id="de_chefprojet"></span>
                          </div>
                          <div style="margin-bottom: 25px;">
                            <h6>Employés: </h6>
                            <ul id="de_employeprojet"></ul>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="deleteProjet" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Supprimer projet</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Êtes-vous sûr de supprimer ce projet?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          <button id="btn_delete" type="button" class="btn btn-danger">Supprimer</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="card-body">
                  <h5 class="card-title">Liste des projets</h5>
                  <div class="table-responsive-xxl" id="table-projet-list"></div>
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                  <script src="myjs.js"></script>
                  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
                </div>
              </div>
            </div><!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>