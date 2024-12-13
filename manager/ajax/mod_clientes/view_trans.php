<?php 
$idc = $_GET['nik'];
?>

<!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">-->
				<table class="table table-striped table-bordered" id="datatable-4">
					<thead>
						<th id="periodo">Fecha</th>
						<th>Comprobante</th>
						<th>Monto</th>
						<th>Localidad</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!--<link href="plugins/bootstrap/bootstrap-theme.css" rel="stylesheet">-->
<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="plugins/select2/select2.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/jquery/jquery.dataTables2.js" charset="utf8" type="text/javascript"></script>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable4();
}

$(document).ready(function(){

	var cli = <?php echo "'".$idc."'";?>;
   var eq = $('#datatable-4').DataTable({
      "processing": false,
	  "serverSide": false,
	   "PaginationType": "bootstrap",
	  language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
	  "ajax": "ajax/mod_clientes/res_script.php?accion=3&ctl="+cli,
      'columns': [
		 { "data": "Fecha" },
		 { "data": "Orden" },
		 { "data": "Monto" },
		 { "data": "Comercio" }
	],
		error: function(error){
			    	console.log(error);
		}
   });
$('#datatable-4').DataTable().ajax.reload(); 
})

</script>