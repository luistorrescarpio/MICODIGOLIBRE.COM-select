<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="lib/bootstrap-4.0.0_lite/css/bootstrap.min.css" type="text/css">
  <title>Paginacion Get</title>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-primary navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fa d-inline fa-lg fa-list"></i> 
        <b>SELECT DATABASE (Busqueda en Base de datos)</b>
      </a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent"
        aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
    </div>
  </nav>
  <div class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-xl-9 col-md-9">
            <br>
            <h2 align="center">Buscador de Libros</h2>
            <br>
            <div class="form-inline">
              <select id='campo' class="form-control col-2">
                <option value='titulo'>Titulo</option>
                <option value='codigo'>Codigo</option>
                <option value='autor'>Autor</option>
                <option value='editorial'>Editorial</option>
              </select>
            
              <input type='text' class="form-control col-8" id='word' size='8' autofocus="true" placeholder="Ingrese texto aqui para buscar" onkeyup="if(event.keyCode==13){ searchBook(); this.blur() }" onclick="this.select()">
              
              <button onclick='searchBook()' class="
            btn btn-primary col-2">Buscar</button>
            </div>
          </div>
        </div>
        <hr>
        <div class="row justify-content-center">
          <div class="col-12 col-xl-9 col-md-9">
            <small id='message'></small>
            <table class='table table-striped'>
              <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Titulo</th>
                  <th>Autor</th>
                  <th>Editorial</th>
                  <th>Ejemplares</th>
                </tr>
              </thead>
              <tbody id="rowsResults">
                <tr>
                  <td colspan='5'>
                    <i>[Bienvenido] Ingrese una texto y presione enter o click buscar</i>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal - View Book -->
    <div class="modal fade" id="infoBook_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">DETALLE DE LIBRO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class='table'>
              <tr>
                <td>ID_LIBRO</td>
                <td id='id_libro_modal'></td>
              </tr>
              <tr>
                <td>CODIGO</td>
                <td id='codigo_modal'></td>
              </tr>
              <tr>
                <td>TITULO</td>
                <td id='titulo_modal'></td>
              </tr>
              <tr>
                <td>AUTOR</td>
                <td id='autor_modal'></td>
              </tr>
              <tr>
                <td>EDITORIAL</td>
                <td id='editorial_modal'></td>
              </tr>
              <tr>
                <td>EJEMPLARES</td>
                <td id='ejemplares_modal'></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ocultar</button>
          </div>
        </div>
      </div>
    </div>
  <script src="js/jquery-3.2.1.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/popper.min.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/bootstrap.min.js"></script>
  <script>
  	$(document).ready(function() {
      
    });
    function searchBook(){
    // Al ser accionado, anunciamos que se encuentra cargando
    $("#rowsResults").html("<tr><th colspan='5'>CARGANDO...</th></tr>");
    // Obtenemos parametros ingresados en el formulario
    var campo_in = $("#campo").val();
    var word_in = $("#word").val();
    // Traer Información de Base de datos
    $.post("query_sql.php",{
      action: "bookSearch"
      ,campo: campo_in
      ,word: word_in
    },function(res){

      console.log(res); //[Console del navegador F12] para ver los datos obtenidos de MYSQL
      
      if(res.length>0){
        $("#message").html("Se encontraron "+res.length+" resultado(s)");
        visualizarResultados(res);
      }else{
        $("#message").html("No se encontraron resultados");
        $("#rowsResults").html("<tr><th colspan='5' class='text-danger'>No se encontraron resultados</th></tr>");
      }
    },"json");
  }
  function visualizarResultados(dt){
    // Limpiamos la tabla de resultados
    $("#rowsResults").html("");
    // Estructuramos Registros para visualizar
    var dh;
    for( i in dt ){
      dh=""
      +"<tr id='row_"+dt[i].id_libro+"'>"
        +"<td>"
          +"<a href='#' onclick='infoBookModal("+dt[i].id_libro+")'>"+dt[i].codigo+"</a>"
        +"</td>"
        +"<td>"
          +"<a href='#' onclick='infoBookModal("+dt[i].id_libro+")'>"+dt[i].titulo+"</a>"
        +"</td>"
        +"<td>"+(dt[i].autor?dt[i].autor:'No especifica')+"</td>"
        +"<td>"+dt[i].editorial+"</td>"
        +"<td>"+dt[i].ejemplares+"</td>"
      +"</tr>";
      $("#rowsResults").append(dh);
    }
  }
  function infoBookModal(id){
    $.post("query_sql.php",{
      action: "getInfoBook"
      ,id_libro: id
    },function(res){
      console.log(res);
      dt = res[0]; //Solo tomamos el primer registro
      $("#id_libro_modal").html(dt.id_libro);
      $("#codigo_modal").html(dt.codigo);
      $("#titulo_modal").html(dt.titulo);
      $("#autor_modal").html(dt.autor?dt.autor:'No especifica');
      $("#editorial_modal").html(dt.editorial);
      $("#ejemplares_modal").html(dt.ejemplares);
      //Open Modal
      $("#infoBook_modal").modal("show");
    },"json");
  }
  </script>
</body>
</html>