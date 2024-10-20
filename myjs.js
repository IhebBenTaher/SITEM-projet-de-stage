//Projets
var theusername;
view_projet_record();
insertProjetRecord();
delete_projet_record();
get_projet_record();
get_projet_toupdate();
update_client_record();
get_profit_projet_record();
get_ressources_projet_record();
get_local_projet_record();
function view_projet_record() {
    $.ajax({
      url: "viewprojet.php",
      method: "post",
      success: function (data) {
        try {
          data = $.parseJSON(data);
          if (data.status == "success") {
            $("#table-projet-list").html(data.html);
          }
        } catch (e) {
          console.error("Invalid Response!");
        }
      },
    });
  }
  function insertProjetRecord() {
    $(document).on("click", "#show_form_projet", function () {
      $("#Registration-Projet").modal("show");
    });
    $(document).on("click", "#btn-register-projet", function () {
      $("#Registration-Projet").scrollTop(0);
      let projetTitre = $("#projetTitre").val();
      let projetDescription = $("#projetDescription").val();
      let image = $("#image").prop("files")[0];
      let projetCategorie = $("#projetCategorie").val();
      let projetEmploye = $("#projetEmploye").val();
      let projetChef = $("#projetChef").val();
      let form_data = new FormData();
      form_data.append("projetTitre", projetTitre);
      form_data.append("projetDescription", projetDescription);
      form_data.append("image", image);
      form_data.append("projetCategorie", projetCategorie);
      form_data.append("projetEmploye", projetEmploye);
      form_data.append("projetChef", projetChef);
        $.ajax({
          url: "AjoutProjet.php",
          method: "post",
          processData: false,
          contentType: false,
          data: form_data,
          success: function (data) {
            if(data=="success"){
              $("#Registration-Projet").modal("hide");
              success();
              view_projet_record();
            }
            if(data=="fail"){
              fail();
            }
            $('#Registration-Projet').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
          },
        });
    });
    $('#Registration-Projet').on('hidden.bs.modal', function () {
      $(this).find('form').trigger('reset');
    });
  }
  function delete_projet_record() {
    let Delete_ID="";
    $(document).on("click", "#btn-delete-projet", function () {
      $("#deleteProjet").modal({backdrop: "static"});
      Delete_ID = $(this).attr("data-id1");
      console.log(Delete_ID);
      $("#deleteProjet").modal("show");
    });
    $(document).on("click", "#btn_delete_projet", function () {
      $.ajax({
        url: "delete_projet.php",
        method: "post",
        data: {
          Delete_ProjetID: Delete_ID
        },
        success: function (data) {
          console.log(data);
          if(data=="success"){
            $("#deleteProjet").modal("hide");
            success();
            view_projet_record();
          }
          if(data=="fail"){
            fail();
          }
            // $("#deleteProjet").modal("hide");
            // view_projet_record();
        },
      });
    });
  }
  function get_projet_record() {
    $(document).on("click", "#btn-detail-projet", function () {
      let ID = $(this).attr("data-id");
      $("#detailsprojet").modal("show");
      $.ajax({
        url: "get_projet_data.php",
        method: "post",
        data: {
          ProjetID: ID
        },
        dataType: "JSON",
        success: function (data) {
          let span = document.getElementById("de_idprojet");
          span.textContent = data[0];
          span = document.getElementById("de_titreprojet");
          span.textContent = data[1];
          span = document.getElementById("de_categorieprojet");
          span.textContent = data[3];
          span = document.getElementById("de_descriptionprojet");
          span.textContent = data[2];
          span = document.getElementById("de_chefprojet");
          span.textContent = data[4]+" "+data[5];
          span = document.getElementById("de_imageProjet");
          span.setAttribute("src","images/"+data[6]);
        },
      });
      $.ajax({
        url: "get_projet_employes.php",
        method: "post",
        data: {
          ProjetID: ID
        },
        dataType: "JSON",
        success: function (data) {
          console.log(data);
          ch="";
          data.forEach((element) => ch+="<li>"+element.nom+"</li>");
          let span = document.getElementById("de_employeprojet");
          span.innerHTML = ch;
        },
      });
    });
  }
  function get_projet_toupdate() {
    $(document).on("click", "#btn-edit-projet", function () {
      let ID = $(this).attr("data-id2");
      $("#updateprojet").modal("show");
      $.ajax({
        url: "get_projet_data.php",
        method: "post",
        data: {
          ProjetID: ID
        },
        dataType: "JSON",
        success: function (data) {
          let span = document.getElementById("up_idprojet");
          span.textContent = data[0];
          $("#up_titreprojet").val(data[1]);
          $("#up_descriptionprojet").val(data[2]);
          $("#up_categorieprojet").val(data[7]);
          $("#up_chefprojet").val(data[8]);
        },
      });
      $.ajax({
        url: "get_projet_employes.php",
        method: "post",
        data: {
          ProjetID: ID
        },
        dataType: "JSON",
        success: function (data) {
          let employes=data.map((e)=>e.id);
          $("#up_employeprojet").val(employes);
        },
      });
    });
  }
  function update_client_record() {
    $(document).on("click", "#btn_update_projet", function () {
      $("#updateprojet").scrollTop(0);
      let up_idprojet = document.getElementById("up_idprojet").textContent;
      let up_projetTitre = $("#up_titreprojet").val();
      let up_descriptionprojet = $("#up_descriptionprojet").val();
      let up_categorieprojet = $("#up_categorieprojet").val();
      let up_chefprojet = $("#up_chefprojet").val();
      let up_employeprojet = $("#up_employeprojet").val();
      let up_imageprojet = $("#up_imageprojet").prop("files")[0]==undefined?"":$("#up_imageprojet").prop("files")[0];
      let form_data = new FormData();
      form_data.append("up_idprojet", up_idprojet);
      form_data.append("up_projetTitre", up_projetTitre);
      form_data.append("up_descriptionprojet", up_descriptionprojet);
      form_data.append("up_categorieprojet", up_categorieprojet);
      form_data.append("up_chefprojet", up_chefprojet);
      form_data.append("up_employeprojet", up_employeprojet);
      form_data.append("up_imageprojet", up_imageprojet);
      $.ajax({
        url: "update_projet.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          console.log(data);
          if(data=="success"){
            $("#updateprojet").modal("hide");
            success();
            view_projet_record();
          }
          if(data=="fail"){
            fail();
          }
            // $("#updateprojet").modal("hide");
            // view_projet_record();
            $('#updateprojet').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
    });
  }
//Statistiques projet
function get_profit_projet_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_profitprojet", function () {
    text = document.getElementById("de_idprojet").textContent;
    $("#de_anneeprofitprojet").val("all");
    let Annee=$("#de_anneeprofitprojet").val();
    document.getElementById("de_idprofitprojet").textContent=text;
    $("#detailsprojet").modal("hide");
    $("#showprofitprojet").modal("show");
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_projet_profit_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#profitparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showprofitprojet').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeprofitprojet", function () {
    let year=$("#de_anneeprofitprojet").val();
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_projet_profit_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#profitparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_ressources_projet_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_ressourcesprojet", function () {
    text = document.getElementById("de_idprojet").textContent;
    $("#de_anneeressourcesprojet").val("all");
    let Annee=$("#de_anneeressourcesprojet").val();
    document.getElementById("de_idressourcesprojet").textContent=text;
    $("#detailsprojet").modal("hide");
    $("#showressourcesprojet").modal("show");
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_projet_ressources_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#ressourcesparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showressourcesprojet').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeressourcesprojet", function () {
    let year=$("#de_anneeressourcesprojet").val();
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_projet_ressources_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#ressourcesparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_local_projet_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_localprojet", function () {
    text = document.getElementById("de_idprojet").textContent;
    $("#de_anneelocalprojet").val("all");
    let Annee=$("#de_anneelocalprojet").val();
    document.getElementById("de_idlocalprojet").textContent=text;
    $("#detailsprojet").modal("hide");
    $("#showlocalprojet").modal("show");
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", Annee);
    // console.log(text+" "+Annee);
    $.ajax({
      url: "view_projet_local_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#localparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showlocalprojet').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneelocalprojet", function () {
    let year=$("#de_anneelocalprojet").val();
    let form_data = new FormData();
    form_data.append("ProjetID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_projet_local_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#localparprojet'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
//Ressources
view_ressource_record();
insertRessourceRecord();
delete_ressource_record();
// // get_projet_record();
get_ressource_toupdate();
update_ressource_record();
function view_ressource_record() {
  $.ajax({
    url: "viewressource.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-ressource-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertRessourceRecord() {
  $(document).on("click", "#show_form_ressource", function () {
    $("#addressource").modal("show");
  });
  $(document).on("click", "#btn-register-ressource", function () {
    $("#addressource").scrollTop(0);
    let ressourceType = $("#ressourceType").val();
    let ressourceDate = $("#ressourceDate").val();
    let ressourceProjet = $("#ressourceProjet").val();
    let ressourcePrix = $("#ressourcePrix").val();
    let ressourceNombreunites = $("#ressourceNombreunites").val();
    let ressourceNbmois = $("#ressourceNbmois").val();
    let form_data = new FormData();
    form_data.append("ressourceType", ressourceType);
    form_data.append("ressourceDate", ressourceDate);
    form_data.append("ressourceProjet", ressourceProjet);
    form_data.append("ressourcePrix", ressourcePrix);
    form_data.append("ressourceNombreunites", ressourceNombreunites);
    form_data.append("ressourceNbmois", ressourceNbmois);
      $.ajax({
        url: "AjoutRessource.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="admin"){
            $("#addressource").modal("hide");
            success();
            view_ressource_record();
          }
          if(data=="chef de projet"){
            $("#addressource").modal("hide");
            success();
            view_ressource_chef_record();
          }
          if(data=="fail"){
            fail();
          }
            $('#addressource').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function delete_ressource_record() {
  let Delete_ID ="";
  $(document).on("click", "#btn-delete-ressource", function () {
    $("#deleteressource").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deleteressource").modal("show");
  });
  $(document).on("click", "#btn_delete_ressource", function () {
      $.ajax({
        url: "delete_ressource.php",
        method: "post",
        data: {
          Delete_RessourceID: Delete_ID
        },
        success: function (data) {
            if(data=="admin"){
              $("#deleteressource").modal("hide");
              success();
              view_ressource_record();
            }
            if(data=="chef de projet"){
              $("#deleteressource").modal("hide");
              success();
              view_ressource_chef_record();
            }
            if(data=="fail"){
              fail();
            }
        },
      });
    });
}
function get_ressource_toupdate() {
  $(document).on("click", "#btn-edit-ressource", function () {
    let ID = $(this).attr("data-id2");
    $("#updateressource").modal("show");
    $.ajax({
      url: "get_ressource_data.php",
      method: "post",
      data: {
        RessourceID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idressource");
        span.textContent = data[0];
        $("#up_typeressource").val(data[1]);
        $("#up_dateressource").val(data[3]);
        $("#up_projetressource").val(data[4]);
        $("#up_valeurressource").val(data[8]);
        $("#up_nbunitesressource").val(data[7]);
        $("#up_nbmoisressource").val(data[6]);
      },
    });
  });
}
function update_ressource_record() {
  $(document).on("click", "#btn_update_ressource", function () {
    $("#updateressource").scrollTop(0);
    let up_idressource = document.getElementById("up_idressource").textContent;
    var up_typeressource = $("#up_typeressource").val();
    var up_dateressource = $("#up_dateressource").val();
    let up_projetressource = $("#up_projetressource").val();
    let up_valeurressource = $("#up_valeurressource").val();
    let up_nbunitesressource = $("#up_nbunitesressource").val();
    let up_nbmoisressource = $("#up_nbmoisressource").val();
    let form_data = new FormData();
    form_data.append("up_idressource", up_idressource);
    form_data.append("up_typeressource", up_typeressource);
    form_data.append("up_dateressource", up_dateressource);
    form_data.append("up_projetressource", up_projetressource);
    form_data.append("up_valeurressource", up_valeurressource);
    form_data.append("up_nbunitesressource", up_nbunitesressource);
    form_data.append("up_nbmoisressource", up_nbmoisressource);
    $.ajax({
      url: "update_ressource.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="admin"){
              $("#updateressource").modal("hide");
              success();
              view_ressource_record();
            }
            if(data=="chef de projet"){
              $("#updateressource").modal("hide");
              success();
              view_ressource_chef_record();
            }
            if(data=="fail"){
              fail();
            }
          $('#updateressource').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Ressources chef
view_ressource_chef_record();
function view_ressource_chef_record(){
  $.ajax({
    url: "viewressourcechef.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-ressource-chef-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
//Employes
view_employe_record();
insertEmployeRecord();
delete_employe_record();
get_employe_record();
get_employe_toupdate();
update_employe_record();
function view_employe_record() {
  $.ajax({
    url: "viewemploye.php",
    method: "post",
    success: function (data) {
      // try {
        
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-employe-list").html(data.html);
        }
      // } catch (e) {
      //   console.error("Invalid Response!");
      // }
    },
  });
}
function insertEmployeRecord() {
  $(document).on("click", "#show_form_employe", function () {
    $("#addemploye").modal("show");
  });
  $(document).on("click", "#btn-register-employe", function () {
    $("#addemploye").scrollTop(0);
    let employenom = $("#employenom").val();
    let employeprenom = $("#employeprenom").val();
    let employeimage = $("#employeimage").prop("files")[0];
    let employeusername = $("#employeusername").val();
    let employesalaire = $("#employesalaire").val();
    let form_data = new FormData();
    form_data.append("employenom", employenom);
    form_data.append("employeprenom", employeprenom);
    form_data.append("employeimage", employeimage);
    form_data.append("employeusername", employeusername);
    form_data.append("employesalaire", employesalaire);
      $.ajax({
        url: "AjoutEmploye.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addemploye").modal("hide");
            success();
            view_employe_record();
          }
          if(data=="fail"){
            fail();
          }
            $('#addemploye').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
  // $('#Registration-Projet').on('hidden.bs.modal', function () {
  //   $(this).find('form').trigger('reset');
  // });
}
function delete_employe_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-employe", function () {
    $("#deleteemploye").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deleteemploye").modal("show");
  });
  $(document).on("click", "#btn_delete_employe", function () {
      $.ajax({
        url: "delete_employe.php",
        method: "post",
        data: {
          Delete_EmployeID: Delete_ID
        },
        success: function (data) {
          if(data=="success"){
            $("#deleteemploye").modal("hide");
            success();
            view_employe_record();
          }
          if(data=="fail"){
            fail();
          }
        },
      });
    });
}
function get_employe_record() {
  $(document).on("click", "#btn-detail-employe", function () {
    let ID = $(this).attr("data-id");
    $("#detailsemploye").modal("show");
    $.ajax({
      url: "get_employe_data.php",
      method: "post",
      data: {
        EmployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idemploye");
        span.textContent = data[0];
        span = document.getElementById("de_nomemploye");
        span.textContent = data[1];
        span = document.getElementById("de_prenomemploye");
        span.textContent = data[2];
        span = document.getElementById("de_usernameemploye");
        span.textContent = data[3];
        span = document.getElementById("de_salaireemploye");
        span.textContent = data[4];
        span = document.getElementById("de_imageemploye");
        span.setAttribute("src","images/employes/"+data[5]);
      },
    });
    $.ajax({
      url: "get_employe_projets.php",
      method: "post",
      data: {
        EmployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        ch="";
        data.forEach((element) => ch+="<li>"+element+"</li>");
        let span = document.getElementById("de_projetemploye");
        span.innerHTML = ch;
      },
    });
  });
}
function get_employe_toupdate() {
  $(document).on("click", "#btn-edit-employe", function () {
    let ID = $(this).attr("data-id2");
    $("#updateemploye").modal("show");
    $.ajax({
      url: "get_employe_data.php",
      method: "post",
      data: {
        EmployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idemploye");
        span.textContent = data[0];
        $("#up_nomemploye").val(data[1]);
        $("#up_prenomemploye").val(data[2]);
        $("#up_usernameemploye").val(data[3]);
        $("#up_salaireemploye").val(data[4]);
      },
    });
  });
}
function update_employe_record() {
  $(document).on("click", "#btn_update_employe", function () {
    $("#updateemploye").scrollTop(0);
    let up_idemploye = document.getElementById("up_idemploye").textContent;
    let up_nomemploye = $("#up_nomemploye").val();
    let up_prenomemploye = $("#up_prenomemploye").val();
    let up_usernameemploye = $("#up_usernameemploye").val();
    let up_salaireemploye = $("#up_salaireemploye").val();
    let up_imageemploye = $("#up_imageemploye").prop("files")[0]==undefined?"":$("#up_imageemploye").prop("files")[0];
    let form_data = new FormData();
    form_data.append("up_idemploye", up_idemploye);
    form_data.append("up_nomemploye", up_nomemploye);
    form_data.append("up_prenomemploye", up_prenomemploye);
    form_data.append("up_usernameemploye", up_usernameemploye);
    form_data.append("up_salaireemploye", up_salaireemploye);
    form_data.append("up_imageemploye", up_imageemploye);
    $.ajax({
      url: "update_employe.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updateemploye").modal("hide");
          success();
          view_employe_record();
        }
        if(data=="fail"){
          fail();
        }
          $('#updateemploye').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Chefs
view_chef_record();
insertChefRecord();
delete_chef_record();
get_chef_record();
get_chef_toupdate();
update_chef_record();
function view_chef_record() {
  $.ajax({
    url: "viewchef.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-chef-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertChefRecord() {
  $(document).on("click", "#show_form_chef", function () {
    $("#addchef").modal("show");
  });
  $(document).on("click", "#btn-register-chef", function () {
    $("#addchef").scrollTop(0);
    let chefnom = $("#chefnom").val();
    let chefprenom = $("#chefprenom").val();
    let chefimage = $("#chefimage").prop("files")[0];
    let chefusername = $("#chefusername").val();
    let chefsalaire = $("#chefsalaire").val();
    let form_data = new FormData();
    form_data.append("chefnom", chefnom);
    form_data.append("chefprenom", chefprenom);
    form_data.append("chefimage", chefimage);
    form_data.append("chefusername", chefusername);
    form_data.append("chefsalaire", chefsalaire);
    $.ajax({
      url: "AjoutChef.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#addchef").modal("hide");
          success();
          view_chef_record();
        }
        if(data=="fail"){
          fail();
        }
        // $("#addchef").modal("hide");
        // view_chef_record();
        $('#addchef').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
        });
      },
    });
  });
}
function delete_chef_record() {
  let Delete_ID ="";
  $(document).on("click", "#btn-delete-chef", function () {
    $("#deletechef").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deletechef").modal("show");
  });
  $(document).on("click", "#btn_delete_chef", function () {
    $.ajax({
      url: "delete_chef.php",
      method: "post",
      data: {
        Delete_ChefID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deletechef").modal("hide");
          success();
          view_chef_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function get_chef_record() {
  $(document).on("click", "#btn-detail-chef", function () {
    let ID = $(this).attr("data-id");
    $("#detailschef").modal("show");
    $.ajax({
      url: "get_chef_data.php",
      method: "post",
      data: {
        ChefID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idchef");
        span.textContent = data[0];
        span = document.getElementById("de_nomchef");
        span.textContent = data[1];
        span = document.getElementById("de_prenomchef");
        span.textContent = data[2];
        span = document.getElementById("de_usernamechef");
        span.textContent = data[3];
        span = document.getElementById("de_salairechef");
        span.textContent = data[4];
        span = document.getElementById("de_imagechef");
        span.setAttribute("src","images/employes/"+data[5]);
      },
    });
    $.ajax({
      url: "get_chef_projets.php",
      method: "post",
      data: {
        ChefID: ID
      },
      dataType: "JSON",
      success: function (data) {
        ch="";
        data.forEach((element) => ch+="<li>"+element+"</li>");
        let span = document.getElementById("de_projetchef");
        span.innerHTML = ch;
      },
    });
  });
}
function get_chef_toupdate() {
  $(document).on("click", "#btn-edit-chef", function () {
    let ID = $(this).attr("data-id2");
    $("#updatechef").modal("show");
    $.ajax({
      url: "get_chef_data.php",
      method: "post",
      data: {
        ChefID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idchef");
        span.textContent = data[0];
        $("#up_nomchef").val(data[1]);
        $("#up_prenomchef").val(data[2]);
        $("#up_usernamechef").val(data[3]);
        $("#up_salairechef").val(data[4]);
      },
    });
  });
}
function update_chef_record() {
  $(document).on("click", "#btn_update_chef", function () {
    $("#updatechef").scrollTop(0);
    let up_idchef = document.getElementById("up_idchef").textContent;
    let up_nomchef = $("#up_nomchef").val();
    let up_prenomchef = $("#up_prenomchef").val();
    let up_usernamechef = $("#up_usernamechef").val();
    let up_salairechef = $("#up_salairechef").val();
    let up_imagechef = $("#up_imagechef").prop("files")[0]==undefined?"":$("#up_imagechef").prop("files")[0];
    let form_data = new FormData();
    form_data.append("up_idchef", up_idchef);
    form_data.append("up_nomchef", up_nomchef);
    form_data.append("up_prenomchef", up_prenomchef);
    form_data.append("up_usernamechef", up_usernamechef);
    form_data.append("up_salairechef", up_salairechef);
    form_data.append("up_imagechef", up_imagechef);
    $.ajax({
      url: "update_chef.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatechef").modal("hide");
          success();
          view_chef_record();
        }
        if(data=="fail"){
          fail();
        }
          $('#updatechef').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Categories
view_categorie_record();
insertCategorieRecord();
delete_categorie_record();
get_categorie_record();
get_categorie_toupdate();
update_categorie_record();
function view_categorie_record() {
  $.ajax({
    url: "viewcategorie.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-categorie-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertCategorieRecord() {
  $(document).on("click", "#show_form_categorie", function () {
    $("#addcategorie").modal("show");
  });
  $(document).on("click", "#btn-register-categorie", function () {
    $("#addcategorie").scrollTop(0);
    let categorietype = $("#categorietype").val();
    let form_data = new FormData();
    form_data.append("categorietype", categorietype);
      $.ajax({
        url: "AjoutCategorie.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addcategorie").modal("hide");
            success();
            view_categorie_record();
          }
          if(data=="fail"){
            fail();
          }
            // $("#addcategorie").modal("hide");
            // view_categorie_record();
            $('#addcategorie').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function delete_categorie_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-categorie", function () {
    $("#deletecategorie").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deletecategorie").modal("show");
  });
  $(document).on("click", "#btn_delete_categorie", function () {
    $.ajax({
      url: "delete_categorie.php",
      method: "post",
      data: {
        Delete_CategorieID: Delete_ID
      },
      success: function (data) {
        console.log(data);
        if(data=="success"){
          $("#deletecategorie").modal("hide");
          success();
          view_categorie_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function get_categorie_record() {
  $(document).on("click", "#btn-detail-categorie", function () {
    let ID = $(this).attr("data-id");
    $("#detailscategorie").modal("show");
    $.ajax({
      url: "get_categorie_data.php",
      method: "post",
      data: {
        CategorieID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idcategorie");
        span.textContent = data[0];
        span = document.getElementById("de_typecategorie");
        span.textContent = data[1];
      },
    });
    $.ajax({
      url: "get_categorie_projets.php",
      method: "post",
      data: {
        CategorieID: ID
      },
      dataType: "JSON",
      success: function (data) {
        ch="";
        data.forEach((element) => ch+="<li>"+element+"</li>");
        let span = document.getElementById("de_projetcategorie");
        span.innerHTML = ch;
      },
    });
  });
}
function get_categorie_toupdate() {
  $(document).on("click", "#btn-edit-categorie", function () {
    let ID = $(this).attr("data-id2");
    $("#updatecategorie").modal("show");
    $.ajax({
      url: "get_categorie_data.php",
      method: "post",
      data: {
        CategorieID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idcategorie");
        span.textContent = data[0];
        $("#up_typecategorie").val(data[1]);
      },
    });
  });
}
function update_categorie_record() {
  $(document).on("click", "#btn_update_categorie", function () {
    $("#updatecategorie").scrollTop(0);
    let up_idcategorie = document.getElementById("up_idcategorie").textContent;
    let up_typecategorie = $("#up_typecategorie").val();
    let form_data = new FormData();
    form_data.append("up_idcategorie", up_idcategorie);
    form_data.append("up_typecategorie", up_typecategorie);
    $.ajax({
      url: "update_categorie.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatecategorie").modal("hide");
          success();
          view_categorie_record();
        }
        if(data=="fail"){
          fail();
        }
          // $("#updatecategorie").modal("hide");
          // view_categorie_record();
          $('#updatecategorie').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Statistiques catégories
get_profit_categorie_record();
get_ressources_categorie_record();
get_local_categorie_record();
function get_profit_categorie_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_profitcategorie", function () {
    text = document.getElementById("de_idcategorie").textContent;
    $("#de_anneeprofitcategorie").val("all");
    let Annee=$("#de_anneeprofitcategorie").val();
    document.getElementById("de_idprofitcategorie").textContent=text;
    $("#detailscategorie").modal("hide");
    $("#showprofitcategorie").modal("show");
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_categorie_profit_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#profitparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showprofitcategorie').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeprofitcategorie", function () {
    let year=$("#de_anneeprofitcategorie").val();
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_categorie_profit_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#profitparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_ressources_categorie_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_ressourcescategorie", function () {
    text = document.getElementById("de_idcategorie").textContent;
    $("#de_anneeressourcescategorie").val("all");
    let Annee=$("#de_anneeressourcescategorie").val();
    document.getElementById("de_idressourcescategorie").textContent=text;
    $("#detailscategorie").modal("hide");
    $("#showressourcescategorie").modal("show");
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_categorie_ressources_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#ressourcesparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showressourcescategorie').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeressourcescategorie", function () {
    let year=$("#de_anneeressourcescategorie").val();
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_categorie_ressources_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#ressourcesparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_local_categorie_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_localcategorie", function () {
    text = document.getElementById("de_idcategorie").textContent;
    $("#de_anneelocalcategorie").val("all");
    let Annee=$("#de_anneelocalcategorie").val();
    document.getElementById("de_idlocalcategorie").textContent=text;
    $("#detailscategorie").modal("hide");
    $("#showlocalcategorie").modal("show");
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_categorie_local_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#localparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showlocalcategorie').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneelocalcategorie", function () {
    let year=$("#de_anneelocalcategorie").val();
    let form_data = new FormData();
    form_data.append("CategorieID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_categorie_local_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#localparcategorie'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
//Tâches
view_tache_record();
insertTacheRecord();
delete_tache_record();
get_tache_record();
get_tache_toupdate();
update_tache_record();
function view_tache_record() {
  $.ajax({
    url: "viewtache.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-tache-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertTacheRecord() {
  $(document).on("click", "#show_form_tache", function () {
    $("#addtache").modal("show");
  });
  $(document).on("click", "#btn-register-tache", function () {
    $("#addtache").scrollTop(0);
    let tachetitre = $("#tachetitre").val();
    let tachedescription = $("#tachedescription").val();
    let tachedeadline = $("#tachedeadline").val();
    let tacheprojet = $("#tacheprojet").val();
    let form_data = new FormData();
    form_data.append("tachetitre", tachetitre);
    form_data.append("tachedescription", tachedescription);
    form_data.append("tachedeadline", tachedeadline);
    form_data.append("tacheprojet", tacheprojet);
      $.ajax({
        url: "AjoutTache.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addtache").modal("hide");
            success();
            view_tache_record();
          }
          if(data=="fail"){
            fail();
          }
          $('#addtache').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
        },
      });
  });
}
function delete_tache_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-tache", function () {
    $("#deletetache").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    console.log(Delete_ID);
    $("#deletetache").modal("show");
  });
  $(document).on("click", "#btn_delete_tache", function () {
    console.log(Delete_ID);
    $.ajax({
      url: "delete_tache_admin.php",
      method: "post",
      data: {
        Delete_TacheID: Delete_ID
      },
      success: function (data) {
        console.log(data);
        if(data=="admin"){
          $("#deletetache").modal("hide");
          success();
          view_tache_record();
        }
        if(data=="chef de projet"){
          $("#deletetache").modal("hide");
          success();
          view_tache_chef_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function get_tache_record() {
  $(document).on("click", "#btn-detail-tache", function () {
    let ID = $(this).attr("data-id");
    $("#detailstache").modal("show");
    $.ajax({
      url: "get_tache_data.php",
      method: "post",
      data: {
        TacheID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idtache");
        span.textContent = data[0];
        span = document.getElementById("de_titretache");
        span.textContent = data[1];
        span = document.getElementById("de_descriptiontache");
        span.textContent = data[2];
        span = document.getElementById("de_deadlinetache");
        span.textContent = data[3];
        span = document.getElementById("de_projettache");
        span.textContent = data[4];
      },
    });
  });
}
function get_tache_toupdate() {
  $(document).on("click", "#btn-edit-tache", function () {
    let ID = $(this).attr("data-id2");
    $("#updatetache").modal("show");
    $.ajax({
      url: "get_tache_data.php",
      method: "post",
      data: {
        TacheID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idtache");
        span.textContent = data[0];
        $("#up_titretache").val(data[1]);
        $("#up_descriptiontache").val(data[2]);
        $("#up_deadlinetache").val(data[3]);
        $("#up_projettache").val(data[5]);
      },
    });
  });
}
function update_tache_record() {
  $(document).on("click", "#btn_update_tache", function () {
    $("#updatetache").scrollTop(0);
    let up_idtache = document.getElementById("up_idtache").textContent;
    let up_titretache = $("#up_titretache").val();
    let up_descriptiontache = $("#up_descriptiontache").val();
    let up_deadlinetache = $("#up_deadlinetache").val();
    let up_projettache = $("#up_projettache").val();
    let form_data = new FormData();
    form_data.append("up_idtache", up_idtache);
    form_data.append("up_titretache", up_titretache);
    form_data.append("up_descriptiontache", up_descriptiontache);
    form_data.append("up_deadlinetache", up_deadlinetache);
    form_data.append("up_projettache", up_projettache);
    $.ajax({
      url: "update_tache.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatetache").modal("hide");
          success();
          view_tache_record();
        }
        if(data=="fail"){
          fail();
        }
        $('#updatetache').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
        });
      },
    });
  });
}
//Locaux
view_local_record();
insertLocalRecord();
delete_local_record();
// get_tache_record();
get_local_toupdate();
update_local_record();
function view_local_record() {
  $.ajax({
    url: "viewlocal.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-local-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertLocalRecord() {
  $(document).on("click", "#show_form_local", function () {
    $("#addlocal").modal("show");
  });
  $(document).on("click", "#btn-register-local", function () {
    $("#addtache").scrollTop(0);
    let localadresse = $("#localadresse").val();
    let localdate = $("#localdate").val();
    let localprojet = $("#localprojet").val();
    let localprix = $("#localprix").val();
    let localnbmois = $("#localnbmois").val();
    let form_data = new FormData();
    form_data.append("localadresse", localadresse);
    form_data.append("localdate", localdate);
    form_data.append("localprojet", localprojet);
    form_data.append("localprix", localprix);
    form_data.append("localnbmois", localnbmois);
      $.ajax({
        url: "AjoutLocal.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
            if(data=="admin"){
              $("#addlocal").modal("hide");
              success();
              view_local_record();
            }
            if(data=="chef de projet"){
              $("#addlocal").modal("hide");
              success();
              view_local_chef_record();
            }
            if(data=="fail"){
              fail();
            }
            $('#addlocal').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function delete_local_record() {
  let Delete_ID ="";
  $(document).on("click", "#btn-delete-local", function () {
    $("#deletelocal").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deletelocal").modal("show");
  });
  $(document).on("click", "#btn_delete_local", function () {
      $.ajax({
        url: "delete_local.php",
        method: "post",
        data: {
          Delete_LocalID: Delete_ID
        },
        success: function (data) {
            if(data=="admin"){
              $("#deletelocal").modal("hide");
              success();
              view_local_record();
            }
            if(data=="chef de projet"){
              $("#deletelocal").modal("hide");
              success();
              view_local_chef_record();
            }
            if(data=="fail"){
              fail();
            }
        },
      });
    });
}
function get_local_toupdate() {
  $(document).on("click", "#btn-edit-local", function () {
    let ID = $(this).attr("data-id2");
    $("#updatelocal").modal("show");
    $.ajax({
      url: "get_local_data.php",
      method: "post",
      data: {
        LocalID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idlocal");
        span.textContent = data[0];
        $("#up_adresselocal").val(data[1]);
        $("#up_prixlocal").val(data[2]);
        $("#up_dateachatlocal").val(data[3]);
        $("#up_nbmoislocal").val(data[4]);
        $("#up_projetlocal").val(data[5]);
      },
    });
  });
}
function update_local_record() {
  $(document).on("click", "#btn_update_local", function () {
    $("#updatelocal").scrollTop(0);
    let up_idlocal = document.getElementById("up_idlocal").textContent;
    let up_adresselocal = $("#up_adresselocal").val();
    let up_prixlocal = $("#up_prixlocal").val();
    let up_dateachatlocal = $("#up_dateachatlocal").val();
    let up_nbmoislocal = $("#up_nbmoislocal").val();
    let up_projetlocal = $("#up_projetlocal").val();
    let form_data = new FormData();
    form_data.append("up_idlocal", up_idlocal);
    form_data.append("up_adresselocal", up_adresselocal);
    form_data.append("up_prixlocal", up_prixlocal);
    form_data.append("up_dateachatlocal", up_dateachatlocal);
    form_data.append("up_nbmoislocal", up_nbmoislocal);
    form_data.append("up_projetlocal", up_projetlocal);
    $.ajax({
      url: "update_local.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="admin"){
          $("#updatelocal").modal("hide");
          success();
          view_local_record();
        }
        if(data=="chef de projet"){
          $("#updatelocal").modal("hide");
          success();
          view_local_chef_record();
        }
        if(data=="fail"){
          fail();
        }
          $('#updatelocal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Locaux chef
view_local_chef_record();
function view_local_chef_record() {
  $.ajax({
    url: "viewlocalchef.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-local-chef-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
//Types equipement
view_typesequipement_record();
insertTypesequipementRecord();
delete_typesequipement_record();
// get_tache_record();
get_typesequipement_toupdate();
update_typesequipement_record();
function view_typesequipement_record() {
  $.ajax({
    url: "viewtypesequipement.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-typesequipement-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertTypesequipementRecord() {
  $(document).on("click", "#show_form_typesequipement", function () {
    $("#addtypesequipement").modal("show");
  });
  $(document).on("click", "#btn-register-typesequipement", function () {
    $("#addtache").scrollTop(0);
    let typesequipementtype = $("#typesequipementtype").val();
    let form_data = new FormData();
    form_data.append("typesequipementtype", typesequipementtype);
      $.ajax({
        url: "AjoutTypesequipement.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
            if(data=="success"){
              $("#addtypesequipement").modal("hide");
              success();
              view_typesequipement_record();
            }
            if(data=="fail"){
              fail();
            }
            $('#addtypesequipement').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function success(){
  Swal.fire(
    'Success!',
    'Opération faite avec succès!',
    'success'
  );
}
function fail(){
  Swal.fire(
    'Error!',
    "L'opération a échoué!",
    'warning'
  );
}
function delete_typesequipement_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-typesequipement", function () {
    $("#deletetypesequipement").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deletetypesequipement").modal("show");
  });
  $(document).on("click", "#btn_delete_typesequipement", function () {
    $.ajax({
      url: "delete_typesequipement.php",
      method: "post",
      data: {
        Delete_TypesequipementID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deletetypesequipement").modal("hide");
          success();
          view_typesequipement_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function get_typesequipement_toupdate() {
  $(document).on("click", "#btn-edit-typesequipement", function () {
    let ID = $(this).attr("data-id2");
    $("#updatetypesequipement").modal("show");
    $.ajax({
      url: "get_typesequipement_data.php",
      method: "post",
      data: {
        TypesequipementID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idtypesequipement");
        span.textContent = data[0];
        $("#up_typetypesequipement").val(data[1]);
      },
    });
  });
}
function update_typesequipement_record() {
  $(document).on("click", "#btn_update_typesequipement", function () {
    $("#updatetypesequipement").scrollTop(0);
    let up_idtypesequipement = document.getElementById("up_idtypesequipement").textContent;
    let up_typetypesequipement = $("#up_typetypesequipement").val();
    let form_data = new FormData();
    form_data.append("up_idtypesequipement", up_idtypesequipement);
    form_data.append("up_typetypesequipement", up_typetypesequipement);
    $.ajax({
      url: "update_typesequipement.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatetypesequipement").modal("hide");
          success();
          view_typesequipement_record();
        }
        if(data=="fail"){
          fail();
        }
        $('#updatetypesequipement').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
        });
      },
    });
  });
}
//Types matiere
view_typesmatiere_record();
insertTypesmatiereRecord();
delete_typesmatiere_record();
// get_tache_record();
get_typesmatiere_toupdate();
update_typesmatiere_record();
function view_typesmatiere_record() {
  $.ajax({
    url: "viewtypesmatiere.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-typesmatiere-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertTypesmatiereRecord() {
  $(document).on("click", "#show_form_typesmatiere", function () {
    $("#addtypesmatiere").modal("show");
  });
  $(document).on("click", "#btn-register-typesmatiere", function () {
    $("#addtache").scrollTop(0);
    let typesmatieretype = $("#typesmatieretype").val();
    let form_data = new FormData();
    form_data.append("typesmatieretype", typesmatieretype);
      $.ajax({
        url: "AjoutTypesmatiere.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
            $("#addtypesmatiere").modal("hide");
            view_typesmatiere_record();
            $('#addtypesmatiere').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function delete_typesmatiere_record() {
  $(document).on("click", "#btn-delete-typesmatiere", function () {
    $("#deletetypesmatiere").modal({backdrop: "static"});
    let Delete_ID = $(this).attr("data-id1");
    $("#deletetypesmatiere").modal("show");
    $(document).on("click", "#btn_delete", function () {
      $.ajax({
        url: "delete_typesmatiere.php",
        method: "post",
        data: {
          Delete_TypesmatiereID: Delete_ID
        },
        success: function (data) {
            $("#deletetypesmatiere").modal("hide");
            view_typesmatiere_record();
        },
      });
    });
  });
}
function get_typesmatiere_toupdate() {
  $(document).on("click", "#btn-edit-typesmatiere", function () {
    let ID = $(this).attr("data-id2");
    $("#updatetypesmatiere").modal("show");
    $.ajax({
      url: "get_typesmatiere_data.php",
      method: "post",
      data: {
        TypesmatiereID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idtypesmatiere");
        span.textContent = data[0];
        $("#up_typetypesmatiere").val(data[1]);
      },
    });
  });
}
function update_typesmatiere_record() {
  $(document).on("click", "#btn_update_typesmatiere", function () {
    $("#updatetypesmatiere").scrollTop(0);
    let up_idtypesmatiere = document.getElementById("up_idtypesmatiere").textContent;
    let up_typetypesmatiere = $("#up_typetypesmatiere").val();
    let form_data = new FormData();
    form_data.append("up_idtypesmatiere", up_idtypesmatiere);
    form_data.append("up_typetypesmatiere", up_typetypesmatiere);
    $.ajax({
      url: "update_typesmatiere.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
          $("#updatetypesmatiere").modal("hide");
          view_typesmatiere_record();
          $('#updatetypesmatiere').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Profil
// get_profil_record();
update_profil_record();
update_profil_password();
function get_profil_record(username) {
  theusername=username;
  $.ajax({
    url: "get_profil_data.php",
    method: "post",
    data: {
      ProfilUsername: username
    },
    dataType: "JSON",
    success: function (data) {
      document.getElementById("de_profilnom").textContent=data[0];
      document.getElementById("de_profilprenom").textContent=data[1];
      document.getElementById("de_profilusername").textContent=data[2];
      document.getElementById("de_profilrole").textContent=data[3];
      document.getElementById("titre_profilusername").textContent=data[2];
      document.getElementById("titre_profilrole").textContent=data[3];
      document.getElementById("titre_profilimage").setAttribute("src","images/employes/"+data[5]);
      // document.getElementById("up_profileimage").setAttribute("src","images/employes/"+data[5]);
      $("#up_profileusername").val(data[2]);
      $("#up_profilenom").val(data[0]);
      $("#up_profileprenom").val(data[1]);
    },
  });
}
function update_profil_record() {
  $(document).on("click", "#btn_update_profile", function () {
    let up_profileusername = $("#up_profileusername").val();
    let up_profilenom = $("#up_profilenom").val();
    let up_profileprenom = $("#up_profileprenom").val();
    let up_profileoldusername=document.getElementById("de_profilusername").textContent;
    let form_data = new FormData();
    form_data.append("up_profileusername", up_profileusername);
    form_data.append("up_profilenom", up_profilenom);
    form_data.append("up_profileprenom", up_profileprenom);
    form_data.append("up_profileoldusername", up_profileoldusername);
    $.ajax({
      url: "update_profil.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          get_profil_record(up_profileusername);
          update_profil_header();
          success();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function update_profil_password() {
  $(document).on("click", "#btn_update_password", function () {
    let currentPassword = $("#currentPassword").val();
    let newpassword = $("#newpassword").val();
    let renewpassword = $("#renewpassword").val();
    let up_profileusername=document.getElementById("de_profilusername").textContent;
    let form_data = new FormData();
    form_data.append("currentPassword", currentPassword);
    form_data.append("newpassword", newpassword);
    form_data.append("renewpassword", renewpassword);
    form_data.append("up_profileusername", up_profileusername);
    $.ajax({
      url: "update_password.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          success();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
update_profil_header();
function update_profil_header() {
    $.ajax({
      url: "get_profil_data_header.php",
      method: "post",
      dataType: "JSON",
      success: function (data) {
        document.getElementById("headerimageprofile").setAttribute("src","images/employes/"+data[5]);
        document.getElementById("headerusernameprofile").textContent=data[2];
      },
    });
}
//Taches chef
// view_tache_chef_record();
get_tache_chef_record();
// insertTypesmatiereRecord();
delete_tache_chef_record();
// get_tache_record();
// get_typesmatiere_toupdate();
// update_typesmatiere_record();
function view_tache_chef_record(username) {
  $.ajax({
    url: "viewtachechef.php",
    method: "post",
    data: {
      Username: username
    },
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-tache-chef-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function get_tache_chef_record() {
  $(document).on("click", "#btn-detail-chef-tache", function () {
    let ID = $(this).attr("data-id");
    $("#detailscheftache").modal("show");
    $.ajax({
      url: "get_tache_chef_data.php",
      method: "post",
      data: {
        TacheChefID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idcheftache");
        span.textContent = data[0];
        span = document.getElementById("de_titrecheftache");
        span.textContent = data[1];
        span = document.getElementById("de_descriptioncheftache");
        span.textContent = data[2];
        span = document.getElementById("de_deadlinecheftache");
        span.textContent = data[3];
        span = document.getElementById("de_projetcheftache");
        span.textContent = data[4];
        $.ajax({
          url: "update_tache_receivevu_data.php",
          method: "post",
          data: {
            TacheID: ID
          },
          success: function (data) {
            if(data=="success"){
              get_notif_employe_tache();
            }
          }
        });
      },
    });
  });
}
function delete_tache_chef_record() {
  let Delete_ID ="";
  $(document).on("click", "#btn-delete-chef-tache", function () {
    $("#deletecheftache").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $.ajax({
      url: "get_tache_status_data.php",
      method: "post",
      data: {
        TacheID: Delete_ID
      },
      success: function (data) {
        if(data=="Done"){
          fail();
        }else{
          $("#deletecheftache").modal("show");
          $.ajax({
            url: "update_tache_receivevu_data.php",
            method: "post",
            data: {
              TacheID: Delete_ID
            },
            success: function (data) {
              if(data=="success"){
                get_notif_employe_tache();
              }
            }
          });
        }
      }
    });
  });
  $(document).on("click", "#btn_delete_tache_chef", function () {
    $.ajax({
      url: "delete_tache.php",
      method: "post",
      data: {
        Delete_TacheID: Delete_ID
      },
      success: function (data) {
        console.log(data);
        if(data!="success"){
          $("#deletecheftache").modal("hide");
          success();
          view_tache_chef_record(data);
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Taches employe
// view_tache_employe_record();
get_tache_employe_record();
delete_tache_employe_record();
function view_tache_employe_record(username) {
  $.ajax({
    url: "viewtacheemploye.php",
    method: "post",
    data: {
      Username: username
    },
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-tache-employe-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function get_tache_employe_record() {
  $(document).on("click", "#btn-detail-employe-tache", function () {
    let ID = $(this).attr("data-id");
    $("#detailsemployetache").modal("show");
    $.ajax({
      url: "get_tache_employe_data.php",
      method: "post",
      data: {
        TacheEmployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idemployetache");
        span.textContent = data[0];
        span = document.getElementById("de_titreemployetache");
        span.textContent = data[1];
        span = document.getElementById("de_descriptionemployetache");
        span.textContent = data[2];
        span = document.getElementById("de_deadlineemployetache");
        span.textContent = data[3];
        span = document.getElementById("de_projetemployetache");
        span.textContent = data[4];
        $.ajax({
          url: "update_tache_receivevu_data.php",
          method: "post",
          data: {
            TacheID: ID
          },
          success: function (data) {
            if(data=="success"){
              get_notif_employe_tache();
            }
          }
        });
      },
    });
  });
}
function delete_tache_employe_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-employe-tache", function () {
    $("#deleteemployetache").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $.ajax({
      url: "get_tache_status_data.php",
      method: "post",
      data: {
        TacheID: Delete_ID
      },
      success: function (data) {
        if(data=="Done"){
          fail();
        }else{
          $("#deleteemployetache").modal("show");
          $.ajax({
            url: "update_tache_receivevu_data.php",
            method: "post",
            data: {
              TacheID: Delete_ID
            },
            success: function (data) {
              if(data=="success"){
                get_notif_employe_tache();
              }
            }
          });
        }
      }
    });
  });
  $(document).on("click", "#btn_delete_tache_employe", function () {
    $.ajax({
      url: "delete_tache.php",
      method: "post",
      data: {
        Delete_TacheID: Delete_ID
      },
      success: function (data) {
        console.log(data);
        if(data!="fail"){
          $("#deleteemployetache").modal("hide");
          success();
          view_tache_employe_record(data);
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Taches affect
view_tache_affect_record();
insertTacheAffectRecord();
get_tache_affect_record();
get_tache_affect_toupdate();
update_tache_affect_record();
delete_tache_affect_record();
function view_tache_affect_record() {
  $.ajax({
    url: "viewtacheaffect.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-tache-affect-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertTacheAffectRecord() {
  $(document).on("click", "#show_form_tache_affect", function () {
    $("#addtacheaffect").modal("show");
  });
  $(document).on("change", "#tacheaffectprojet", function () {
    let ID=$("#tacheaffectprojet").val();
    console.log(ID);
    $.ajax({
      url: "get_projet_employes.php",
      method: "post",
      data: {
        ProjetID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let ch="";
        data.forEach((e)=>{ch+="<option value='"+e.id+"'>"+e.nom+"</option>";});
        $('#tacheaffectemploye').html(ch);
      },
    });
  });
  $(document).on("click", "#btn-register-tache-affect", function () {
    $("#addtacheaffect").scrollTop(0);
    let tacheaffecttitre = $("#tacheaffecttitre").val();
    let tacheaffectdescription = $("#tacheaffectdescription").val();
    let tacheaffectdeadline = $("#tacheaffectdeadline").val();
    let tacheaffectprojet = $("#tacheaffectprojet").val();
    let tacheaffectemploye = $("#tacheaffectemploye").val();
    let form_data = new FormData();
    form_data.append("tacheaffecttitre", tacheaffecttitre);
    form_data.append("tacheaffectdescription", tacheaffectdescription);
    form_data.append("tacheaffectdeadline", tacheaffectdeadline);
    form_data.append("tacheaffectprojet", tacheaffectprojet);
    form_data.append("tacheaffectemploye", tacheaffectemploye);
      $.ajax({
        url: "AjoutTacheAffect.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addtacheaffect").modal("hide");
            success();
            view_tache_affect_record();
          }
          if(data=="fail"){
            fail();
          }
            $('#addtacheaffect').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function get_tache_affect_record() {
  $(document).on("click", "#btn-detail-tache-affect", function () {
    let ID = $(this).attr("data-id");
    $("#detailstacheaffect").modal("show");
    $.ajax({
      url: "get_tache_affect_data.php",
      method: "post",
      data: {
        TacheID: ID
      },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let span = document.getElementById("de_idtacheaffect");
        span.textContent = data[0];
        span = document.getElementById("de_titretacheaffect");
        span.textContent = data[1];
        span = document.getElementById("de_descriptiontacheaffect");
        span.textContent = data[2];
        span = document.getElementById("de_deadlinetacheaffect");
        span.textContent = data[3];
        span = document.getElementById("de_projettacheaffect");
        span.textContent = data[4];
        span = document.getElementById("de_employetacheaffect");
        span.textContent = data[5]+" "+data[6];
      },
    });
  });
}
function get_tache_affect_toupdate() {
  $(document).on("click", "#btn-edit-tache-affect", function () {
    let ID = $(this).attr("data-id2");
    $("#updatetacheaffect").modal("show");
    $.ajax({
      url: "get_tache_affect_data.php",
      method: "post",
      data: {
        TacheID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idtacheaffect");
        span.textContent = data[0];
        $("#up_titretacheaffect").val(data[1]);
        $("#up_descriptiontacheaffect").val(data[2]);
        $("#up_deadlinetacheaffect").val(data[3]);
        $("#up_projettacheaffect").val(data[7]);
        $.ajax({
          url: "get_projet_employes.php",
          method: "post",
          data: {
            ProjetID:data[7]
          },
          dataType: "JSON",
          success: function (data1) {
            let ch="";
            data1.forEach((e)=>{ch+="<option value='"+e.id+"'>"+e.nom+"</option>";});
            $('#up_employetacheaffect').html(ch);
            $("#up_employetacheaffect").val(data[8]);
          },
        });
      },
    });
  });
  $(document).on("change", "#up_projettacheaffect", function () {
    let ID=$("#up_projettacheaffect").val();
    console.log(ID);
    $.ajax({
      url: "get_projet_employes.php",
      method: "post",
      data: {
        ProjetID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let ch="";
        data.forEach((e)=>{ch+="<option value='"+e.id+"'>"+e.nom+"</option>";});
        $('#up_employetacheaffect').html(ch);
      },
    });
  });
}
function update_tache_affect_record() {
  $(document).on("click", "#btn_update_tache_affect", function () {
    $("#updatetacheaffect").scrollTop(0);
    let up_idtacheaffect = document.getElementById("up_idtacheaffect").textContent;
    let up_titretacheaffect = $("#up_titretacheaffect").val();
    let up_descriptiontacheaffect = $("#up_descriptiontacheaffect").val();
    let up_deadlinetacheaffect = $("#up_deadlinetacheaffect").val();
    let up_projettacheaffect = $("#up_projettacheaffect").val();
    let up_employetacheaffect = $("#up_employetacheaffect").val();
    let form_data = new FormData();
    form_data.append("up_idtacheaffect", up_idtacheaffect);
    form_data.append("up_titretacheaffect", up_titretacheaffect);
    form_data.append("up_descriptiontacheaffect", up_descriptiontacheaffect);
    form_data.append("up_deadlinetacheaffect", up_deadlinetacheaffect);
    form_data.append("up_projettacheaffect", up_projettacheaffect);
    form_data.append("up_employetacheaffect", up_employetacheaffect);
    $.ajax({
      url: "update_tache_affect.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatetacheaffect").modal("hide");
          success();
          view_tache_affect_record();
        }
        if(data=="fail"){
          fail();
        }
          $('#updatetacheaffect').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
function delete_tache_affect_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-tache-affect", function () {
    $("#deletetacheaffect").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deletetacheaffect").modal("show");
  });
  $(document).on("click", "#btn_delete_tache_affect", function () {
    console.log(Delete_ID);
    $.ajax({
      url: "delete_tache_admin.php",
      method: "post",
      data: {
        Delete_TacheID: Delete_ID
      },
      success: function (data) {
        console.log(data);
        if(data!="fail"){
          $("#deletetacheaffect").modal("hide");
          success();
          view_tache_affect_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Conges Employe
view_conges_employe_record();
insertCongesEmployeRecord();
get_conges_employe_toupdate();
update_conges_employe_record();
delete_conges_employe_record();
function view_conges_employe_record() {
  $.ajax({
    url: "viewcongesemploye.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-conges-employe-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertCongesEmployeRecord() {
  $(document).on("click", "#show_form_conges_employe", function () {
    $("#addcongesemploye").modal("show");
  });
  $(document).on("click", "#btn-register-conges-employe", function () {
    $("#addcongesemploye").scrollTop(0);
    let congesemployedate = $("#congesemployedate").val();
    let congesemployeduree = $("#congesemployeduree").val();
    let form_data = new FormData();
    form_data.append("congesemployedate", congesemployedate);
    form_data.append("congesemployeduree", congesemployeduree);
      $.ajax({
        url: "AjoutCongesEmploye.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addcongesemploye").modal("hide");
            success();
            view_conges_employe_record();
          }
          if(data=="fail"){
            fail();
          }
            $('#addcongesemploye').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function get_conges_employe_toupdate() {
  $(document).on("click", "#btn-edit-conges-employe", function () {
    let ID = $(this).attr("data-id2");
    $.ajax({
      url: "get_conges_employe_data.php",
      method: "post",
      data: {
        CongesemployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        if(data[3]=="Pending"){
          let span = document.getElementById("up_idcongesemploye");
          span.textContent = data[0];
          $("#up_datecongesemploye").val(data[2]);
          $("#up_dureecongesemploye").val(data[1]);
          $("#updatecongesemploye").modal("show");
        }else{
          fail();
        }
      },
    });
  });
}
function update_conges_employe_record() {
  $(document).on("click", "#btn_update_conges_employe", function () {
    $("#updatecongesemploye").scrollTop(0);
    let up_idcongesemploye = document.getElementById("up_idcongesemploye").textContent;
    let up_datecongesemploye = $("#up_datecongesemploye").val();
    let up_dureecongesemploye = $("#up_dureecongesemploye").val();
    let form_data = new FormData();
    form_data.append("up_idcongesemploye", up_idcongesemploye);
    form_data.append("up_datecongesemploye", up_datecongesemploye);
    form_data.append("up_dureecongesemploye", up_dureecongesemploye);
    $.ajax({
      url: "update_conges_employe.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updatecongesemploye").modal("hide");
          success();
          view_conges_employe_record();
        }
        if(data=="fail"){
          fail();
        }
          $('#updatecongesemploye').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
function delete_conges_employe_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-conges-employe", function () {
    $("#deletecongesemploye").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $.ajax({
      url: "get_conges_employe_data.php",
      method: "post",
      data: {
        CongesemployeID: Delete_ID
      },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        if(data[3]=="Pending"){
          $("#deletecongesemploye").modal("show");
        }else{
          fail();
        }
      },
    });
  });
  $(document).on("click", "#btn_delete_conges_employe", function () {
    $.ajax({
      url: "delete_conges_employe.php",
      method: "post",
      data: {
        Delete_CongesID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deletecongesemploye").modal("hide");
          success();
          view_conges_employe_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Avance Employe
view_avances_employe_record();
insertAvanceEmployeRecord();
get_avance_employe_toupdate();
update_avance_employe_record();
delete_avance_employe_record();
function view_avances_employe_record() {
  $.ajax({
    url: "viewavancesemploye.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-avances-employe-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertAvanceEmployeRecord() {
  $(document).on("click", "#show_form_avance_employe", function () {
    $("#addavanceemploye").modal("show");
  });
  $(document).on("click", "#btn-register-avance-employe", function () {
    $("#addavanceemploye").scrollTop(0);
    let avanceemployedate = $("#avanceemployedate").val();
    let avanceemployevaleur = $("#avanceemployevaleur").val();
    let form_data = new FormData();
    form_data.append("avanceemployedate", avanceemployedate);
    form_data.append("avanceemployevaleur", avanceemployevaleur);
      $.ajax({
        url: "AjoutAvanceEmploye.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
          if(data=="success"){
            $("#addavanceemploye").modal("hide");
            success();
            view_avances_employe_record();
          }
          if(data=="fail"){
            fail();
          }
            // $("#addavanceemploye").modal("hide");
            // view_avances_employe_record();
            $('#addavanceemploye').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function get_avance_employe_toupdate() {
  $(document).on("click", "#btn-edit-avance-employe", function () {
    let ID = $(this).attr("data-id2");
    $.ajax({
      url: "get_avance_employe_data.php",
      method: "post",
      data: {
        AvanceemployeID: ID
      },
      dataType: "JSON",
      success: function (data) {
        if(data[3]=="Pending"){
          let span = document.getElementById("up_idavanceemploye");
          span.textContent = data[0];
          $("#up_dateavanceemploye").val(data[2]);
          $("#up_valeuravanceemploye").val(data[1]);
          $("#updateavanceemploye").modal("show");
        }else{
          fail();
        }
      },
    });
  });
}
function update_avance_employe_record() {
  $(document).on("click", "#btn_update_avance_employe", function () {
    $("#updateavanceemploye").scrollTop(0);
    let up_idavanceemploye = document.getElementById("up_idavanceemploye").textContent;
    let up_dateavanceemploye = $("#up_dateavanceemploye").val();
    let up_valeuravanceemploye = $("#up_valeuravanceemploye").val();
    let form_data = new FormData();
    form_data.append("up_idavanceemploye", up_idavanceemploye);
    form_data.append("up_dateavanceemploye", up_dateavanceemploye);
    form_data.append("up_valeuravanceemploye", up_valeuravanceemploye);
    $.ajax({
      url: "update_avance_employe.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        if(data=="success"){
          $("#updateavanceemploye").modal("hide");
          success();
          view_avances_employe_record();
        }
        if(data=="fail"){
          fail();
        }
          // $("#updateavanceemploye").modal("hide");
          // view_avances_employe_record();
          $('#updateavanceemploye').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
function delete_avance_employe_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-avance-employe", function () {
    $("#deleteavanceemploye").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $.ajax({
      url: "get_avance_employe_data.php",
      method: "post",
      data: {
        AvanceemployeID: Delete_ID
      },
      dataType: "JSON",
      success: function (data) {
        if(data[3]=="Pending"){
          $("#deleteavanceemploye").modal("show");
        }else{
          fail();
        }
      },
    });
  });
  $(document).on("click", "#btn_delete_avance_employe", function () {
    $.ajax({
      url: "delete_avance_employe.php",
      method: "post",
      data: {
        Delete_AvanceID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deleteavanceemploye").modal("hide");
          success();
          view_avances_employe_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Conges
view_conges_record();
approve_conges_record();
refuse_conges_record();
delete_conges_record();
// get_conges_employe_toupdate();
// update_conges_employe_record();
function view_conges_record() {
  $.ajax({
    url: "viewconges.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-conges-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function approve_conges_record() {
  let barchart="";
  let ID="";
  $(document).on("click", "#btn-approve-conges", function () {
    ID = $(this).attr("data-id");
    let span = document.getElementById("ap_idconges");
    span.textContent = ID;
    $.ajax({
      url: "view_id_employe_conges_record.php",
      method: "post",
      data: {ID:ID},
      dataType: "JSON",
      success: function (dat) {
        console.log(dat);
        let currentDate = new Date();
        let Annee = currentDate.getFullYear();
        let form_data = new FormData();
        form_data.append("EmployeID", dat);
        form_data.append("Annee", Annee);
        $.ajax({
          url: "view_employe_conges_record.php",
          method: "post",
          processData: false,
          contentType: false,
          data: form_data,
          dataType: "JSON",
          success: function (data) {
            console.log(data);
            let annees=[];
            let valeurs=[];
            data.forEach((e)=>{annees.push(e.annee);});
            data.forEach((e)=>{valeurs.push(e.valeurs);});
            barchart=new Chart(document.querySelector('#congesapproveemploye'), {
              type: 'bar',
              data: {
                labels: annees,
                datasets: [{
                  label: 'Bar Chart',
                  data: valeurs,
                  backgroundColor:'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgb(54, 162, 235)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          },
        });
      },
    });
    $("#approveconges").modal("show");
    $.ajax({
      url: "update_conges_receivevu_data.php",
      method: "post",
      data: {
        CongesID: ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_conges();
        }
      }
    });
    $('#approveconges').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("click", "#btn_approve_conges", function () {
    $.ajax({
      url: "update_conges.php",
      method: "post",
      data: {
        CongesID: ID,
        Status: "Approved"
      },
      success: function (data) {
        if(data=="success"){
          $("#approveconges").modal("hide");
          success();
          view_conges_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function refuse_conges_record() {
  let barchart="";
  let ID="";
  $(document).on("click", "#btn-refuse-conges", function () {
    ID = $(this).attr("data-id2");
    let span = document.getElementById("re_idconges");
    span.textContent = ID;
    $.ajax({
      url: "view_id_employe_conges_record.php",
      method: "post",
      data: {ID:ID},
      dataType: "JSON",
      success: function (dat) {
        console.log(dat);
        let currentDate = new Date();
        let Annee = currentDate.getFullYear();
        let form_data = new FormData();
        form_data.append("EmployeID", dat);
        form_data.append("Annee", Annee);
        $.ajax({
          url: "view_employe_conges_record.php",
          method: "post",
          processData: false,
          contentType: false,
          data: form_data,
          dataType: "JSON",
          success: function (data) {
            console.log(data);
            let annees=[];
            let valeurs=[];
            data.forEach((e)=>{annees.push(e.annee);});
            data.forEach((e)=>{valeurs.push(e.valeurs);});
            barchart=new Chart(document.querySelector('#congesrefuseemploye'), {
              type: 'bar',
              data: {
                labels: annees,
                datasets: [{
                  label: 'Bar Chart',
                  data: valeurs,
                  backgroundColor:'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgb(54, 162, 235)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          },
        });
      },
    });
    $("#refuseconges").modal("show");
    $.ajax({
      url: "update_conges_receivevu_data.php",
      method: "post",
      data: {
        CongesID: ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_conges();
        }
      }
    });
    $('#refuseconges').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("click", "#btn_refuse_conges", function () {
    $.ajax({
      url: "update_conges.php",
      method: "post",
      data: {
        CongesID: ID,
        Status: "Refused"
      },
      success: function (data) {
        if(data=="success"){
          $("#refuseconges").modal("hide");
          success();
          view_conges_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function delete_conges_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-conges", function () {
    $("#deleteconges").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deleteconges").modal("show");
    $.ajax({
      url: "update_conges_receivevu_data.php",
      method: "post",
      data: {
        CongesID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_conges();
        }
      }
    });
  });
  $(document).on("click", "#btn_delete_conges", function () {
    $.ajax({
      url: "delete_conges_employe.php",
      method: "post",
      data: {
        Delete_CongesID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deleteconges").modal("hide");
          success();
          view_conges_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Avances
view_avance_record();
approve_avance_record();
refuse_avance_record();
delete_avance_record();
// get_conges_employe_toupdate();
// update_conges_employe_record();
function view_avance_record() {
  $.ajax({
    url: "viewavance.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-avance-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function approve_avance_record() {
  let barchart="";
  let ID="";
  $(document).on("click", "#btn-approve-avance", function () {
    ID = $(this).attr("data-id");
    let span = document.getElementById("ap_idavance");
    span.textContent = ID;
    $.ajax({
      url: "view_id_employe_avance_record.php",
      method: "post",
      data: {ID:ID},
      dataType: "JSON",
      success: function (dat) {
        console.log(dat);
        let currentDate = new Date();
        let Annee = currentDate.getFullYear();
        let form_data = new FormData();
        form_data.append("EmployeID", dat);
        form_data.append("Annee", Annee);
        $.ajax({
          url: "view_employe_avance_record.php",
          method: "post",
          processData: false,
          contentType: false,
          data: form_data,
          dataType: "JSON",
          success: function (data) {
            console.log(data);
            let annees=[];
            let valeurs=[];
            data.forEach((e)=>{annees.push(e.annee);});
            data.forEach((e)=>{valeurs.push(e.valeurs);});
            barchart=new Chart(document.querySelector('#avanceapproveemploye'), {
              type: 'bar',
              data: {
                labels: annees,
                datasets: [{
                  label: 'Bar Chart',
                  data: valeurs,
                  backgroundColor:'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgb(54, 162, 235)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          },
        });
      },
    });
    $("#approveavance").modal("show");
    $.ajax({
      url: "update_avance_receivevu_data.php",
      method: "post",
      data: {
        AvanceID: ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_avance();
        }
      }
    });
    $('#approveavance').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("click", "#btn_approve_avance", function () {
    $.ajax({
      url: "update_avance.php",
      method: "post",
      data: {
        AvanceID: ID,
        Status: "Approved"
      },
      success: function (data) {
        if(data=="success"){
          $("#approveavance").modal("hide");
          success();
          view_avance_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function refuse_avance_record() {
  let barchart="";
  let ID="";
  $(document).on("click", "#btn-refuse-avance", function () {
    ID = $(this).attr("data-id2");
    let span = document.getElementById("re_idavance");
    span.textContent = ID;
    $.ajax({
      url: "view_id_employe_avance_record.php",
      method: "post",
      data: {ID:ID},
      dataType: "JSON",
      success: function (dat) {
        console.log(dat);
        let currentDate = new Date();
        let Annee = currentDate.getFullYear();
        let form_data = new FormData();
        form_data.append("EmployeID", dat);
        form_data.append("Annee", Annee);
        $.ajax({
          url: "view_employe_avance_record.php",
          method: "post",
          processData: false,
          contentType: false,
          data: form_data,
          dataType: "JSON",
          success: function (data) {
            console.log(form_data);
            console.log(data);
            let annees=[];
            let valeurs=[];
            data.forEach((e)=>{annees.push(e.annee);});
            data.forEach((e)=>{valeurs.push(e.valeurs);});
            barchart=new Chart(document.querySelector('#avancerefuseemploye'), {
              type: 'bar',
              data: {
                labels: annees,
                datasets: [{
                  label: 'Bar Chart',
                  data: valeurs,
                  backgroundColor:'rgba(54, 162, 235, 0.2)',
                  borderColor: 'rgb(54, 162, 235)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          },
        });
      },
    });
    $("#refuseavance").modal("show");
    $.ajax({
      url: "update_avance_receivevu_data.php",
      method: "post",
      data: {
        AvanceID: ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_avance();
        }
      }
    });
    $('#refuseavance').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("click", "#btn_refuse_avance", function () {
    $.ajax({
      url: "update_avance.php",
      method: "post",
      data: {
        AvanceID: ID,
        Status: "Refused"
      },
      success: function (data) {
        if(data=="success"){
          $("#refuseavance").modal("hide");
          success();
          view_avance_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
function delete_avance_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-avance", function () {
    $("#deleteavance").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deleteavance").modal("show");
    $.ajax({
      url: "update_avance_receivevu_data.php",
      method: "post",
      data: {
        AvanceID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          get_notif_employe_avance();
        }
      }
    });
  });
  $(document).on("click", "#btn_delete_avance", function () {
    $.ajax({
      url: "delete_avance_employe.php",
      method: "post",
      data: {
        Delete_AvanceID: Delete_ID
      },
      success: function (data) {
        if(data=="success"){
          $("#deleteavance").modal("hide");
          success();
          view_avance_record();
        }
        if(data=="fail"){
          fail();
        }
      },
    });
  });
}
//Profit
view_profit_record();
insertProfitRecord();
delete_profit_record();
get_profit_toupdate();
update_profit_record();
function view_profit_record() {
  $.ajax({
    url: "viewprofit.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-profit-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
function insertProfitRecord() {
  $(document).on("click", "#show_form_profit", function () {
    $("#addprofit").modal("show");
  });
  $(document).on("click", "#btn-register-profit", function () {
    $("#addprofit").scrollTop(0);
    let profitannee = $("#profitannee").val();
    let profitprojet = $("#profitprojet").val();
    let profitvaleur = $("#profitvaleur").val();
    let profitmois = $("#profitmois").val();
    let form_data = new FormData();
    form_data.append("profitannee", profitannee);
    form_data.append("profitprojet", profitprojet);
    form_data.append("profitvaleur", profitvaleur);
    form_data.append("profitmois", profitmois);
      $.ajax({
        url: "AjoutProfit.php",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        success: function (data) {
            if(data=="admin"){
              $("#addprofit").modal("hide");
              success();
              view_profit_record();
            }
            if(data=="chef de projet"){
              $("#addprofit").modal("hide");
              success();
              view_profit_chef_record();
            }
            if(data=="fail"){
              fail();
            }
            $('#addprofit').on('hidden.bs.modal', function () {
              $(this).find('form').trigger('reset');
            });
        },
      });
  });
}
function delete_profit_record() {
  let Delete_ID="";
  $(document).on("click", "#btn-delete-profit", function () {
    $("#deleteprofit").modal({backdrop: "static"});
    Delete_ID = $(this).attr("data-id1");
    $("#deleteprofit").modal("show");
  });
  $(document).on("click", "#btn_delete_profit", function () {
      $.ajax({
        url: "delete_profit.php",
        method: "post",
        data: {
          Delete_ProfitID: Delete_ID
        },
        success: function (data) {
            if(data=="admin"){
              $("#deleteprofit").modal("hide");
              success();
              view_profit_record();
            }
            if(data=="chef de projet"){
              $("#deleteprofit").modal("hide");
              success();
              view_profit_chef_record();
            }
            if(data=="fail"){
              fail();
            }
        },
      });
    });
}
function get_profit_toupdate() {
  $(document).on("click", "#btn-edit-profit", function () {
    let ID = $(this).attr("data-id2");
    $("#updateprofit").modal("show");
    $.ajax({
      url: "get_profit_data.php",
      method: "post",
      data: {
        ProfitID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("up_idprofit");
        span.textContent = data[0];
        $("#up_moisprofit").val(data[1]);
        $("#up_anneeprofit").val(data[2]);
        $("#up_projetprofit").val(data[3]);
        $("#up_valeurprofit").val(data[4]);
      },
    });
  });
}
function update_profit_record() {
  $(document).on("click", "#btn_update_profit", function () {
    $("#updateprofit").scrollTop(0);
    let up_idprofit = document.getElementById("up_idprofit").textContent;
    let up_anneeprofit = $("#up_anneeprofit").val();
    let up_valeurprofit = $("#up_valeurprofit").val();
    let up_projetprofit = $("#up_projetprofit").val();
    let up_moisprofit = $("#up_moisprofit").val();
    let form_data = new FormData();
    form_data.append("up_idprofit", up_idprofit);
    form_data.append("up_anneeprofit", up_anneeprofit);
    form_data.append("up_valeurprofit", up_valeurprofit);
    form_data.append("up_projetprofit", up_projetprofit);
    form_data.append("up_moisprofit", up_moisprofit);
    $.ajax({
      url: "update_profit.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
          if(data=="admin"){
            $("#updateprofit").modal("hide");
            success();
            view_profit_record();
          }
          if(data=="chef de projet"){
            $("#updateprofit").modal("hide");
            success();
            view_profit_chef_record();
          }
          if(data=="fail"){
              fail();
          }
          $('#updateprofit').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
          });
      },
    });
  });
}
//Profit chef
view_profit_chef_record();
function view_profit_chef_record(){
  $.ajax({
    url: "viewprofitchef.php",
    method: "post",
    success: function (data) {
      try {
        data = $.parseJSON(data);
        if (data.status == "success") {
          $("#table-profit-chef-list").html(data.html);
        }
      } catch (e) {
        console.error("Invalid Response!");
      }
    },
  });
}
//Statistiques employe
get_conges_employe_record();
get_avance_employe_record();
function get_conges_employe_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_congesemploye", function () {
    text = document.getElementById("de_idemploye").textContent;
    $("#de_anneecongesemploye").val("all");
    let Annee=$("#de_anneecongesemploye").val();
    document.getElementById("de_idcongesemploye").textContent=text;
    $("#detailsemploye").modal("hide");
    $("#showcongesemploye").modal("show");
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_employe_conges_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#congesparemploye'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showcongesemploye').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneecongesemploye", function () {
    let year=$("#de_anneecongesemploye").val();
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_employe_conges_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#congesparemploye'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_avance_employe_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_avanceemploye", function () {
    text = document.getElementById("de_idemploye").textContent;
    $("#de_anneeavanceemploye").val("all");
    let Annee=$("#de_anneeavanceemploye").val();
    document.getElementById("de_idavanceemploye").textContent=text;
    $("#detailsemploye").modal("hide");
    $("#showavanceemploye").modal("show");
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_employe_avance_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#avanceparemploye'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showavanceemploye').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeavanceemploye", function () {
    let year=$("#de_anneeavanceemploye").val();
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_employe_avance_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#avanceparemploye'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
//Etape
view_etape_record();
function view_etape_record(){
  $(document).on("click", "#btn-etape", function () {
    let sommeetape = $("#sommeetape").val();
    let nbmoisetape = $("#nbmoisetape").val();
    let form_data = new FormData();
    form_data.append("sommeetape", sommeetape);
    form_data.append("nbmoisetape", nbmoisetape);
    $.ajax({
      url: "viewetape.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      success: function (data) {
        console.log(data);
      },
    });
  });
}
//Statistiques chef
get_conges_chef_record();
get_avance_chef_record();
function get_conges_chef_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_congeschef", function () {
    text = document.getElementById("de_idchef").textContent;
    $("#de_anneecongeschef").val("all");
    let Annee=$("#de_anneecongeschef").val();
    document.getElementById("de_idcongeschef").textContent=text;
    $("#detailschef").modal("hide");
    $("#showcongeschef").modal("show");
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_employe_conges_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#congesparchef'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showcongeschef').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneecongeschef", function () {
    let year=$("#de_anneecongeschef").val();
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_employe_conges_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#congesparchef'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
function get_avance_chef_record() {
  let barchart="";
  let text="";
  $(document).on("click", "#de_avancechef", function () {
    text = document.getElementById("de_idchef").textContent;
    $("#de_anneeavancechef").val("all");
    let Annee=$("#de_anneeavancechef").val();
    document.getElementById("de_idavancechef").textContent=text;
    $("#detailschef").modal("hide");
    $("#showavancechef").modal("show");
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", Annee);
    $.ajax({
      url: "view_employe_avance_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#avanceparchef'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
    $('#showavancechef').on('hidden.bs.modal', function () {
      barchart.destroy();
    });
  });
  $(document).on("change", "#de_anneeavancechef", function () {
    let year=$("#de_anneeavancechef").val();
    let form_data = new FormData();
    form_data.append("EmployeID", text);
    form_data.append("Annee", year);
    $.ajax({
      url: "view_employe_avance_record.php",
      method: "post",
      processData: false,
      contentType: false,
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        barchart.destroy();
        let annees=[];
        let valeurs=[];
        data.forEach((e)=>{annees.push(e.annee);});
        data.forEach((e)=>{valeurs.push(e.valeurs);});
        barchart=new Chart(document.querySelector('#avanceparchef'), {
          type: 'bar',
          data: {
            labels: annees,
            datasets: [{
              label: 'Bar Chart',
              data: valeurs,
              backgroundColor:'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgb(54, 162, 235)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
    });
  });
}
//Notif employé
get_notif_employe_tache();
get_notif_employe_tache_affect();
get_notif_employe_avance();
get_notif_employe_conges();
function get_notif_employe_tache() {
  $.ajax({
    url: "view_taches_notif.php",
    method: "post",
    processData: false,
    data:{id:1},
    contentType: false,
    dataType: "JSON",
    success: function (data) {
      document.getElementById("nbtacheemployenotif").textContent=data.length;
      ch="";
      data.forEach((e)=>{
        ch+='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>'+e.titre+'</h4><p>'+e.titrep+'</p><button id="show_notif_tache_detail_employe" class="btn btn-primary" type="button" id-data="'+e.id_tache+'">Details</button></div></li><li><hr class="dropdown-divider"></li>'
      })
      if(ch==""){
        ch='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>Aucune notification</h4></div></li>';
      }
      document.getElementById("tacheemployenotiflist").innerHTML=ch;
    },
  });
  $(document).on("click", "#show_notif_tache_detail_employe", function () {
    let ID = $(this).attr("id-data");
    $("#detailsemployetachenotif").modal("show");
    $.ajax({
      url: "get_tache_data.php",
      method: "post",
      data: {
        TacheID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idemployetachenotif");
        span.textContent = data[0];
        span = document.getElementById("de_titreemployetachenotif");
        span.textContent = data[1];
        span = document.getElementById("de_descriptionemployetachenotif");
        span.textContent = data[2];
        span = document.getElementById("de_deadlineemployetachenotif");
        span.textContent = data[3];
        span = document.getElementById("de_projetemployetachenotif");
        span.textContent = data[4];
        document.getElementById("dede_nomemployetachenotif").style.display="none";
        $.ajax({
          url: "update_tache_receivevu_data.php",
          method: "post",
          data: {
            TacheID: ID
          },
          success: function (data) {
            if(data=="success"){
              get_notif_employe_tache();
            }
          }
        });
      },
    });
    // let ID = $(this).attr("data-id2");
    // console.log($(this).attr("id-data"));
  });
  // document.getElementById("nbtacheemployenotif").textContent=9;
}
function get_notif_employe_avance() {
  $.ajax({
    url: "view_avance_notif.php",
    method: "post",
    processData: false,
    data:{id:1},
    contentType: false,
    dataType: "JSON",
    success: function (data) {
      console.log(data);
      document.getElementById("nbavanceemployenotif").textContent=data.length;
      ch="";
      data.forEach((e)=>{
        ch+='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>'+e.status+'</h4><p>'+e.valeur+'</p><button id="show_notif_avance_detail_employe" class="btn btn-primary" type="button" id-data="'+e.id_avance+'">Details</button></div></li><li><hr class="dropdown-divider"></li>'
      })
      if(ch==""){
        ch='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>Aucune notification</h4></div></li>';
      }
      document.getElementById("avanceemployenotiflist").innerHTML=ch;
    },
  });
  $(document).on("click", "#show_notif_avance_detail_employe", function () {
    let ID = $(this).attr("id-data");
    $("#detailsemployeavancenotif").modal("show");
    $.ajax({
      url: "get_avance_data.php",
      method: "post",
      data: {
        AvanceID: ID
      },
      dataType: "JSON",
      success: function (data) {
        let span = document.getElementById("de_idemployeavancenotif");
        span.textContent = data[0];
        span = document.getElementById("de_nomemployeavancenotif");
        span.textContent = data[1]+' '+data[2];
        span = document.getElementById("de_valeuremployeavancenotif");
        span.textContent = data[3];
        span = document.getElementById("de_dateemployeavancenotif");
        span.textContent = data[4];
        span = document.getElementById("de_statusemployeavancenotif");
        span.textContent = data[5];
        $.ajax({
          url: "update_avance_receivevu_data.php",
          method: "post",
          data: {
            AvanceID: ID
          },
          success: function (data) {
            if(data=="success"){
              get_notif_employe_avance();
            }
          }
        });
      },
    });
  });
}
function get_notif_employe_conges() {
  $.ajax({
    url: "view_conges_notif.php",
    method: "post",
    processData: false,
    data:{id:1},
    contentType: false,
    dataType: "JSON",
    success: function (data) {
      console.log(data);
      document.getElementById("nbcongesemployenotif").textContent=data.length;
      ch="";
      data.forEach((e)=>{
        ch+='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>'+e.status+'</h4><p>'+e.duree+' jours</p><button id="show_notif_conges_detail_employe" class="btn btn-primary" type="button" id-data="'+e.id_conges+'">Details</button></div></li><li><hr class="dropdown-divider"></li>'
      })
      if(ch==""){
        ch='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>Aucune notification</h4></div></li>';
      }
      document.getElementById("congesemployenotiflist").innerHTML=ch;
    },
  });
  $(document).on("click", "#show_notif_conges_detail_employe", function () {
    let ID = $(this).attr("id-data");
    $("#detailsemployecongesnotif").modal("show");
    $.ajax({
      url: "get_conges_data.php",
      method: "post",
      data: {
        CongesID: ID
      },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        let span = document.getElementById("de_idemployecongesnotif");
        span.textContent = data[0];
        span = document.getElementById("de_nomemployecongesnotif");
        span.textContent = data[1]+' '+data[2];
        span = document.getElementById("de_dureeemployecongesnotif");
        span.textContent = data[3];
        span = document.getElementById("de_dateemployecongesnotif");
        span.textContent = data[4];
        span = document.getElementById("de_statusemployecongesnotif");
        span.textContent = data[5];
        $.ajax({
          url: "update_conges_receivevu_data.php",
          method: "post",
          data: {
            CongesID: ID
          },
          success: function (data) {
            if(data=="success"){
              get_notif_employe_conges();
            }
          }
        });
      },
    });
  });
}
function get_notif_employe_tache_affect() {
  console.log("affect");
  $.ajax({
    url: "get_role.php",
    method: "post",
    processData: false,
    data:{id:1},
    contentType: false,
    success: function (data) {
      if(data=="chef de projet"){
        $.ajax({
          url: "view_taches_affect_notif.php",
          method: "post",
          processData: false,
          data:{id:1},
          contentType: false,
          dataType: "JSON",
          success: function (data) {
            console.log(data);
            document.getElementById("nbtacheemployeaffectnotif").textContent=data.length;
            ch="";
            data.forEach((e)=>{
              ch+='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>'+e.titre+'</h4><p>'+e.titrep+' project</p><button id="show_notif_taches_affect_detail_employe" class="btn btn-primary" type="button" id-data="'+e.id_tache+'">Details</button></div></li><li><hr class="dropdown-divider"></li>'
            })
            if(ch==""){
              ch='<li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4>Aucune notification</h4></div></li>';
            }
            document.getElementById("tacheemployeaffectnotiflist").innerHTML=ch;
          },
        });
        $(document).on("click", "#show_notif_taches_affect_detail_employe", function () {
          let ID = $(this).attr("id-data");
          $("#detailsemployetachenotif").modal("show");
          $.ajax({
            url: "get_tache_affect_data.php",
            method: "post",
            data: {
              TacheID: ID
            },
            dataType: "JSON",
            success: function (data) {
              let span = document.getElementById("de_idemployetachenotif");
              span.textContent = data[0];
              span = document.getElementById("de_titreemployetachenotif");
              span.textContent = data[1];
              span = document.getElementById("de_descriptionemployetachenotif");
              span.textContent = data[2];
              span = document.getElementById("de_deadlineemployetachenotif");
              span.textContent = data[3];
              span = document.getElementById("de_projetemployetachenotif");
              span.textContent = data[4];
              if(data.length==9){
                span = document.getElementById("de_nomemployetachenotif");
                span.textContent = data[5]+" "+data[6];
              }else{
                document.getElementById("dede_nomemployetachenotif").style.display="none";
              }
              console.log(data);
              $.ajax({
                url: "update_tache_affect_receivevu_data.php",
                method: "post",
                data: {
                  TacheID: ID
                },
                success: function (data) {
                  if(data=="success"){
                    get_notif_employe_tache_affect();
                  }
                }
              });
            },
          });
          // let ID = $(this).attr("data-id2");
          // console.log($(this).attr("id-data"));
        });
      }else{
        document.getElementById("tacheemployeaffectnotif").style.display="none";
      }
    },
  });
}
