<?php

    require_once '../vendor/autoload.php';

    use charlemagne\Shuwa\Shuwa;    
    use charlemagne\Shuwa\FShuwa;

    $ls = new Shuwa('en', 'it');

    $ls->setSafeMode(true); 

    // echo $ls->fileTranslate('testingFiles/input.php', 'testingFiles/output.php');  

    // $result = include('testingFiles/output.php'); 
    
    // var_dump($result); 

