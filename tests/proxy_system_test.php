<?php

    require_once '../vendor/autoload.php';
    require_once '../src/ProxySys.php';
    
    $ps = new ProxySys(); 

    $ps->setReserveSSL(true); 
    $ps->setListLimit(20); 
    $ps->supportSource();
    $ps->filter(); 
    $ps->optimize();  

    print_r($ps->list); 

    print $ps->fire(); 