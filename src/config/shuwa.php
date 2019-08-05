<?php

    return [

        /*  
        |--------------------------------------------------------------------------
        | Google translate free API url
        |--------------------------------------------------------------------------
        |
        |
        */

        /*  
        *   Google API request
        */
        'API_URL' => 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=SOURCE&tl=TARGET&dt=t&q=',

        /*  
        *   Enable this mode if you need to do a lot of requests, in order to not be 
        *   temporary ip-banned from Google. It might take a while, but it's 100% sure.  
        */
        'SAFE_MODE' => true,
        
        /*  
        *   Process Errors
        */
        'ERRORS' => [

            'CODES' => 'Source/Target language codes must be valid.'

        ], 

    ]; 