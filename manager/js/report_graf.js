var serie_pie ="";
$(document).ready(function(){
	
	(function(bi_graf, $, undefined){
		
		
	bi_graf.carga_vent_pais = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_bi/list_view_pais.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}		
		
	bi_graf.const_pais_label  = function(e){	
				$.ajax({
			    url : 'ajax/mod_bi/bi_script.php',
			    data : { 
			    	accion: 'label_pais'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#loading_home6").show();
			    	messagebefore = new PNotify({
                        title: 'Consultando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					$("#loading_home6").hide();
					if(json.response=='ok'){
							var ventas = [];	
							var inof = [];	
							
							var total_piez = 0;
							
							var contar = 0;
							var total_v = 0;
									$.each(json.infov, function(e,abre){
											var resultado =json.infov[e].vendido;
											var total_camp2 = 0;
											var total_pro = 0;
											total_camp2 =  parseFloat(resultado);
											total_v = total_v + total_camp2;
											total_pro =  parseFloat(json.infov[e].promedio);
											var cade_graf_pais = "";
											cade_graf_pais = '<div class="col-sm-3">'+
											'<div class="box box-pricing">'+
											'	<div class="box-header">'+
											'		<div class="box-name">'+
											'			<span><b>'+number_format(total_camp2,2)+' '+json.infov[e].moneda+'</b><br/>';
											$.each(json.infop, function(d,abre){
												
												if(json.infop[d].cod==json.infov[e].cod){
													contar = contar+1;
												cade_graf_pais = cade_graf_pais +' '+json.infop[d].abre+' <img class="img-rounded" src="'+json.infop[d].bandera+'"  ></span>'+
													'		</div>'+
													'		<div class="no-move"></div>'+
													'	</div>'+
													'	<div class="box-content no-padding">'+
													'		<div class="row-fluid centered">'+
													'			<div class="col-sm-12">Marcas: '+json.infop[d].marcas+'</div>'+
													'			<div class="col-sm-12">Tiendas: '+json.infop[d].tiendas+'</div>';
													
														$.each(json.infov_m, function(f,abre){
															if(json.infov_m[f].cod==json.infov[d].cod){
															cade_graf_pais = cade_graf_pais +
																'<div class="col-sm-12">Mes: '+number_format(parseFloat(json.infov_m[f].vendido),2)+' '+json.infov[e].moneda+'</div>';
																
																}
															 });
													cade_graf_pais = cade_graf_pais +
													'			<div class="clearfix"></div>'+
													'		</div>';
													}
												 });
												cade_graf_pais = cade_graf_pais +
												'		<div class="row-fluid bg-default">'+
												'			<div class="col-sm-6"><b>AVG:</b> '+number_format(total_pro,2)+' '+json.infov[e].moneda+'/Mo</div>'+
												'			<div class="col-sm-6">'+
												'				<a class="ajax-link" style="cursor:pointer" onclick="bi_graf.carga_vent_pais('+json.infov[e].cod+');"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" ><button type="button" class="btn btn-primary btn-block">Explorar</button></a>'+
												'			</div>'+
												'			<div class="clearfix"></div>'+
												'		</div>'+
												'	</div>'+
												'</div>'+
												'</div>';	
											$("#label_graf_pais").append(cade_graf_pais);
											cade_graf_pais = "";
									
											//contar = 0;
									});
									var c1= 0;
									$.each(json.infov, function(g,abre){
									var result  = resultado =json.infov[g].vendido;
									result =  parseFloat(result);
											serie_pie = serie_pie + '{name: "'+json.infop[g].abre+'",'+
											'y: '+(result*100)/total_v+'},';
											var porc = (result*100)/total_v;
										});
									serie_pie = serie_pie.substring(0, serie_pie.length-1);	
								
							
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al Asignar',
		                        text: 'Ocurrio un error asignando la venta',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
	
}
  }(window.bi_graf = window.bi_graf || {}, jQuery));
  
  });	
function graficar_pais(){
	var dias ="";
	/* 
	
	$.ajax({
			   //url : '/dshopretail/datos.php',
				url : 'ajax/mod_bi/bi_script.php',
			    data : { 
			    	accion: 'group_pie_pais'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	$("#loading_home7").show();
			    },		
			    success : function(json) {
					$("#loading_home4").hide();
					if(json.response=='ok'){
						$("#loading_home7").hide();
								 options.series[0].data = json.data1;
								 options.series[0].name = json.pais1;
								 options.series[1].name = json.pais2;
								 options.series[1].data = json.data2;

						 for(l=1;l<=json.dias;l++){
							 dias =  dias+""+l+",";
							
						 }
						dias = dias.substring(0, dias.length-1);
							dias = "["+dias+"]";
						console.log(dias);
						 var chart = new Highcharts.Chart(options);
												
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
    var options = {
        chart: {
            renderTo: 'pie_gra_pais2',
            type: 'column'
        },
		title: {
            text: 'Ventas del Mes por Pais',
            x: -20 //center
        },
		xAxis: {
            categories: [dias]
        },
        series: [{},{}]
    };
*/
}
