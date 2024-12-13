<?php
if (isset($_FILES["file"]))
{
    $file = $_FILES["file"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $tipo2 = explode(".",  $nombre);
    $tipo3 =  $tipo2[1];
    $carpeta = "files/";
	$borrar_file =  $nombre;
    $nombre= str_replace(' ','_',$nombre); 
    if ($tipo != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    {
      echo "Error, el archivo no es xlsx"; 
    }
    else if ($size > 1024*1024*5)
    {
      echo "Error, el tamaño máximo permitido es un 5MB";
    }
   
    else
    {
        $src = $carpeta.$nombre;
        move_uploaded_file($ruta_provisional, $src);
        //echo "<div>$tipo</div>";
		echo "<input type='hidden' id='excelfile' name='excelfile' value='$nombre'>";
    }
	?>
	<script>
	var tienda = $('#idtienda2').val(); 
	console.log("tienda="+tienda);
	var formatNumber = {
		 separador: ".", // separador para los miles
		 sepDecimal: ',', // separador para los decimales
		 formatear:function (num){
		  num +='';
		  var splitStr = num.split('.');
		  var splitLeft = splitStr[0];
		  var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
		  var regx = /(\d+)(\d{3})/;
		  while (regx.test(splitLeft)) {
		  splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
		  }
		  return this.simbol + splitLeft  +splitRight;
		 },
		 new:function(num, simbol){
		  this.simbol = simbol ||'';
		  return this.formatear(num);
		 }
		}
	var file = "<?=$nombre?>";
	$.ajax({
			    url : 'ajax/mod_rep/read_file.php',
			    data : { 
					file: file
			    },	
			    type : 'GET',
			    dataType : 'html',
				cache: false,
			    beforeSend: function(){
					$("#loading_file").show();
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 4000
						
                    });
					
			    },		
			    success : function(data) {
					if(data){
							$("#respuesta_file").html(data);
							$("#respuesta_file").show();
							var campos1, campos2, campos3, campos4, campos5, campos6, campos7;
							$("#tabla_ventas tr").each(function (index) 
							{	$(this).css("background-color", "#ECF8E0");
								var campo1, campo2, campo3, campo4, campo5, campo6, campo7;
								
								$(this).children("td").each(function (index2) 
								{
								
									switch (index2) 
									{
										case 0: campo1 = $(this).text();
												break;
										case 1: campo2 = $(this).text();
												break;
										case 2: campo3 = $(this).text();
												break;
										case 3: campo4 = $(this).text();
												break;
										case 4: campo5 = $(this).text();
												break;
										case 5: campo6 = $(this).text();
												break;
										case 6: campo7 = $(this).text();
												break;
									}
									$(this).css("background-color", "#ECF8E0");
								})
								console.log(campo1 + ' - ' + campo2 + ' - ' + campo3);
								
								campos1 = campos1+","+campo1;
								campos2 = campos2+","+campo2;
								campos3 = campos3+","+campo3;
								campos4	= campos4+","+campo4;
								campos5	= campos5+","+campo5;
								campos6 = campos6+","+campo6;
								campos7	= campos7+","+campo7;

							})
							campos1 = campos1.split(",");
							if(campos1[4]!=tienda){
									messagebefore = new PNotify({
										title: 'Erro!',
										text: 'El archivo no pertenece a la tienda...',
										type: 'error',
										delay : 4000
										
									});
									$("#fileupload").attr("disabled",true);
									$("#error_id_tienda").show();
									$("#movertienda").val(campos1[4]);

								}else{
									$("#fileupload").attr("disabled",false);
									$("#error_id_tienda").hide();
								}
							
							campos3 = campos3.split(",");
							campos4 = campos4.split(",");
							campos5 = campos5.split(",");
							campos6 = campos6.split(",");
							campos7 = campos7.split(",");
							var pie = 0;
							var tick = 0;
							var bruto = 0.0;
							var iva = 0.0;
							var total = 0.0;
							for (var i=3; i < campos3.length; i++) {
								pie = pie + parseInt(campos3[i]);
								tick = tick + parseInt(campos4[i]);
								bruto = bruto + parseFloat(campos5[i]);
								iva = iva + parseFloat(campos6[i]);
								total = total + parseFloat(campos7[i]);
							}
							$("#piez").html(formatNumber.new(pie));
							$("#tick").html(formatNumber.new(tick));
							$("#bt").html(formatNumber.new(bruto)+" Bs");
							$("#iva").html(formatNumber.new(iva)+" Bs");
							$("#totl").html(formatNumber.new(parseFloat(total).toFixed(2))+" Bs");
							$("#datatable-footer").show();
							$("#loading_file").hide();
							//console.log(data);
							
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al procesar el archivo',
		                        text: 'Ocurrio un error en la lectura del fichero',
		                        type: 'error',
								delay : 4000
		                    });
							$("#fileupload").attr("disabled",true);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
	//$("#respuesta").load("ajax/mod_rep/read_file.php?file=<?=$nombre?>");
	</script>
	<?
	
}
