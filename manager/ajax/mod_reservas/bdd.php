<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=bd_ebs_bot;charset=utf8', '24hopenv', 'GkP6bxloBYcSDMk3');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}
