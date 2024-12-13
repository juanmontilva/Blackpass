 $(document).ready(function() {
 	 $("#fact").load("response.php");
   var refreshId = setInterval(function() {
      $("#fact").load('response.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
});
 $(document).ready(function() {
$("#ingreso").load("monitoringreso.php");
   var refreshId = setInterval(function() {
      $("#ingreso").load('monitoringreso.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
}); 
 $(document).ready(function() {
$("#hora").load("monitorhora.php");
   var refreshId = setInterval(function() {
      $("#hora").load('monitorhora.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
});
 $(document).ready(function() {
$("#ncliente").load("monitorncliente.php");
   var refreshId = setInterval(function() {
      $("#ncliente").load('monitorncliente.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
});