<?php 

    return [

        /*  
        |--------------------------------------------------------------------------
        | ProxySys configuration file
        |--------------------------------------------------------------------------
        |
        */

            /*  
            *   Proxy source, the site where we scrape the proxy list
            */
            'PROXY_URL' => 'https://free-proxy-list.net/uk-proxy.html',

            /*  
            *  HTTP Proxy api, a second source in case the scraping doesn't works  
            */
            'PROXY_API' => 'https://www.proxy-list.download/api/v1/get?type=http', 

            /*  
            *  HTTPS Proxy api, a second source in case the scraping doesn't works  
            */
            'SPROXY_API' => 'https://www.proxy-list.download/api/v1/get?type=https', 

            /*  
            *   Test source, we will do some requests to Google,  
            *   btw you are free to edit the test destination
            */
            'TEST_URL' => 'https://www.google.com',
            
            /*  
            *  Enable HTTPS on Reserve proxy list.   
            */
            'RESERVE_SSL' => false, 

            /*  
            *   Time To Live of the TEST requests made to the TEST_URL server, 
            *   we need to set an optimal number to ensure that the filter()
            *   function mantain only the working proxyes
            */
            'TTL' => 1000, 

            /*  
            *  Proxy list length limit, Strongly recommend 100-200    
            */
            'LIST_LIMIT' => 100, 

            /*  
            *   Scrape the proxy list when the class is instantiated
            */
            'AUTO_SCRAPE' => true, 
            
            /*  
            *   Fitler the proxy list when the class is instantiated
            */
            'AUTO_FILTER' => true, 

            /*  
            *   Optimize the proxy list when the class is instantiated
            *   Note: please enable only if AUTO_FILTER is enabled.
            */
            'AUTO_OPTIMIZE' => true, 

            /*  
            *   Fitler the proxy list on reload
            */
            'FILTER_ON_RELOAD' => true, 
            
            /*  
            *   Optimize the proxy list on reload
            */
            'OPTIMIZE_ON_RELOAD' => true,

            /* 
            *   [Terminal mode] enable SET if you want to see what the 
            *   object is doing. 
            */
            'DISPLAY' => [

                'SET' => false, 

                'SCRAPE' => "\n[ProxySys] Filling the proxy list\n",

                'FILTER' => "\n[ProxySys] Filtering the proxy list\n", 

                'OPTIMIZE' => "\n[ProxySys] Optimizing the proxy list\n",

                'RELOAD' => "\n[ProxySys] Reloading the proxy list\n", 

                'SYS_FAULT' => "\n[ProxySys] [Breakdown] Enabling reserve list\n",

            ], 

    ]; 
