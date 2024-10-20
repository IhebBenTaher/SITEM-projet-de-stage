<?php
require_once('db.php');
// Projet
function display_projet_record()
{
    global $conn;
    $value = '<table id="projet-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Image</th>
            <th class="border-top-0">Titre</th>
            <th class="border-top-0">Catégorie</th>
            <th class="border-top-0">Chef</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT p.id_projet, titre, description,type,nom,prenom,p.image FROM projet p,categorie c,employe e
where p.id_categorie=c.id_categorie and p.id_chef=e.id_employe";
    $result = mysqli_query($conn, $query);
    // $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_projet"] . '</td>
            <td><img src="images/'. $row["image"].'" alt="" style="width: 100px;height: 100px;"></td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row['type'] . '</td>
            <td>' . $row["nom"].' '. $row["prenom"] .'</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du projet" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-projet" data-id=' . $row['id_projet'] . '>
                    Details</button> 
                    <button type="button" title="Modifier le projet" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-projet" data-id2=' . $row['id_projet'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le projet" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-projet" data-id1=' . $row['id_projet'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertProjet()
{
    if(!isset($_FILES['image'])){
        echo "fail";return;
    }
    global $conn;
    $projetTitre = $_POST['projetTitre'];
    $projetDescription = $_POST['projetDescription'];
    $projetCategorie = $_POST['projetCategorie'];
    $projetEmploye = $_POST['projetEmploye'];
    $employeArray = explode(',', $projetEmploye);
    $projetChef = $_POST['projetChef'];
    $projetImage = time().$_FILES["image"]["name"];
    $image = $_FILES['image'];
    $uploadOk_image = 1;
    $emplacement_image = "images/";
    $size_image = $_FILES["image"]["size"];
    $file_image = $emplacement_image . basename($_FILES["image"]["name"]);
    $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));

        // if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        //     echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        //     $uploadOk_image = 0;
        // }  
    
    $sql = "INSERT INTO projet (titre,description,image,id_categorie,id_chef) VALUES ('$projetTitre', 
    '$projetDescription','$projetImage','$projetCategorie','$projetChef')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $id = mysqli_insert_id($conn);
        foreach ($employeArray as $value) {
            $query = "INSERT INTO projet_employe(id_projet,id_employe) values($id,$value)";
            $result = mysqli_query($conn, $query);
        }
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $emplacement_image .$projetImage);
        }
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_projet_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_ProjetID'];
    $query = "select image from projet WHERE id_projet= $Del_ID";
    $image="";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $image=$row["image"];
    }
    $query = "delete from projet WHERE id_projet = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        unlink('images/'.$image);
        echo "success";
    } else {
        echo "fail";
    }
}
function get_projet_record()
{
    global $conn;
    $ProjetID = $_POST['ProjetID'];
    $query = "SELECT p.id_projet, p.titre, p.description,c.type,nom,prenom,p.image,p.id_categorie,p.id_chef FROM projet p,categorie c,employe e
where p.id_projet=".$ProjetID." and p.id_categorie=c.id_categorie and p.id_chef=e.id_employe";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $projet_data = [];
        $projet_data[0] = $row['id_projet'];
        $projet_data[1] = $row['titre'];
        $projet_data[2] = $row['description'];
        $projet_data[3] = $row['type'];
        $projet_data[4] = $row['nom'];
        $projet_data[5] = $row['prenom'];  
        $projet_data[6] = $row['image'];
        $projet_data[7] = $row['id_categorie'];
        $projet_data[8] = $row['id_chef'];
    }
    echo json_encode($projet_data);
}
function get_projet_employes()
{
    global $conn;
    $ProjetID = $_POST['ProjetID'];
    $query = "SELECT e.id_employe,nom,prenom FROM employe e,projet_employe p where p.id_projet=$ProjetID and p.id_employe=e.id_employe";
    $result = mysqli_query($conn, $query);
    $projet_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projet_data[] = [
            'id' => $row['id_employe'],
            'nom' => $row['nom'] . " " . $row['prenom']
        ];
    }
    echo json_encode($projet_data);
}
function update_projet_value(){
    if($_POST['up_idprojet']==''||$_POST['up_projetTitre']==''||$_POST['up_descriptionprojet']==''){
        echo "fail";
        return;
    }
    global $conn;
    $projetId = $_POST['up_idprojet'];
    $projetTitre = $_POST['up_projetTitre'];
    $projetDescription = $_POST['up_descriptionprojet'];
    $projetCategorie = $_POST['up_categorieprojet'];
    $projetChef = $_POST['up_chefprojet'];
    $projetEmploye = $_POST['up_employeprojet'];
    $employeArray = explode(',', $projetEmploye);
    // $projetImage = $_FILES["up_imageprojet"];
    $uploadOk_image = 1;
    $imageprojetnom="";
    if(isset($_FILES["up_imageprojet"])){
        $imageprojetnom=time().$_FILES["up_imageprojet"]["name"];
        $emplacement_image = "images/";
        $size_image = $_FILES["up_imageprojet"]["size"];
        $file_image = $emplacement_image . basename($_FILES["up_imageprojet"]["name"]);
        $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));
        // if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        //     echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        //     $uploadOk_image = 0;
        // }  
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["up_imageprojet"]["tmp_name"], $emplacement_image .$imageprojetnom);
            $query = "select image from projet WHERE id_projet = $projetId";
            $result = mysqli_query($conn, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                unlink('images/'.$row["image"]);
            }
        }
    }
    $sql="";
    if(isset($_FILES["up_imageprojet"])){
        $sql = "update projet set titre='$projetTitre',description='$projetDescription',id_categorie=$projetCategorie,id_chef=$projetChef,image='".$imageprojetnom."' where id_projet=$projetId";
    }else{
        $sql = "update projet set titre='$projetTitre',description='$projetDescription',id_categorie=$projetCategorie,id_chef=$projetChef where id_projet=$projetId";
    }
    $result = mysqli_query($conn, $sql);
    $sql="delete from projet_employe where id_projet=$projetId";
    $result = mysqli_query($conn, $sql);
    foreach ($employeArray as $value) {
        $query = "INSERT INTO projet_employe(id_projet,id_employe) values($projetId,$value)";
        $result = mysqli_query($conn, $query);
    }
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
//Ressource
function display_resource_record(){
    global $conn;
    $value = '<table id="resource-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Type</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Date achat</th>
            <th class="border-top-0">Nombre unités</th>
            <th class="border-top-0">Prix unitaire</th>
            <th class="border-top-0">Nombre de mois</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_ressources,type,titre,date_achat,quantite,valeur,nbmois FROM ressources r,projet p,typesequipement t
where r.id_projet=p.id_projet and r.id_typesequipement=t.id_typesequipement";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_ressources"] . '</td>
            <td>' . $row["type"] . '</td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row['date_achat'] . '</td>
            <td>' . $row['quantite'] . '</td>
            <td>$' . $row["valeur"].'</td>
            <td>' . $row["nbmois"].'</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier la ressource" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-ressource" data-id2=' . $row['id_ressources'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer la ressource" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-ressource" data-id1=' . $row['id_ressources'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>'; 
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertRessource()
{
    global $conn;
    $ressourceType = $_POST['ressourceType'];
    $ressourceDate = $_POST['ressourceDate'];
    $ressourceProjet = $_POST['ressourceProjet'];
    $ressourcePrix = $_POST['ressourcePrix'];
    $ressourceNombreunites = $_POST['ressourceNombreunites'];
    $ressourceNbmois = $_POST['ressourceNbmois'];
    $sql = "INSERT INTO ressources (id_typesequipement,date_achat,id_projet,valeur,quantite,nbmois) VALUES ($ressourceType, 
    '$ressourceDate',$ressourceProjet,$ressourcePrix,$ressourceNombreunites,$ressourceNbmois)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
function delete_ressource_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_RessourceID'];
    $query = "delete from ressources 
            WHERE id_ressources = $Del_ID";
    $result = mysqli_query($conn, $query);
    // unlink('test.html');
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "<div class='text-echec'>$msg_delete_echec client !</div>";
    }
}
function get_ressource_record()
{
    global $conn;
    $RessourceID = $_POST['RessourceID'];
    $query = "SELECT id_ressources,r.id_typesequipement,type,r.id_projet,titre, nbmois,quantite, valeur,date_achat FROM ressources r,projet p,typesequipement t
where id_ressources=".$RessourceID." and r.id_projet=p.id_projet and r.id_typesequipement=t.id_typesequipement";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $ressource_data = [];
        $ressource_data[0] = $row['id_ressources'];
        $ressource_data[1] = $row['id_typesequipement'];
        $ressource_data[2] = $row['type'];
        $ressource_data[3] = $row['date_achat'];
        $ressource_data[4] = $row['id_projet'];
        $ressource_data[5] = $row['titre'];
        $ressource_data[6] = $row['nbmois'];
        $ressource_data[7] = $row['quantite'];
        $ressource_data[8] = $row['valeur'];
    }
    echo json_encode($ressource_data);
}
function update_ressource_value(){
    global $conn;
    $up_idressource = $_POST['up_idressource'];
    $up_typeressource = $_POST['up_typeressource'];
    $up_dateressource = $_POST['up_dateressource'];
    $up_projetressource = $_POST['up_projetressource'];
    $up_valeurressource = $_POST['up_valeurressource'];
    $up_nbunitesressource = $_POST['up_nbunitesressource'];
    $up_nbmoisressource = $_POST['up_nbmoisressource'];
    $sql = "update ressources set id_typesequipement='$up_typeressource',valeur=$up_valeurressource,id_projet=$up_projetressource,date_achat='$up_dateressource',quantite=$up_nbunitesressource,nbmois=$up_nbmoisressource where id_ressources=$up_idressource";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
// Ressource chef
function display_ressource_chef_record(){
    global $conn;
    $value = '<table id="ressource-chef-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Type</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Date achat</th>
            <th class="border-top-0">Nombre unités</th>
            <th class="border-top-0">Prix unitaire</th>
            <th class="border-top-0">Nombre de mois</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    session_start();
    $username=$_SESSION["username"];
    $query = "SELECT id_ressources,type,titre,date_achat,quantite,valeur,nbmois FROM ressources r,projet p,typesequipement t,employe e
where r.id_projet=p.id_projet and r.id_typesequipement=t.id_typesequipement and p.id_chef=e.id_employe and e.username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_ressources"] . '</td>
            <td>' . $row["type"] . '</td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row['date_achat'] . '</td>
            <td>' . $row['quantite'] . '</td>
            <td>$' . $row["valeur"].'</td>
            <td>' . $row["nbmois"].'</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier la ressource" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-ressource" data-id2=' . $row['id_ressources'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer la ressource" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-ressource" data-id1=' . $row['id_ressources'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>'; 
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
// Employe
function display_employe_record()
{
    global $conn;
    $value = '<table id="employe-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Image</th>
            <th class="border-top-0">Nom</th>
            <th class="border-top-0">Prénom</th>
            <th class="border-top-0">Username</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_employe,image,nom,prenom,username FROM employe where role='employe'";
    $result = mysqli_query($conn, $query);
    // $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_employe"] . '</td>
            <td><img src="images/employes/'. $row["image"].'" alt="" style="width: 100px;height: 100px;"></td>
            <td>' . $row["nom"]. '</td>
            <td>' . $row['prenom'] . '</td>
            <td>' . $row["username"] .'</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du employé" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-employe" data-id=' . $row['id_employe'] . '>
                    Details</button> 
                    <button type="button" title="Modifier le emplyé" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-employe" data-id2=' . $row['id_employe'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le employé" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-employe" data-id1=' . $row['id_employe'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertEmploye()
{
    if(!isset($_FILES['employeimage'])){
        echo "fail";return;
    }
    global $conn;
    $employenom = $_POST['employenom'];
    $employeprenom = $_POST['employeprenom'];
    $employeusername = $_POST['employeusername'];
    $employesalaire = $_POST["employesalaire"];
    $employeimage = $_FILES['employeimage'];
    //$employeimagenom = $_FILES['employeimage']['name'];
    $employeimagenom = time().$_FILES['employeimage']['name'];
    $uploadOk_image = 1;
    $emplacement_image = "images/employes/";
    $size_image = $_FILES["employeimage"]["size"];
    $file_image = $emplacement_image . basename($_FILES["employeimage"]["name"]);
    $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));
    if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_image = 0;
    }  
    
    $sql = "INSERT INTO employe (nom,prenom,username,salaire,role,image) VALUES ('$employenom', 
            '$employeprenom','$employeusername',$employesalaire,'employe','$employeimagenom')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["employeimage"]["tmp_name"], $emplacement_image .$employeimagenom);
        }
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_employe_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_EmployeID'];
    $query = "select image from employe WHERE id_employe = $Del_ID";
    $image="";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $image=$row["image"];
    }
    $query = "delete from employe WHERE id_employe = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        unlink('images/employes/'.$image);
        echo "success";
    } else {
        echo "fail";
    }
}
function get_employe_record()
{
    global $conn;
    $EmployeID = $_POST['EmployeID'];
    $query = "SELECT id_employe,nom,prenom,username,salaire,image FROM employe where id_employe=".$EmployeID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $employe_data = [];
        $employe_data[0] = $row['id_employe'];
        $employe_data[1] = $row['nom'];
        $employe_data[2] = $row['prenom'];
        $employe_data[3] = $row['username'];
        $employe_data[4] = $row['salaire'];  
        $employe_data[5] = $row['image'];
    }
    echo json_encode($employe_data);
}
function get_employe_projets()
{
    global $conn;
    $EmployeID = $_POST['EmployeID'];
    $query = "SELECT titre FROM projet p,projet_employe e where e.id_employe=$EmployeID and p.id_projet=e.id_projet";
    $result = mysqli_query($conn, $query);
    $projet_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projet_data[] =  $row['titre'];
    }
    echo json_encode($projet_data);
}
function update_employe_value(){
    if($_POST['up_idemploye']==''||$_POST['up_nomemploye']==''||$_POST['up_prenomemploye']==''||$_POST['up_usernameemploye']==''||$_POST['up_salaireemploye']==''){
        echo "fail";
        return;
    }
    global $conn;
    $up_idemploye = $_POST['up_idemploye'];
    $up_nomemploye = $_POST['up_nomemploye'];
    $up_prenomemploye = $_POST['up_prenomemploye'];
    $up_usernameemploye = $_POST['up_usernameemploye'];
    $up_salaireemploye = $_POST['up_salaireemploye'];
    // $up_imageemploye = $_FILES["up_imageemploye"];
    // $imageemploye = $_POST["up_imageemploye"];
    $imageemploye="";
    $uploadOk_image = 1;
    if(isset($_FILES["up_imageemploye"])){
        $imageemploye=time().$_FILES["up_imageemploye"]["name"];
        $emplacement_image = "images/employes/";
        $size_image = $_FILES["up_imageemploye"]["size"];
        $file_image = $emplacement_image . basename($_FILES["up_imageemploye"]["name"]);
        $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));
        // if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        //     echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        //     $uploadOk_image = 0;
        // }  
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["up_imageemploye"]["tmp_name"], $emplacement_image .$imageemploye);
            $query = "select image from employe WHERE id_employe = $up_idemploye";
            $result = mysqli_query($conn, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                unlink('images/employes/'.$row["image"]);
            }
        }
    }
    $sql="";
    if(isset($_FILES["up_imageemploye"])){
        $sql = "update employe set nom='$up_nomemploye',prenom='$up_prenomemploye',username='$up_usernameemploye',salaire=$up_salaireemploye,image='".$imageemploye."' where id_employe=$up_idemploye";
    }else{
        $sql = "update employe set nom='$up_nomemploye',prenom='$up_prenomemploye',username='$up_usernameemploye',salaire=$up_salaireemploye where id_employe=$up_idemploye";
    }
    $result = mysqli_query($conn, $sql);
   if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Chef
