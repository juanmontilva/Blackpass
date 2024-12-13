<?php 
$idc = $_GET['nik'];
?>
 <!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">-->
				<table class="table table-striped table-bordered" id="datatable-2">
					<thead>
							<th>Mes</th>
							<th>Facturado</th>
							<th>Servicios</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>


<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable2();
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Search');
		
		
	});
}
$(document).ready(function(){
	var cli = <?php echo "'".$idc."'";?>;
   $('#datatable-2').DataTable({
      "processing": true,
	  "sPaginationType": "bootstrap",
		"oLanguage": {
			  "sSearch": "Buscar",
			  "sLengthMenu": "Mostrar _MENU_ Por paginas",
			  "sZeroRecords": "Sin resultados encontrados",
			  "sInfo": "Mostrar _START_ de _END_ hasta _TOTAL_ registros",
			  "sInfoEmpty": "Mostrando 0 de 0 hasta 0 registros",
			  "sInfoFiltered": "(filtered from _MAX_ total registros)"
			},
	  "ajax": "ajax/mod_clientes/res_script.php?accion=2&ctl="+cli,
      'columns': [
		 { "data": "Mes" },
		 { "data": "Facturado" },
		 { "data": "Servicios" }
      ]
	  
   });
})

</script>