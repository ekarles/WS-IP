<?php 

header('Content-type: text/xml');


$file = file_get_contents('DNM_INTI_WSService.wsdl');
echo $file;


?>