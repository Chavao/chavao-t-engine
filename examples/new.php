<?php

include '../CTemplate.php';

$objT = new CTemplate('new.html','templates');

/*
$objT = new CTemplate();
$objT->setPath('templates');
$objT->setTemplate('new.html');
*/

$linha[0] = "Zero";
$linha[1] = "One";
$linha[2] = "Two";
$linha[3] = "Three";

$teste[0] = "1";
$teste[1] = "2";
$teste[2] = "3";
$teste[3] = "4";
$teste[4] = "5";

$objT->assign('title','Test page');
$objT->assign('variavel_teste',"New value to be placed at variavel_teste");
$objT->assign('linha',$linha);
$objT->assign('teste',$teste);
$objT->display();

?>
