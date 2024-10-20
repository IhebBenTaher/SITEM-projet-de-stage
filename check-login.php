<?php 
    session_start();
    require_once('Gestion_location/db.php');
    global $conn;
    $username = $_POST['username']; 
    $password = $_POST['password'];
    $sql="select password,role,image,nom,prenom,salaire from employe where username='$username'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
        $p="";
        $r="";
        $i="";
        $nom="";
        $prenom="";
        $salaie="";
        if ($row = mysqli_fetch_assoc($result)) {
            $p=$row["password"];
            $r=$row["role"];
            $i=$row["image"];
            $nom=$row["nom"];
            $prenom=$row["prenom"];
            $salaie=$row["salaie"];
        }
        if($p!=""&&!password_verify($password, $p)){
            header('Location: login.php');
        }
        else{
            if($p==""){
                $password=password_hash($password,PASSWORD_BCRYPT);
                $sql="update employe set password='$password' where username='$username'";
                $result = mysqli_query($conn, $sql);
            }
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $r;
            $_SESSION['image'] = $i;
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['salaire'] = $salaire;
            if($r=='admin'){
                header('Location: profile.php');
            }else{
                if($r=="chef de projet"){
                    header('Location: profilechef.php');
                }else{
                    if($r=="employe"){
                        header('Location: profileemploye.php');
                    }else{
                        header('Location: login.html');
                    }
                }
            }
            
        }
    }else{
        header('Location: login.php');
    }
?>