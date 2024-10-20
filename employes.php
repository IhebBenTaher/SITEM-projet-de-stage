<?php 
   session_start();
   if(!isset($_SESSION['username'])||!isset($_SESSION['role'])||$_SESSION['role']!="admin"){
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
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "app gestion de projet";
// $titredetail="";
// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
// $projets=[];
// $sql = "SELECT id_projet, titre from projet";
// $result = $conn->query($sql);
// if ($result->num_rows > 0) {
//   while($row = $result->fetch_assoc()) {
//     $projets[] = ['id_projet' => $row["id_projet"], 'titre' => $row["titre"]];
//   }}
?>
<body>
<?php
require_once('header.php');
require_once('adminsidebar.php');
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Employés</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Home</a></li>
          <li class="breadcrumb-item active">Employés</li>
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
                      <button id="show_form_employe" type="button" class="btn btn-primary rounded-pill" style="margin-right: 30px;">+ Nouveau</button>
                  </div>
                  <div class="modal fade" id="addemploye" tabindex="-1" data-bs-backdrop="false">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Nouveau employé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <form>
                                  <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Nom</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="employenom">
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                      <label for="inputPassword" class="col-sm-3 col-form-label">Prénom</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control" id="employeprenom">
                                      </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputPassword" class="col-sm-3 col-form-label">Username</label>
                                        <div class="col-sm-9">
                                          <input type="text" class="form-control" id="employeusername">
                                        </div>
                                      </div>
                                  <div class="row mb-3">
                                      <label for="inputNumber" class="col-sm-3 col-form-label">Image</label>
                                      <div class="col-sm-9">
                                        <input class="form-control" type="file" id="employeimage" accept=".jpg, .jpeg, .png">
                                      </div>
                                  </div>
                                  
                                  <div class="row mb-3">
                                      <label class="col-sm-3 col-form-label">Salaire</label>
                                      <div class="col-sm-9">
                                        <input type="number" class="form-control" id="employesalaire">
                                      </div>
                                  </div>
                              </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button id="btn-register-employe" type="button" class="btn btn-primary">Ajouter</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal fade" id="updateemploye" tabindex="-1" data-bs-backdrop="false">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Modifier employé <span id="up_idemploye"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form>
                                <div class="row mb-3">
                                  <label for="inputText" class="col-sm-3 col-form-label">Nom</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" id="up_nomemploye">
                                  </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Prénom</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" id="up_prenomemploye">
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                      <label for="inputPassword" class="col-sm-3 col-form-label">Username</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control" id="up_usernameemploye">
                                      </div>
                                    </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-3 col-form-label">Image</label>
                                    <div class="col-sm-9">
                                      <input id="up_imageemploye" class="form-control" type="file" id="formFile" accept=".jpg, .jpeg, .png">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Salaire</label>
                                    <div class="col-sm-9">
                                      <input id="up_salaireemploye" type="number" class="form-control">
                                    </div>
                                </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary" id="btn_update_employe">Modifier</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal fade" id="detailsemploye" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Détails de l'employé <span id="de_idemploye"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                          <img id="de_imageemploye" style="margin-bottom: 25px;width: 100px;height: 100px;" src="" alt="">
                            <div style="margin-bottom: 25px;">
                              <h6>Nom: </h6>
                              <span id="de_nomemploye"></span>
                            </div>
                            <div style="margin-bottom: 25px;">
                              <h6>Prénom: </h6>
                              <span id="de_prenomemploye"></span>
                            </div>
                            <div style="margin-bottom: 25px;">
                              <h6>Username: </h6>
                              <span id="de_usernameemploye"></span>
                            </div>
                            <div style="margin-bottom: 25px;">
                              <h6>Salaire: </h6>
                              <span id="de_salaireemploye"></span>
                            </div>
                            <div style="margin-bottom: 25px;">
                              <h6>Projets: </h6>
                              <ul id="de_projetemploye"></ul>
                            </div>
                              <!-- <div style="margin-bottom: 25px;">
                                <h6>Avances de l'année: </h6>
                                <span>Avances de l'année1</span>
                              </div>
                              <div style="margin-bottom: 25px;">
                                <h6>Congès de l'année: </h6>
                                <span>Congès de l'année1</span>
                              </div> -->
                          </div>
                          <div class="modal-footer">
                            <button id="de_avanceemploye" type="button" class="btn btn-primary">Voir Avances</button>
                            <button id="de_congesemploye" type="button" class="btn btn-primary">Voir Congès</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal fade" id="deleteemploye" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Supprimer employé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Êtes-vous sûr de supprimer cet employé?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="btn_delete_employe" class="btn btn-danger">Supprimer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  <div class="modal fade" id="showcongesemploye" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Congès de l'employé <span id="de_idcongesemploye"></span></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <select class="form-select" id="de_anneecongesemploye">
                            <option value="all">all</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                          </select>
                          <canvas id="congesparemploye" style="max-height: 400px;"></canvas>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          <!-- <button id="btn_delete" type="button" class="btn btn-danger">Supprimer</button> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="showavanceemploye" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Avances de l'employé <span id="de_idavanceemploye"></span></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <select class="form-select" id="de_anneeavanceemploye">
                            <option value="all">all</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                          </select>
                          <canvas id="avanceparemploye" style="max-height: 400px;"></canvas>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                          <!-- <button id="btn_delete" type="button" class="btn btn-danger">Supprimer</button> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Liste des employés</h5>
                    <div class="table-responsive-xxl" id="table-employe-list"></div>
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