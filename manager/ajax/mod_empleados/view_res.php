<?php 
$idc = $_GET['nik'];
?>
 <!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">-->
				<table class="table table-striped table-bordered" id="datatable-2">
					<thead>
							<th>Fecha</th>
							<th>Servicio</th>
							<th>Profesional</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
 <script src="plugins/jquery/jquery.min2.js" type="text/javascript"></script>
    <script src="plugins/jquery/jquery.dataTables2.js" charset="utf8" type="text/javascript"></script>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();

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
	  "ajax": "ajax/mod_clientes/res_script.php?ctl="+cli,
      'columns': [
		 { "data": "Fecha" },
		 { "data": "Servicio" },
		 { "data": "Profesional" }
      ]
   });
})
/*
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	var cli = <?php echo "'".$idc."'";?>;
	// $('#datatable-2').DataTable(); 
	 $('#datatable-2').DataTable( {	
					 "columns": [
					    { "data": "#" },
						{ "data": "Fecha" },
						{ "data": "Servicio" },
						{ "data": "Profesional" }
						],
					"processing": true,
					"serverSide": true,	 
				"ajax":{
					"url": 'ajax/mod_clientes/clientes_script.php',                
					"type": 'POST',
					"data": {
							cliente: 'V28987123',
							accion: 'reser'
						},
					dataType: "json",
					cache: false,
					beforeSend: function(){
							messagebefore = new PNotify({
								title: 'Procesando informaci\u00f3n',
								text: 'Por favor, espere...',
								type: 'info',
								delay : 0
								
							});
						}
					}					
					
			    });
		
});*/
</script>