<?php 

    return [

        /*  
        |--------------------------------------------------------------------------
        | Google translate free API url
        |--------------------------------------------------------------------------
        |
        | Remember that the number of requests with the same IP address are limited.
        | So we require a proxy system.
        |
        */

         'API_URL' => 'https://translate.googleapis.com/translate_a/single?client=gtx&',
         
        /*  
        |--------------------------------------------------------------------------
        | Safe mode
        |--------------------------------------------------------------------------
        |
        | Enable the proxy system at the bootstrap and prevent your IP to be banned.
        |
        */

        'SAFE_MODE' => true,
        
         
    ]; 