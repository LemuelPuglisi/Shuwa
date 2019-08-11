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
        'SAFE_MODE' => false,
        
        /*
        *   Process Errors
        */
        'ERRORS' => [

            'CODES' => 'Source/Target language codes must be valid.'

        ],

        /*
        *   fShuwa File configuration
        */
        'FILE' => [

            /*
            *  PHP Translation files return an array, so please don't edit this fields.
            */
            'HEADER' => "<?php \n return [ \n",
            'TRAILER' => "\n ];",

            /*
            *  Read more about target in Documentation https://github.com/LemuelPuglisi/Shuwa
            *  > file translation, targets.
            */
            'TARGET' => -1,

            /*
            * Enable quote validation
            */
            'VALIDATION' => true,

            /*
            * If you enabled the validation, then you can choose to do not
            * translate the quotes that contain html
            */
            'HTML_INTEGRITY' => true,
            
            /*
            * If you enabled the validation, then you can choose to do not
            * translate the single-word quotes
            */
            'MANTAIN_SINGLE_WORDS' => false,

            /*
            * If you enabled the validation, then you can choose to do not
            * translate the quotes that contain those words
            */
            'BLACKLIST' => [

                /*
                    example:

                    'sample1',
                    'sample2',
                    'sample2',

                    "sample1 lorem ipsum" doesn't get translated
                */

            ],

        ],
        
    ];
