<?php 
$idc = $_GET['nik'];
?>
 <script type="text/javascript">

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