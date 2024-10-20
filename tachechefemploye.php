<?php 
   session_start();
   if(!isset($_SESSION['username'])||!isset($_SESSION['role'])||$_SESSION['role']!="chef de projet"){
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
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$projets=[];
$sql = "SELECT id_projet, titre from projet,employe where id_chef=id_employe and username='".$_SESSION["username"]."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $projets[] = ['id_projet' => $row["id_projet"], 'titre' => $row["titre"]];
  }}
?>
<body>
<?php
require_once('header.php');
require_once('chefsidebar.php');
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Tâches affectées</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Home</a></li>
          <li class="breadcrumb-item active">Tâches affectées</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Right side columns -->
        <div class="col-lg-12">
            <div class="row">
                <!-- Recent Sales -->
                <div class="col-12">
                  <div class="card recent-sales overflow-auto">
                    <div class="filter">
                        <button type="button" class="btn btn-primary rounded-pill" id="show_form_tache_affect" style="margin-right: 30px;">+ Nouveau</button>
                    </div>
                    <div class="modal fade" id="addtacheaffect" tabindex="-1" data-bs-backdrop="false">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Nouvelle tâche</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form>
                                <div class="row mb-3">
                                  <label for="inputText" class="col-sm-3 col-form-label">Titre</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tacheaffecttitre">
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label for="inputText" class="col-sm-3 col-form-label">Description</label>
                                  <div class="col-sm-9">
                                    <textarea style="height: 100px" class="form-control" id="tacheaffectdescription"></textarea>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-3 col-form-label">Deadline</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tacheaffectdeadline">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                      <label class="col-sm-3 col-form-label">Projet</label>
                                      <div class="col-sm-9">
                                        <select class="form-select" aria-label="Default select example" id="tacheaffectprojet">
                                        <?php
                                            foreach ($projets as $projet) {
                                              echo "<option value='".$projet['id_projet']."'>".$projet['titre']."</option>";
                                          }
                                            ?>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="row mb-3">
                                      <label class="col-sm-3 col-form-label">Employé</label>
                                      <div class="col-sm-9">
                                        <select class="form-select" aria-label="Default select example" id="tacheaffectemploye">
                                        </select>
                                      </div>
                                  </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              <button type="button" id="btn-register-tache-affect" class="btn btn-primary">Ajouter</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal fade" id="updatetacheaffect" tabindex="-1" data-bs-backdrop="false">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Modifier tâche <span id="up_idtacheaffect"></span></h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row mb-3">
                                      <label for="inputText" class="col-sm-3 col-form-label">Titre</label>
                                      <div class="col-sm-9">
                                        <input type="text" id="up_titretacheaffect" class="form-control">
                                      </div>
                                    </div>
                                    <div class="row mb-3">
                                      <label for="inputText" class="col-sm-3 col-form-label">Description</label>
                                      <div class="col-sm-9">
                                        <input type="text" id="up_descriptiontacheaffect" class="form-control">
                                      </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputNumber" class="col-sm-3 col-form-label">Deadline</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="up_deadlinetacheaffect">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">Projet</label>
                                        <div class="col-sm-9">
                                          <select class="form-select" aria-label="Default select example" id="up_projettacheaffect">
                                            <?php
                                                foreach ($projets as $projet) {
                                                  echo "<option value='".$projet['id_projet']."'>".$projet['titre']."</option>";
                                              }
                                            ?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">Employé</label>
                                        <div class="col-sm-9">
                                          <select class="form-select" aria-label="Default select example" id="up_employetacheaffect">
                                            
                                          </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              <button type="button" id="btn_update_tache_affect" class="btn btn-primary">Modifier</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal fade" id="detailstacheaffect" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Détails de la tâche <span id="de_idtacheaffect"></h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div style="margin-bottom: 25px;">
                                <h6>Titre: </h6>
                                <span id="de_titretacheaffect"></span>
                              </div>
                              <div style="margin-bottom: 25px;">
                                <h6>Description: </h6>
                                <span id="de_descriptiontacheaffect"></span>
                              </div>
                              <div style="margin-bottom: 25px;">
                                <h6>Deadline: </h6>
                                <span id="de_deadlinetacheaffect"></span>
                              </div>
                              <div style="margin-bottom: 25px;">
                                <h6>Projet: </h6>
                                <span id="de_projettacheaffect"></span>
                              </div>
                              <div style="margin-bottom: 25px;">
                                <h6>Employé: </h6>
                                <span id="de_employetacheaffect"></span>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal fade" id="deletetacheaffect" tabindex="-1">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Supprimer tâche</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              Êtes-vous sûr de supprimer cette tâche?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              <button id="btn_delete_tache_affect" type="button" class="btn btn-danger">Supprimer</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <div class="card-body">
                      <h5 class="card-title">Liste des tâches affectées</h5>
                      <div class="table-responsive-xxl" id="table-tache-affect-list"></div>
                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                      <script src="myjs.js"></script>
                      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                      <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
                    </div>
                  </div>
                </div><!-- End Recent Sales -->
              </div>
        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <!-- <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div> -->
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
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