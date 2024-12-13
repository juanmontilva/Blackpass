<?php
function imp_mes($mes){
	
	Switch($mes){
		case 1: 
		echo "Enero";
		break;
		case 2: 
		echo "Febrero";
		break;
		case 3: 
		echo "Marzo";
		break;
		case 4: 
		echo "Abril";
		break;
		case 5: 
		echo "Mayo";
		break;
		case 6: 
		echo "Junio";
		break;
		case 7: 
		echo "Julio";
		break;
		case 8: 
		echo "Agosto";
		break;
		case 9: 
		echo "Septiembre";
		break;
		case 10: 
		echo "Octubre";
		break;
		case 11: 
		echo "Noviembre";
		break;
		case 12: 
		echo "Diciembre";
		break;
	}
}

function UltimoDia($anho,$mes){
   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) {
       $dias_febrero = 29;
   } else {
       $dias_febrero = 28;
   }
   switch($mes) {
       case 01: return 31; break;
       case 02: return $dias_febrero; break;
       case 03: return 31; break;
       case 04: return 30; break;
       case 05: return 31; break;
       case 06: return 30; break;
       case 07: return 31; break;
       case 08: return 31; break;
       case 09: return 30; break;
       case 10: return 31; break;
       case 11: return 30; break;
       case 12: return 31; break;
   }
} 
?>