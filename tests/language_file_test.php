<?php

    require_once '../vendor/autoload.php';
    require_once '../src/FShuwa.php';

    $ls = new FShuwa('en', 'IT');

    echo $ls->fileTranslate('testingFiles/input.php', 'testingFiles/output.php');  

    $result = include('testingFiles/output.php'); 
    
    var_dump($result); 