function display_chef_record()
{
    global $conn;
    $value = '<table id="chef-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Image</th>
            <th class="border-top-0">Nom</th>
            <th class="border-top-0">Prénom</th>
            <th class="border-top-0">Username</th>
            <th class="border-top-0">Salaire</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_employe,image,nom,prenom,username,salaire FROM employe where role='chef de projet'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_employe"] . '</td>
            <td><img src="images/employes/'. $row["image"].'" alt="" style="width: 100px;height: 100px;"></td>
            <td>' . $row["nom"]. '</td>
            <td>' . $row['prenom'] . '</td>
            <td>' . $row["username"] .'</td>
            <td>' . $row["salaire"] .'</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du chef" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-chef" data-id=' . $row['id_employe'] . '>
                    Details</button> 
                    <button type="button" title="Modifier le chef" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-chef" data-id2=' . $row['id_employe'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le chef" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-chef" data-id1=' . $row['id_employe'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertChef()
{
    if(!isset($_FILES['chefimage'])){
        echo "fail";return;
    }
    global $conn;
    $chefnom = $_POST['chefnom'];
    $chefprenom = $_POST['chefprenom'];
    $chefusername = $_POST['chefusername'];
    $chefsalaire = $_POST["chefsalaire"];
    $chefimage = $_FILES['chefimage'];
    $chefimagenom = time().$_FILES['chefimage']['name'];
    $uploadOk_image = 1;
    $emplacement_image = "images/employes/";
    $size_image = $_FILES["chefimage"]["size"];
    $file_image = $emplacement_image . basename($_FILES["chefimage"]["name"]);
    $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));
    if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_image = 0;
    }
    $sql = "INSERT INTO employe (nom,prenom,username,salaire,role,image) VALUES ('$chefnom', 
            '$chefprenom','$chefusername',$chefsalaire,'chef de projet','$chefimagenom')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["chefimage"]["tmp_name"], $emplacement_image .$chefimagenom);
        }
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_chef_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_ChefID'];
    $query = "select image from employe WHERE id_employe = $Del_ID";
    $image="";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $image=$row["image"];
    }
    $query = "delete from employe WHERE id_employe = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        unlink('images/employes/'.$image);
        echo "success";
    } else {
        echo "fail";
    }
}
function get_chef_record()
{
    global $conn;
    $ChefID = $_POST['ChefID'];
    $query = "SELECT id_employe,nom,prenom,username,salaire,image FROM employe where id_employe=".$ChefID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $chef_data = [];
        $chef_data[0] = $row['id_employe'];
        $chef_data[1] = $row['nom'];
        $chef_data[2] = $row['prenom'];
        $chef_data[3] = $row['username'];
        $chef_data[4] = $row['salaire'];  
        $chef_data[5] = $row['image'];
    }
    echo json_encode($chef_data);
}
function get_chef_projets()
{
    global $conn;
    $ChefID = $_POST['ChefID'];
    $query = "SELECT titre FROM projet where id_chef=".$ChefID;
    $result = mysqli_query($conn, $query);
    $chef_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $chef_data []= $row['titre'];
    }
    echo json_encode($chef_data);
}
function update_chef_value(){
    if($_POST['up_idchef']==''||$_POST['up_nomchef']==''||$_POST['up_prenomchef']==''||$_POST['up_usernamechef']==''||$_POST['up_salairechef']==''){
        echo "fail";
        return;
    }
    global $conn;
    $up_idchef = $_POST['up_idchef'];
    $up_nomchef = $_POST['up_nomchef'];
    $up_prenomchef = $_POST['up_prenomchef'];
    $up_usernamechef = $_POST['up_usernamechef'];
    $up_salairechef = $_POST['up_salairechef'];
    // $up_imageemploye = $_FILES["up_imageemploye"];
    // $imageemploye = $_POST["up_imageemploye"];
    // echo $imageemploye;
    $imagechefnom="";
    $uploadOk_image = 1;
    if(isset($_FILES["up_imagechef"])){
        $imagechefnom=time().$_FILES["up_imagechef"]["name"];
        $emplacement_image = "images/employes/";
        $size_image = $_FILES["up_imagechef"]["size"];
        $file_image = $emplacement_image . basename($_FILES["up_imagechef"]["name"]);
        $type_image = strtolower(pathinfo($file_image,PATHINFO_EXTENSION));
        // if($type_image != "jpg" && $type_image != "png" && $type_image != "jpeg" && $type_image != "gif" ) {
        //     echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        //     $uploadOk_image = 0;
        // }  
        if ($uploadOk_image != 0) {
            move_uploaded_file($_FILES["up_imagechef"]["tmp_name"], $emplacement_image .$imagechefnom);
            $query = "select image from employe WHERE id_employe = $up_idchef";
            $result = mysqli_query($conn, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                unlink('images/employes/'.$row["image"]);
            }
        }
    }
    $sql="";
    if(isset($_FILES["up_imagechef"])){
        $sql = "update employe set nom='$up_nomchef',prenom='$up_prenomchef',username='$up_usernamechef',salaire=$up_salairechef,image='".$imagechefnom."' where id_employe=$up_idchef";
    }else{
        $sql = "update employe set nom='$up_nomchef',prenom='$up_prenomchef',username='$up_usernamechef',salaire=$up_salairechef where id_employe=$up_idchef";
    }
    $result = mysqli_query($conn, $sql);
   if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Categorie
function display_categorie_record()
{
    global $conn;
    $value = '<table id="categorie-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Type</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_categorie,type FROM categorie";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_categorie"] . '</td>
            <td>' . $row["type"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du categorie" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-categorie" data-id=' . $row['id_categorie'] . '>
                    Details</button> 
                    <button type="button" title="Modifier le categorie" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-categorie" data-id2=' . $row['id_categorie'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le categorie" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-categorie" data-id1=' . $row['id_categorie'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertCategorie()
{
    global $conn;
    $categorietype = $_POST['categorietype'];
    $sql = "INSERT INTO categorie (type) VALUES ('$categorietype')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_categorie_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_CategorieID'];
    $query = "delete from categorie 
            WHERE id_categorie = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function get_categorie_record()
{
    global $conn;
    $CategorieID = $_POST['CategorieID'];

    $query = "SELECT id_categorie,type FROM categorie where id_categorie=".$CategorieID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $categorie_data = [];
        $categorie_data[0] = $row['id_categorie'];
        $categorie_data[1] = $row['type'];
    }
    echo json_encode($categorie_data);
}
function get_categorie_projets()
{
    global $conn;
    $CategorieID = $_POST['CategorieID'];
    $query = "SELECT titre FROM projet where id_categorie=".$CategorieID;
    $result = mysqli_query($conn, $query);
    $categorie_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categorie_data []= $row['titre'];
    }
    echo json_encode($categorie_data);
}
function update_categorie_value(){
    global $conn;
    $up_idcategorie = $_POST['up_idcategorie'];
    $up_typecategorie = $_POST['up_typecategorie'];
    $sql = "update categorie set type='$up_typecategorie'where id_categorie=$up_idcategorie";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Tache
function display_tache_record()
{
    global $conn;
    $value = '<table id="tache-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Titre</th>
            <th class="border-top-0">Deadline</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_tache,status,t.titre as titret,deadline,p.titre as titrep FROM tache t,projet p where p.id_projet=t.id_projet and t.id_employe is null";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_tache"] . '</td>
            <td>' . $row["titret"]. '</td>
            <td>' . $row["deadline"]. '</td>
            <td>' . $row["titrep"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du tache" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-tache" data-id=' . $row['id_tache'] . '>
                    Details</button>
                    <button type="button" title="Modifier la tache" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-tache" data-id2=' . $row['id_tache'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer la tache" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-tache" data-id1=' . $row['id_tache'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertTache()
{
    global $conn;
    $tachetitre = $_POST['tachetitre'];
    $tachedescription = $_POST['tachedescription'];
    $tachedeadline = $_POST['tachedeadline'];
    $tacheprojet = $_POST['tacheprojet'];
    $sql = "INSERT INTO tache (titre,description,deadline,id_projet) VALUES ('$tachetitre','$tachedescription','$tachedeadline','$tacheprojet')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_tache_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_TacheID'];
    $query = "update tache set status='Done'
            WHERE id_tache = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        session_start();
        echo $_SESSION["username"];
    } else {
        echo "fail";
    }
}
function delete_tache_admin_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_TacheID'];
    $query = "delete from tache WHERE id_tache = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        session_start();
        echo $_SESSION["username"];
    } else {
        echo "fail";
    }
}
function get_tache_record()
{
    global $conn;
    $TacheID = $_POST['TacheID'];

    $query = "SELECT id_tache,t.titre,t.description,deadline,t.id_projet,p.titre as titrep FROM tache t,projet p where id_tache=".$TacheID." and t.id_projet=p.id_projet";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tache_data = [];
        $tache_data[0] = $row['id_tache'];
        $tache_data[1] = $row['titre'];
        $tache_data[2] = $row['description'];
        $tache_data[3] = $row['deadline'];
        $tache_data[4] = $row['titrep'];
        $tache_data[5] = $row['id_projet'];
    }
    echo json_encode($tache_data);
}
function get_tache_status_record()
{
    global $conn;
    $TacheID = $_POST['TacheID'];
    $query = "SELECT status FROM tache where id_tache=$TacheID";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        echo $row['status'];
    }
}
function update_tache_value(){
    global $conn;
    $up_idtache = $_POST['up_idtache'];
    $up_titretache = $_POST['up_titretache'];
    $up_descriptiontache = $_POST['up_descriptiontache'];
    $up_deadlinetache = $_POST['up_deadlinetache'];
    $up_projettache = $_POST['up_projettache'];
    $sql = "update tache set titre='$up_titretache',description='$up_descriptiontache',deadline='$up_deadlinetache',id_projet=$up_projettache where id_tache=$up_idtache";
    $result = mysqli_query($conn, $sql);
   if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Local
function display_local_record()
{
    global $conn;
    $value = '<table id="local-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Adresse</th>
            <th class="border-top-0">Prix</th>
            <th class="border-top-0">Date achat</th>
            <th class="border-top-0">Nombre de mois</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_local,adresse,prix,dateachat,nbmois,titre from local l,projet p where l.id_projet=p.id_projet";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_local"] . '</td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row["adresse"]. '</td>
            <td>' . $row["prix"]. '</td>
            <td>' . $row["dateachat"]. '</td>
            <td>' . $row["nbmois"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier la local" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-local" data-id2=' . $row['id_local'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer la local" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-local" data-id1=' . $row['id_local'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertLocal()
{
    global $conn;
    $localadresse = $_POST['localadresse'];
    $localdate = $_POST['localdate'];
    $localprojet = $_POST['localprojet'];
    $localprix = $_POST['localprix'];
    $localnbmois = $_POST['localnbmois'];
    $sql = "INSERT INTO local (adresse,prix,dateachat,nbmois,id_projet) VALUES ('$localadresse',$localprix,'$localdate',$localnbmois,$localprojet)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
function delete_local_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_LocalID'];
    $query = "delete from local 
            WHERE id_local = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
function get_local_record()
{
    global $conn;
    $LocalID = $_POST['LocalID'];
    $query = "SELECT * FROM local where id_local=".$LocalID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $local_data = [];
        $local_data[0] = $row['id_local'];
        $local_data[1] = $row['adresse'];
        $local_data[2] = $row['prix'];
        $local_data[3] = $row['dateachat'];
        $local_data[4] = $row['nbmois'];
        $local_data[5] = $row['id_projet'];
    }
    echo json_encode($local_data);
}
function update_local_value(){
    global $conn;
    $up_idlocal = $_POST['up_idlocal'];
    $up_adresselocal = $_POST['up_adresselocal'];
    $up_prixlocal = $_POST['up_prixlocal'];
    $up_dateachatlocal = $_POST['up_dateachatlocal'];
    $up_nbmoislocal = $_POST['up_nbmoislocal'];
    $up_projetlocal = $_POST['up_projetlocal'];
    $sql = "update local set adresse='$up_adresselocal',prix=$up_prixlocal,dateachat='$up_dateachatlocal',nbmois=$up_nbmoislocal,id_projet=$up_projetlocal where id_local=$up_idlocal";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
// Local chef
function display_local_chef_record()
{
    global $conn;
    $value = '<table id="local-chef-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Adresse</th>
            <th class="border-top-0">Prix</th>
            <th class="border-top-0">Date achat</th>
            <th class="border-top-0">Nombre de mois</th>
        </tr>
    </thead>
    <tbody>';
    session_start();
    $username=$_SESSION["username"];
    $query = "SELECT id_local,adresse,prix,dateachat,nbmois,titre from local l,projet p,employe e where l.id_projet=p.id_projet and p.id_chef=e.id_employe and username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_local"] . '</td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row["adresse"]. '</td>
            <td>' . $row["prix"]. '</td>
            <td>' . $row["dateachat"]. '</td>
            <td>' . $row["nbmois"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier la local" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-local" data-id2=' . $row['id_local'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer la local" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-local" data-id1=' . $row['id_local'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
// Types equipement
function display_typesequipement_record()
{
    global $conn;
    $value = '<table id="typesequipement-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Type</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT * from typesequipement";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_typesequipement"] . '</td>
            <td>' . $row["type"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier le type d équipement" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-typesequipement" data-id2=' . $row['id_typesequipement'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le type d équipement" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-typesequipement" data-id1=' . $row['id_typesequipement'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertTypesequipement()
{
    global $conn;
    $typesequipementtype = $_POST['typesequipementtype'];
    $sql = "INSERT INTO typesequipement (type) VALUES ('$typesequipementtype')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_typesequipement_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_TypesequipementID'];
    $query = "delete from typesequipement WHERE id_typesequipement = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function get_typesequipement_record()
{
    global $conn;
    $TypesequipementID = $_POST['TypesequipementID'];
    $query = "SELECT * FROM typesequipement where id_typesequipement=".$TypesequipementID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $typesequipement_data = [];
        $typesequipement_data[0] = $row['id_typesequipement'];
        $typesequipement_data[1] = $row['type'];
    }
    echo json_encode($typesequipement_data);
}
function update_typesequipement_value(){
    global $conn;
    $up_idtypesequipement = $_POST['up_idtypesequipement'];
    $up_typetypesequipement = $_POST['up_typetypesequipement'];
    $sql = "update typesequipement set type='$up_typetypesequipement' where id_typesequipement=$up_idtypesequipement";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Types matiere
function display_typesmatiere_record()
{
    global $conn;
    $value = '<table id="typesmatiere-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">type</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT * from typesmatiere";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_typesmatiere"] . '</td>
            <td>' . $row["type"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier le type de matière première" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-typesmatiere" data-id2=' . $row['id_typesmatiere'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le type de matière première" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-typesmatiere" data-id1=' . $row['id_typesmatiere'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertTypesmatiere()
{
    global $conn;
    $typesmatieretype = $_POST['typesmatieretype'];
    $sql = "INSERT INTO typesmatiere (type) VALUES ('$typesmatieretype')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>console.log('cbon')</script>";
        echo "<div class='text-checked'>Le projet est inséré avec succès</div>";
    } else {
        echo "<div class='text-echec'>L'ajout du client a échoué</div>";
    }
}
function delete_typesmatiere_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_TypesmatiereID'];
    $query = "delete from typesmatiere WHERE id_typesmatiere = $Del_ID";
    $result = mysqli_query($conn, $query);
    // unlink('test.html');
    if ($result) {
        echo "<div class='text-checked'>Le client $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec client !</div>";
    }
}
function get_typesmatiere_record()
{
    global $conn;
    $TypesmatiereID = $_POST['TypesmatiereID'];
    $query = "SELECT * FROM typesmatiere where id_typesmatiere=".$TypesmatiereID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $typesmatiere_data = [];
        $typesmatiere_data[0] = $row['id_typesmatiere'];
        $typesmatiere_data[1] = $row['type'];
    }
    echo json_encode($typesmatiere_data);
}
function update_typesmatiere_value(){
    global $conn;
    $up_idtypesmatiere = $_POST['up_idtypesmatiere'];
    $up_typetypesmatiere = $_POST['up_typetypesmatiere'];
    $sql = "update typesmatiere set type='$up_typetypesmatiere' where id_typesmatiere=$up_idtypesmatiere";
    $result = mysqli_query($conn, $sql);
   if ($result) {
        echo "<div class='text-checked'>Le projet est inséré avec succès</div>";
    } else {
        echo "<div class='text-echec'>L'ajout du client a échoué</div>";
    }
}
// Profil
function get_profil_record()
{
    global $conn;
    $ProfilUsername = $_POST['ProfilUsername'];
    $query = "SELECT * FROM employe where username='$ProfilUsername'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $profil_data = [];
        $profil_data[0] = $row['nom'];
        $profil_data[1] = $row['prenom'];
        $profil_data[2] = $row['username'];
        $profil_data[3] = $row['role'];
        $profil_data[4] = $row['salaire'];
        $profil_data[5] = $row['image'];
    }
    echo json_encode($profil_data);
}
function update_profil_value(){
    global $conn;
    $up_profileusername = $_POST['up_profileusername'];
    $up_profilenom = $_POST['up_profilenom'];
    $up_profileprenom = $_POST['up_profileprenom'];
    $up_profileoldusername = $_POST['up_profileoldusername'];
    $sql = "update employe set username='$up_profileusername',nom='$up_profilenom',prenom='$up_profileprenom' where username='$up_profileoldusername'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        $_SESSION["username"]=$up_profileusername;
        echo "success";
    } else {
        echo "fail";
    }
}
function update_profil_password(){
    global $conn;
    $up_profileusername = $_POST['up_profileusername'];
    $currentPassword = $_POST['currentPassword'];
    $newpassword = $_POST['newpassword'];
    $renewpassword = $_POST['renewpassword'];
    if($newpassword==$renewpassword){
        $sql="select password from employe where username='$up_profileusername'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)>0){
            $p="";
            if ($row = mysqli_fetch_assoc($result)) {
                $p=$row["password"];
                if(password_verify($currentPassword, $p)){
                    $newpassword=password_hash($newpassword,PASSWORD_BCRYPT);
                    $sql="update employe set password='$newpassword' where username='$up_profileusername'";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                }else{
                    echo "fail";
                }
            }
        }else{
            echo "fail";
        }
    }else{
        echo "fail";
    }
}
function get_profil_header_record()
{
    global $conn;
    session_start();
    $ProfilUsername = $_SESSION['username'];
    $query = "SELECT * FROM employe where username='$ProfilUsername'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $profil_data = [];
        $profil_data[0] = $row['nom'];
        $profil_data[1] = $row['prenom'];
        $profil_data[2] = $row['username'];
        $profil_data[3] = $row['role'];
        $profil_data[4] = $row['salaire'];
        $profil_data[5] = $row['image'];
    }
    echo json_encode($profil_data);
}
// Tache chef
function display_tache_chef_record()
{
    global $conn;
    $username=$_POST['Username'];
    $value = '<table id="tache-chef-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Titre</th>
            <th class="border-top-0">Deadline</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_tache,status,t.titre as titret,deadline,p.titre as titrep FROM tache t,projet p,employe e where t.id_employe is NULL and p.id_projet=t.id_projet and p.id_chef=e.id_employe and e.username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_tache"] . '</td>
            <td>' . $row["titret"]. '</td>
            <td>' . $row["deadline"]. '</td>
            <td>' . $row["titrep"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du tache" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-chef-tache" data-id=' . $row['id_tache'] . '>
                    Details</button>
                    <button type="button" title="Supprimer la tache" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-chef-tache" data-id1=' . $row['id_tache'] . '>
                    Done</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function get_tache_chef_record()
{
    global $conn;
    $TacheChefID = $_POST['TacheChefID'];

    $query = "SELECT id_tache,t.titre,t.description,deadline,t.id_projet,p.titre as titrep FROM tache t,projet p where id_tache=".$TacheChefID." and t.id_projet=p.id_projet";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tache_data = [];
        $tache_data[0] = $row['id_tache'];
        $tache_data[1] = $row['titre'];
        $tache_data[2] = $row['description'];
        $tache_data[3] = $row['deadline'];
        $tache_data[4] = $row['titrep'];
        $tache_data[5] = $row['id_projet'];
    }
    echo json_encode($tache_data);
}
// Tache employe
function display_tache_employe_record()
{
    global $conn;
    $username=$_POST['Username'];
    $value = '<table id="tache-employe-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Titre</th>
            <th class="border-top-0">Deadline</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_tache,status,t.titre as titret,deadline,p.titre as titrep FROM tache t,projet p,employe e where p.id_projet=t.id_projet and t.id_employe=e.id_employe and e.username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_tache"] . '</td>
            <td>' . $row["titret"]. '</td>
            <td>' . $row["deadline"]. '</td>
            <td>' . $row["titrep"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du tache" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-employe-tache" data-id=' . $row['id_tache'] . '>
                    Details</button>
                    <button type="button" title="Supprimer la tache" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-employe-tache" data-id1=' . $row['id_tache'] . '>
                    Done</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function get_tache_employe_record()
{
    global $conn;
    $TacheEmployeID = $_POST['TacheEmployeID'];

    $query = "SELECT id_tache,t.titre,t.description,deadline,t.id_projet,p.titre as titrep FROM tache t,projet p where id_tache=".$TacheEmployeID." and t.id_projet=p.id_projet";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tache_data = [];
        $tache_data[0] = $row['id_tache'];
        $tache_data[1] = $row['titre'];
        $tache_data[2] = $row['description'];
        $tache_data[3] = $row['deadline'];
        $tache_data[4] = $row['titrep'];
        $tache_data[5] = $row['id_projet'];
    }
    echo json_encode($tache_data);
}
// Tache affect
function display_tache_affect_record()
{
    global $conn;
    session_start();
    $username=$_SESSION["username"];
    $value = '<table id="tache-employe-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Titre</th>
            <th class="border-top-0">Deadline</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Employé</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_tache,status,t.titre as titret,deadline,p.titre as titrep,e1.nom,e1.prenom FROM tache t,projet p,employe e,employe e1 where
    e.username='$username' and p.id_chef=e.id_employe and t.id_projet=p.id_projet and e1.id_employe=t.id_employe";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_tache"] . '</td>
            <td>' . $row["titret"]. '</td>
            <td>' . $row["deadline"]. '</td>
            <td>' . $row["titrep"]. '</td>
            <td>' . $row["nom"]." ".$row["prenom"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Détails du tache" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-detail-tache-affect" data-id=' . $row['id_tache'] . '>
                    Details</button>
                    <button type="button" title="Modifier la tache" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-tache-affect" data-id2=' . $row['id_tache'] . '>
                    Update</button>
                    <button type="button" title="Supprimer la tache" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-tache-affect" data-id1=' . $row['id_tache'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertTacheAffect()
{
    global $conn;
    $tacheaffecttitre = $_POST['tacheaffecttitre'];
    $tacheaffectdescription = $_POST['tacheaffectdescription'];
    $tacheaffectdeadline = $_POST['tacheaffectdeadline'];
    $tacheaffectprojet = $_POST['tacheaffectprojet'];
    $tacheaffectemploye = $_POST['tacheaffectemploye'];
    $sql = "INSERT INTO tache (titre,description,deadline,id_projet,id_employe) VALUES ('$tacheaffecttitre','$tacheaffectdescription','$tacheaffectdeadline','$tacheaffectprojet','$tacheaffectemploye')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function get_tache_affect_record(){
    global $conn;
    $TacheID = $_POST['TacheID'];
    $query = "SELECT id_tache, t.titre, t.description,deadline,p.titre as titrep,nom,prenom,t.id_projet,t.id_employe FROM tache t,projet p,employe e
where t.id_tache=".$TacheID." and t.id_projet=p.id_projet and t.id_employe=e.id_employe";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tache_affect_data = [];
        $tache_affect_data[0] = $row['id_tache'];
        $tache_affect_data[1] = $row['titre'];
        $tache_affect_data[2] = $row['description'];
        $tache_affect_data[3] = $row['deadline'];
        $tache_affect_data[4] = $row['titrep'];
        $tache_affect_data[5] = $row['nom'];
        $tache_affect_data[6] = $row['prenom'];
        $tache_affect_data[7] = $row['id_projet'];
        $tache_affect_data[8] = $row['id_employe'];
    }
    echo json_encode($tache_affect_data);   
}
function update_tache_affect_value(){
    global $conn;
    $up_idtacheaffect = $_POST['up_idtacheaffect'];
    $up_titretacheaffect = $_POST['up_titretacheaffect'];
    $up_descriptiontacheaffect = $_POST['up_descriptiontacheaffect'];
    $up_deadlinetacheaffect = $_POST['up_deadlinetacheaffect'];
    $up_projettacheaffect = $_POST['up_projettacheaffect'];
    $up_employetacheaffect = $_POST['up_employetacheaffect'];
    $sql = "update tache set titre='$up_titretacheaffect',description='$up_descriptiontacheaffect',deadline='$up_deadlinetacheaffect',id_employe=$up_employetacheaffect,id_projet=$up_projettacheaffect where id_tache=$up_idtacheaffect";
    $result = mysqli_query($conn, $sql);
   if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Conges employe
function display_conges_employe_record()
{
    global $conn;
    $value = '<table id="conges-employe-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date de début</th>
            <th class="border-top-0">Durée</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    session_start();
    $username=$_SESSION["username"];
    $query = "SELECT id_conges,duree,date_depart,status from conges c,employe e where c.id_employe=e.id_employe and username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_conges"] . '</td>
            <td>' . $row["date_depart"]. '</td>
            <td>' . $row["duree"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier le congès" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-conges-employe" data-id2=' . $row['id_conges'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le congès" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-conges-employe" data-id1=' . $row['id_conges'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertCongesEmploye()
{
    global $conn;
    $congesemployedate = $_POST['congesemployedate'];
    $congesemployeduree = $_POST['congesemployeduree'];
    session_start();
    $username=$_SESSION["username"];
    $sql = "select id_employe from employe where username='$username'";
    $result = mysqli_query($conn, $sql);
    $id=0;
    if ($row = mysqli_fetch_assoc($result)) {
        $id=$row["id_employe"];
    }
    $sql = "INSERT INTO conges (duree,date_depart,status,id_employe) VALUES ($congesemployeduree,'$congesemployedate','Pending',$id)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function get_conges_employe_record()
{
    global $conn;
    $CongesemployeID = $_POST['CongesemployeID'];
    $query = "SELECT * FROM conges where id_conges=".$CongesemployeID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $congesemploye_data = [];
        $congesemploye_data[0] = $row['id_conges'];
        $congesemploye_data[1] = $row['duree'];
        $congesemploye_data[2] = $row['date_depart'];
        $congesemploye_data[3] = $row['status'];
    }
    echo json_encode($congesemploye_data);
}
function update_conges_employe_value(){
    global $conn;
    $up_idcongesemploye = $_POST['up_idcongesemploye'];
    $up_datecongesemploye = $_POST['up_datecongesemploye'];
    $up_dureecongesemploye = $_POST['up_dureecongesemploye'];
    $sql = "update conges set date_depart='$up_datecongesemploye',duree=$up_dureecongesemploye where id_conges=$up_idcongesemploye";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_conges_employe_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_CongesID'];
    $query = "delete from conges WHERE id_conges = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Avances employe
function display_avances_employe_record()
{
    global $conn;
    $value = '<table id="avances-employe-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date</th>
            <th class="border-top-0">Valeur</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    session_start();
    $username=$_SESSION["username"];
    $query = "SELECT id_avance,valeur,date_demande,status from avance a,employe e where a.id_employe=e.id_employe and username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_avance"] . '</td>
            <td>' . $row["date_demande"]. '</td>
            <td>' . $row["valeur"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier l avance" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-avance-employe" data-id2=' . $row['id_avance'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer l avance" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-avance-employe" data-id1=' . $row['id_avance'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertAvanceEmploye()
{
    global $conn;
    $avanceemployedate = $_POST['avanceemployedate'];
    $avanceemployevaleur = $_POST['avanceemployevaleur'];
    session_start();
    $username=$_SESSION["username"];
    $sql = "select id_employe from employe where username='$username'";
    $result = mysqli_query($conn, $sql);
    $id=0;
    if ($row = mysqli_fetch_assoc($result)) {
        $id=$row["id_employe"];
    }
    $sql = "INSERT INTO avance (valeur,date_demande,status,id_employe) VALUES ($avanceemployevaleur,'$avanceemployedate','Pending',$id)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function get_avance_employe_record()
{
    global $conn;
    $AvanceemployeID = $_POST['AvanceemployeID'];
    $query = "SELECT * FROM avance where id_avance=".$AvanceemployeID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $avanceemploye_data = [];
        $avanceemploye_data[0] = $row['id_avance'];
        $avanceemploye_data[1] = $row['valeur'];
        $avanceemploye_data[2] = $row['date_demande'];
        $avanceemploye_data[3] = $row['status'];
    }
    echo json_encode($avanceemploye_data);
}
function update_avance_employe_value(){
    global $conn;
    $up_idavanceemploye = $_POST['up_idavanceemploye'];
    $up_dateavanceemploye = $_POST['up_dateavanceemploye'];
    $up_valeuravanceemploye = $_POST['up_valeuravanceemploye'];
    $sql = "update avance set date_demande='$up_dateavanceemploye',valeur=$up_valeuravanceemploye where id_avance=$up_idavanceemploye";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
function delete_avance_employe_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_AvanceID'];
    $query = "delete from avance WHERE id_avance = $Del_ID";
    $result = mysqli_query($conn, $query);
    // unlink('test.html');
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Conges
function display_conges_record()
{
    global $conn;
    $value = '<table id="conges-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Employé</th>
            <th class="border-top-0">Date de début</th>
            <th class="border-top-0">Durée</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_conges,duree,date_depart,status,nom,prenom from conges c,employe e where c.id_employe=e.id_employe";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_conges"] . '</td>
            <td>' . $row["nom"].' '. $row["prenom"] . '</td>
            <td>' . $row["date_depart"]. '</td>
            <td>' . $row["duree"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Approuver la demande" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-approve-conges" data-id=' . $row['id_conges'] . '>
                    Approve</button>
                    <button type="button" title="Refuser la demande" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-refuse-conges" data-id2=' . $row['id_conges'] . '>
                    Refuse</button> 
                    <button type="button" title="Supprimer la demande" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-conges" data-id1=' . $row['id_conges'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function update_conges_value(){
    global $conn;
    $CongesID = $_POST['CongesID'];
    $Status = $_POST['Status'];
    $sql = "update conges set status='$Status' where id_conges=$CongesID";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Avances
function display_avance_record()
{
    global $conn;
    $value = '<table id="avance-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Employé</th>
            <th class="border-top-0">Date</th>
            <th class="border-top-0">Valeur</th>
            <th class="border-top-0">Status</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_avance,valeur,date_demande,status,nom,prenom from avance a,employe e where a.id_employe=e.id_employe";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_avance"] . '</td>
            <td>' . $row["nom"].' '. $row["prenom"] . '</td>
            <td>' . $row["date_demande"]. '</td>
            <td>' . $row["valeur"]. '</td>
            <td>' . $row["status"]. '</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Approuver la demande" class="badge bg-success" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-approve-avance" data-id=' . $row['id_avance'] . '>
                    Approve</button>
                    <button type="button" title="Refuser la demande" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-refuse-avance" data-id2=' . $row['id_avance'] . '>
                    Refuse</button> 
                    <button type="button" title="Supprimer la demande" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-avance" data-id1=' . $row['id_avance'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function update_avance_value(){
    global $conn;
    $AvanceID = $_POST['AvanceID'];
    $Status = $_POST['Status'];
    $sql = "update avance set status='$Status' where id_avance=$AvanceID";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    } else {
        echo "fail";
    }
}
// Profit
function display_profit_record()
{
    global $conn;
    $value = '<table id="profit-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Mois</th>
            <th class="border-top-0">Année</th>
            <th class="border-top-0">Valeur</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT id_profit,mois,annee,valeur,titre from profit l,projet p where l.id_projet=p.id_projet";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_profit"] . '</td>
            <td>' . $row["titre"]. '</td>
            <td>' . $row["mois"]. '</td>
            <td>' . $row["annee"]. '</td>
            <td>' . $row["valeur"]. ' $</td>
            <td>
                <div class="btn-group" role="group">
                <!--<span class="badge bg-success"style="cursor: pointer;"data-bs-toggle="modal" data-bs-target="#detailsprojet">Details</span>-->
                    <button type="button" title="Modifier le profit" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-profit" data-id2=' . $row['id_profit'] . '>
                    Update</button>
                    <button type="button" title="Supprimer le profit" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-profit" data-id1=' . $row['id_profit'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>';
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
function InsertProfit()
{
    global $conn;
    $profitannee = $_POST['profitannee'];
    $profitprojet = $_POST['profitprojet'];
    $profitvaleur = $_POST['profitvaleur'];
    $profitmois = $_POST['profitmois'];
    $sql = "INSERT INTO profit (valeur,annee,mois,id_projet) VALUES ($profitvaleur,'$profitannee',$profitmois,$profitprojet)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
function delete_profit_record()
{
    global $conn;
    $Del_ID = $_POST['Delete_ProfitID'];
    $query = "delete from profit WHERE id_profit = $Del_ID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
function get_profit_record()
{
    global $conn;
    $ProfitID = $_POST['ProfitID'];
    $query = "SELECT * FROM profit where id_profit=".$ProfitID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $profit_data = [];
        $profit_data[0] = $row['id_profit'];
        $profit_data[1] = $row['mois'];
        $profit_data[2] = $row['annee'];
        $profit_data[3] = $row['id_projet'];
        $profit_data[4] = $row['valeur'];
    }
    echo json_encode($profit_data);
}
function update_profit_value(){
    global $conn;
    $up_idprofit = $_POST['up_idprofit'];
    $up_moisprofit = $_POST['up_moisprofit'];
    $up_valeurprofit = $_POST['up_valeurprofit'];
    $up_anneeprofit = $_POST['up_anneeprofit'];
    $up_projetprofit = $_POST['up_projetprofit'];
    $sql = "update profit set mois='$up_moisprofit',valeur=$up_valeurprofit,annee='$up_anneeprofit',id_projet=$up_projetprofit where id_profit=$up_idprofit";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        session_start();
        echo $_SESSION["role"];
    } else {
        echo "fail";
    }
}
// Profit chef
function display_profit_chef_record(){
    global $conn;
    $value = '<table id="profit-chef-list" class="datatable table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Projet</th>
            <th class="border-top-0">Mois</th>
            <th class="border-top-0">Année</th>
            <th class="border-top-0">Valeur</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    session_start();
    $username=$_SESSION["username"];
    $query = "SELECT id_profit,mois,titre,annee,valeur FROM profit r,projet p,employe e
where r.id_projet=p.id_projet and p.id_chef=e.id_employe and e.username='$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $row["id_profit"] . '</td>
            <td>' . $row["titre"] . '</td>
            <td>' . $row["mois"]. '</td>
            <td>' . $row['annee'] . '</td>
            <td>$' . $row["valeur"].'</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier le profit" class="badge bg-warning" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-edit-profit" data-id2=' . $row['id_profit'] . '>
                    Update</button> 
                    <button type="button" title="Supprimer le profit" class="badge bg-danger" style="font-size: 11px;margin:2px; border:0;padding:3px;" id="btn-delete-profit" data-id1=' . $row['id_profit'] . '>
                    Delete</button>
		        </div>
            </td>
        </tr>'; 
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}
// Statistiques projet
function display_projet_profit_record(){
    global $conn;
    $ProjetID=$_POST["ProjetID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT sum(valeur) as valeurs,annee,id_projet FROM profit where id_projet=$ProjetID group by(annee)";
        $result = mysqli_query($conn, $query);
        $profit_projet_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $profit_projet_data[] = [
                'annee' => $row['annee'],
                'valeurs' => $row['valeurs'] 
            ];
        }
        echo json_encode($profit_projet_data);
    }else{
        $query = "SELECT valeur,mois FROM profit where id_projet=$ProjetID and annee=$Annee";
        $result = mysqli_query($conn, $query);
        $profit_projet_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $profit_projet_data[] = [
                'annee' => $row['mois'],
                'valeurs' => $row['valeur'] 
            ];
        }
        echo json_encode($profit_projet_data);
    }
}
function display_projet_ressources_record(){
    global $conn;
    $ProjetID=$_POST["ProjetID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT valeur * quantite / nbmois AS valeurmois, date_achat,nbmois FROM ressources WHERE id_projet = $ProjetID";
        $result = mysqli_query($conn, $query);
        $ressources_projet_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['date_achat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $found = false;
                    foreach ($ressources_projet_data as &$item) {
                        if ($item->annee == $year) {
                            $item->valeurs += $valeurmois;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $obj = new stdClass();
                        $obj->annee = $year;
                        $obj->valeurs = $valeurmois;
                        $ressources_projet_data[] = $obj;
                    }
                }
            }
        }
        echo json_encode($ressources_projet_data);
    }
    else{
        $query = "SELECT valeur * quantite / nbmois AS valeurmois, date_achat,nbmois FROM ressources WHERE id_projet = $ProjetID";
        $result = mysqli_query($conn, $query);
        $ressources_projet_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['date_achat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $month = $current_date->format('m');
                    if ($year == $Annee) {
                        $found = false;
                        foreach ($ressources_projet_data as &$data) {
                            if ($data['annee'] == $month) {
                                $data['valeurs'] += $valeurmois;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $ressources_projet_data[] = ['annee' => $month, 'valeurs' => $valeurmois];
                        }
                    }
                }
            }
        }
        echo json_encode($ressources_projet_data);
    }
}
function display_projet_local_record(){
    global $conn;
    $ProjetID=$_POST["ProjetID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT prix / nbmois AS valeurmois, dateachat,nbmois FROM local WHERE id_projet = $ProjetID";
        $result = mysqli_query($conn, $query);
        $local_projet_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['dateachat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $found = false;
                    foreach ($local_projet_data as &$item) {
                        if ($item->annee == $year) {
                            $item->valeurs += $valeurmois;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $obj = new stdClass();
                        $obj->annee = $year;
                        $obj->valeurs = $valeurmois;
                        $local_projet_data[] = $obj;
                    }
                }
            }
        }
        echo json_encode($local_projet_data);
    }
    else{
        $query = "SELECT prix / nbmois AS valeurmois, dateachat,nbmois FROM local WHERE id_projet = $ProjetID";
        $result = mysqli_query($conn, $query);
        $local_projet_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['dateachat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $month = $current_date->format('m');
                    if ($year == $Annee) {
                        $found = false;
                        foreach ($local_projet_data as &$data) {
                            if ($data['annee'] == $month) {
                                $data['valeurs'] += $valeurmois;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $local_projet_data[] = ['annee' => $month, 'valeurs' => $valeurmois];
                        }
                    }
                }
            }
        }
        echo json_encode($local_projet_data);
    }
}
// Statistiques categorie
function display_categorie_profit_record(){
    global $conn;
    $CategorieID=$_POST["CategorieID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT sum(valeur) as valeurs,annee,id_categorie FROM profit,projet where profit.id_projet=projet.id_projet and id_categorie=$CategorieID group by(annee)";
        $result = mysqli_query($conn, $query);
        $profit_categorie_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $profit_categorie_data[] = [
                'annee' => $row['annee'],
                'valeurs' => $row['valeurs'] 
            ];
        }
        echo json_encode($profit_categorie_data);
    }else{
        $query = "SELECT sum(valeur) as valeurs,mois,id_categorie FROM profit,projet where profit.id_projet=projet.id_projet and id_categorie=$CategorieID and annee=$Annee group by(mois)";
        $result = mysqli_query($conn, $query);
        $profit_categorie_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $profit_categorie_data[] = [
                'annee' => $row['mois'],
                'valeurs' => $row['valeurs'] 
            ];
        }
        echo json_encode($profit_categorie_data);
    }
}
function display_categorie_ressources_record(){
    global $conn;
    $CategorieID=$_POST["CategorieID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT valeur * quantite / nbmois AS valeurmois, date_achat,nbmois FROM ressources r,projet p WHERE r.id_projet = p.id_projet and id_categorie=$CategorieID";
        $result = mysqli_query($conn, $query);
        $ressources_categorie_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['date_achat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $found = false;
                    foreach ($ressources_categorie_data as &$item) {
                        if ($item->annee == $year) {
                            $item->valeurs += $valeurmois;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $obj = new stdClass();
                        $obj->annee = $year;
                        $obj->valeurs = $valeurmois;
                        $ressources_categorie_data[] = $obj;
                    }
                }
            }
        }
        echo json_encode($ressources_categorie_data);
    }
    else{
        $query = "SELECT valeur * quantite / nbmois AS valeurmois, date_achat,nbmois FROM ressources r,projet p WHERE r.id_projet=p.id_projet and  id_categorie = $CategorieID";
        $result = mysqli_query($conn, $query);
        $ressources_categorie_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['date_achat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $month = $current_date->format('m');
                    if ($year == $Annee) {
                        $found = false;
                        foreach ($ressources_categorie_data as &$data) {
                            if ($data['annee'] == $month) {
                                $data['valeurs'] += $valeurmois;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $ressources_categorie_data[] = ['annee' => $month, 'valeurs' => $valeurmois];
                        }
                    }
                }
            }
        }
        echo json_encode($ressources_categorie_data);
    }
}
function display_categorie_local_record(){
    global $conn;
    $CategorieID=$_POST["CategorieID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT prix / nbmois AS valeurmois, dateachat,nbmois FROM local l,projet p WHERE l.id_projet = p.id_projet and id_categorie=$CategorieID";
        $result = mysqli_query($conn, $query);
        $local_categorie_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['dateachat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $found = false;
                    foreach ($local_categorie_data as &$item) {
                        if ($item->annee == $year) {
                            $item->valeurs += $valeurmois;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $obj = new stdClass();
                        $obj->annee = $year;
                        $obj->valeurs = $valeurmois;
                        $local_categorie_data[] = $obj;
                    }
                }
            }
        }
        echo json_encode($local_categorie_data);
    }
    else{
        $query = "SELECT prix / nbmois AS valeurmois, dateachat,nbmois FROM local l,projet p WHERE l.id_projet=p.id_projet and  id_categorie = $CategorieID";
        $result = mysqli_query($conn, $query);
        $local_categorie_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $valeurmois = $row['valeurmois'];
                $date_achat = $row['dateachat'];
                $nbmois = $row['nbmois'];
                $start_date = new DateTime($date_achat);
                for ($i = 0; $i < $nbmois; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i month");
                    $year = $current_date->format('Y');
                    $month = $current_date->format('m');
                    if ($year == $Annee) {
                        $found = false;
                        foreach ($local_categorie_data as &$data) {
                            if ($data['annee'] == $month) {
                                $data['valeurs'] += $valeurmois;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $local_categorie_data[] = ['annee' => $month, 'valeurs' => $valeurmois];
                        }
                    }
                }
            }
        }
        echo json_encode($local_categorie_data);
    }
}
function display_etape_record()
{
    $somme=$_POST["sommeetape"];
    $nbmois=$_POST["nbmoisetape"];
    echo $somme;
    echo $nbmois;
}
// Statistiques employe
function display_employe_conges_record(){
    global $conn;
    $EmployeID=$_POST["EmployeID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT date_depart,duree FROM conges where status='Approved' and id_employe=$EmployeID";
        $result = mysqli_query($conn, $query);
        $conges_employe_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $date_depart = $row['date_depart'];
                $duree = $row['duree'];
                $start_date = new DateTime($date_depart);
                for ($i = 0; $i < $duree; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i day");
                    $year = $current_date->format('Y');
                    $found = false;
                    foreach ($conges_employe_data as &$item) {
                        if ($item->annee == $year) {
                            $item->valeurs ++;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $obj = new stdClass();
                        $obj->annee = $year;
                        $obj->valeurs = 1;
                        $conges_employe_data[] = $obj;
                    }
                }
            }
        }
        echo json_encode($conges_employe_data);
    }else{
        $query = "SELECT date_depart,duree FROM conges where status='Approved' and id_employe=$EmployeID and year(date_depart)<=$Annee";
        $result = mysqli_query($conn, $query);
        $conges_employe_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $date_depart = $row['date_depart'];
                $duree = $row['duree'];
                $start_date = new DateTime($date_depart);
                for ($i = 0; $i < $duree; $i++) {
                    $current_date = clone $start_date;
                    $current_date->modify("+$i day");
                    $year = $current_date->format('Y');
                    if($year==$Annee){
                        $month = $current_date->format('m');
                        $found = false;
                        foreach ($conges_employe_data as &$item) {
                            if ($item->annee == $month) {
                                $item->valeurs ++;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $obj = new stdClass();
                            $obj->annee = $month;
                            $obj->valeurs = 1;
                            $conges_employe_data[] = $obj;
                        }
                    }
                }
            }
        }
        echo json_encode($conges_employe_data);
    }
}
function display_employe_avance_record(){
    global $conn;
    $EmployeID=$_POST["EmployeID"];
    $Annee=$_POST["Annee"];
    if($Annee=="all"){
        $query = "SELECT year(date_demande) as annee,sum(valeur) as valeurs FROM avance where status='Approved' and id_employe=$EmployeID group by(annee)";
        $result = mysqli_query($conn, $query);
        $avance_employe_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $obj = new stdClass();
                $obj->annee = $row["annee"];
                $obj->valeurs = $row["valeurs"];
                $avance_employe_data[] = $obj;
            }
        }
        echo json_encode($avance_employe_data);
    }else{
        $query = "SELECT month(date_demande) as annee,sum(valeur) as valeurs FROM avance where status='Approved' and year(date_demande)=$Annee and id_employe=$EmployeID group by(annee)";
        $result = mysqli_query($conn, $query);
        $avance_employe_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $obj = new stdClass();
                $obj->annee = $row["annee"];
                $obj->valeurs = $row["valeurs"];
                $avance_employe_data[] = $obj;
            }
        }
        echo json_encode($avance_employe_data);
    }
}
function display_id_employe_avance_record()
{
    global $conn;
    $ID = $_POST['ID'];
    $query = "SELECT id_employe FROM avance where id_avance=".$ID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['id_employe'];
    }
}
function display_id_employe_conges_record()
{
    global $conn;
    $ID = $_POST['ID'];
    $query = "SELECT id_employe FROM conges where id_conges=".$ID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['id_employe'];
    }
}
// notif taches
function display_taches_notif(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="employe"){
        $username=$_SESSION["username"];
        $query = "SELECT id_tache,t.titre,p.titre as titrep from tache t,projet p,employe e where t.id_projet=p.id_projet and t.id_employe=e.id_employe and e.username='$username'and receivevu='non' and status='Pending'";
        $result = mysqli_query($conn, $query);
        $notif_tache_employe_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_tache_employe_data[]=$row;
            }
        }
        echo json_encode($notif_tache_employe_data);
    }
    if($_SESSION["role"]=="chef de projet"){
        $username=$_SESSION["username"];
        $query = "SELECT id_tache,t.titre,p.titre as titrep from tache t,projet p,employe e where t.id_projet=p.id_projet and t.id_employe is null and e.username='$username' and p.id_chef=e.id_employe and receivevu='non' and status='Pending'";
        $result = mysqli_query($conn, $query);
        $notif_tache_chef_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_tache_chef_data[]=$row;
            }
        }
        echo json_encode($notif_tache_chef_data);
    }
    if($_SESSION["role"]=="admin"){
        $query = "SELECT id_tache,t.titre,p.titre as titrep from tache t,projet p where t.id_projet=p.id_projet and t.id_employe is null and sendvu='non' and status='Done'";
        $result = mysqli_query($conn, $query);
        $notif_tache_admin_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_tache_admin_data[]=$row;
            }
        }
        echo json_encode($notif_tache_admin_data);
    }
}
function update_tache_receivevu_data(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="employe"||$_SESSION["role"]=="chef de projet"){
        $TacheID=$_POST["TacheID"];
        $sql = "update tache set receivevu='oui' where id_tache=$TacheID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
    if($_SESSION["role"]=="admin"){
        $TacheID=$_POST["TacheID"];
        $sql = "update tache set sendvu='oui' where id_tache=$TacheID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
}
// notif avance
function display_avance_notif(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"||$_SESSION["role"]=="employe"){
        $username=$_SESSION["username"];
        $query = "SELECT id_avance,valeur,status from avance a,employe e where a.id_employe=e.id_employe and sendvu='non' and status in('Approved','Refused') and username='$username'";
        $result = mysqli_query($conn, $query);
        $notif_avance_chef_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_avance_chef_data[]=$row;
            }
        }
        echo json_encode($notif_avance_chef_data);
    }
    if($_SESSION["role"]=="admin"){
        $query = "SELECT id_avance,valeur,concat(nom,' ',prenom) as status from avance a,employe e where a.id_employe=e.id_employe and receivevu='non' and status='Pending'";
        $result = mysqli_query($conn, $query);
        $notif_avance_admin_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_avance_admin_data[]=$row;
            }
        }
        echo json_encode($notif_avance_admin_data);
    }
}
function get_avance_record()
{
    global $conn;
    $AvanceID = $_POST['AvanceID'];

    $query = "SELECT id_avance,nom,prenom,valeur,date_demande,status FROM avance a,employe e where a.id_employe=e.id_employe and id_avance=".$AvanceID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $avance_data = [];
        $avance_data[0] = $row['id_avance'];
        $avance_data[1] = $row['nom'];
        $avance_data[2] = $row['prenom'];
        $avance_data[3] = $row['valeur'];
        $avance_data[4] = $row['date_demande'];
        $avance_data[5] = $row['status'];
    }
    echo json_encode($avance_data);
}
function update_avance_receivevu_data(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"||$_SESSION["role"]=="employe"){
        $AvanceID=$_POST["AvanceID"];
        $sql = "update avance set sendvu='oui' where id_avance=$AvanceID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
    if($_SESSION["role"]=="admin"){
        $AvanceID=$_POST["AvanceID"];
        $sql = "update avance set receivevu='oui' where id_avance=$AvanceID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
}
// notif conges
function display_conges_notif(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"||$_SESSION["role"]=="employe"){
        $username=$_SESSION["username"];
        $query = "SELECT id_conges,duree,status from conges c,employe e where c.id_employe=e.id_employe and sendvu='non' and status in('Approved','Refused') and username='$username'";
        $result = mysqli_query($conn, $query);
        $notif_avance_chef_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_avance_chef_data[]=$row;
            }
        }
        echo json_encode($notif_avance_chef_data);
    }
    if($_SESSION["role"]=="admin"){
        $query = "SELECT id_conges,duree,concat(nom,' ',prenom) as status from conges c,employe e where c.id_employe=e.id_employe and receivevu='non' and status='Pending'";
        $result = mysqli_query($conn, $query);
        $notif_conges_admin_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_conges_admin_data[]=$row;
            }
        }
        echo json_encode($notif_conges_admin_data);
    }
}
function get_conges_record()
{
    global $conn;
    $CongesID = $_POST['CongesID'];
    $query = "SELECT id_conges,nom,prenom,duree,date_depart,status FROM conges c,employe e where c.id_employe=e.id_employe and id_conges=".$CongesID;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $conges_data = [];
        $conges_data[0] = $row['id_conges'];
        $conges_data[1] = $row['nom'];
        $conges_data[2] = $row['prenom'];
        $conges_data[3] = $row['duree'];
        $conges_data[4] = $row['date_depart'];
        $conges_data[5] = $row['status'];
    }
    echo json_encode($conges_data);
}
function update_conges_receivevu_data(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"||$_SESSION["role"]=="employe"){
        $CongesID=$_POST["CongesID"];
        $sql = "update conges set sendvu='oui' where id_conges=$CongesID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
    if($_SESSION["role"]=="admin"){
        $CongesID=$_POST["CongesID"];
        $sql = "update conges set receivevu='oui' where id_conges=$CongesID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
}
// notif taches affect
function display_taches_affect_notif(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"){
        $username=$_SESSION["username"];
        $query = "SELECT id_tache,t.titre,p.titre as titrep from tache t,projet p,employe e where t.id_projet=p.id_projet and t.id_employe is not null and sendvu='non' and status='Done' and id_chef=e.id_employe and username='$username'";
        $result = mysqli_query($conn, $query);
        $notif_tache_affcet_chef_data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notif_tache_affcet_chef_data[]=$row;
            }
        }
        echo json_encode($notif_tache_affcet_chef_data);
    }
}
function update_tache_affect_receivevu_data(){
    global $conn;
    session_start();
    if($_SESSION["role"]=="chef de projet"){
        $TacheID=$_POST["TacheID"];
        $sql = "update tache set sendvu='oui' where id_tache=$TacheID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "success";
        } else {
            echo "fail";
        }
    }
}
// 1000000
// publinet(2000)=>2TV 3PC TV:7500 PC:8000
// café(3000)=>2TV 1PC
// 2000x1+3000x2 max
// (2*7500+3*8000)x1+(2*7500+8000)x2<=1000000