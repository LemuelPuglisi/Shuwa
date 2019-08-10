<?php

    namespace charlemagne\Shuwa; 

    use charlemagne\Shuwa\ProxySys as ProxySystem; 

    class Shuwa {

        protected $config; 
        protected $codes; 
        protected $proxySystem;
        protected $currentProxy;
        protected $safeMode; 

        protected $srcLang; 
        protected $tgtLang; 
        protected $request; 

        public function __construct($source = 'en', $target = 'it') {

            $this->codes = include('config/codes.php'); 
            $this->config = include('config/shuwa.php'); 

            $this->srcLang = strtolower($source); 
            $this->tgtLang = strtolower($target); 

            if ( !$this->checkLanguageCode($this->srcLang) || !$this->checkLanguageCode($this->tgtLang) ) {
                throw new Exception($this->config['ERRORS']['CODES']); 
            }

            $this->request = $this->config['API_URL'];
            $this->request = str_ireplace("SOURCE", $this->srcLang, $this->request); 
            $this->request = str_ireplace("TARGET", $this->tgtLang, $this->request); 

            $this->safeMode = $this->config['SAFE_MODE']; 
            if ($this->safeMode) {
                $this->proxySystem = new ProxySystem();
                $this->currentProxy = $this->proxySystem->fire();  
            } else $this->proxySystem = null; 

        }



        public function checkLanguageCode( $code ) {
            return in_array($code, $this->codes); 
        }



        public function setSourceLang( $langCode ) {
            if (!$this->checkLanguageCode($LangCode)) {
                throw new Exception($this->config['ERRORS']['CODES']); 
            } else $this->srcLang = $langCode; 
        }



        public function setTargetLang( $langCode ) {
            if (!$this->checkLanguageCode($LangCode)) {
                throw new Exception($this->config['ERRORS']['CODES']); 
            } else $this->tgtLang = $langCode;; 
        }



        public function getSourceLang() { return $this->srcLang; }
        public function getTargetLang() { return $this->tgtLang; }



        public function setSafeMode( bool $mode ) {
            if ($mode && $this->proxySystem === null) {
                $this->safeMode = true; 
                $this->proxySystem = new ProxySystem();
                $this->currentProxy = $this->proxySystem->fire();  
            } else $this->safeMode = false; 
        }



        public function translate( string $quote, bool $banned = false ) {

            $request = $this->request . urlencode($quote); 
            $curl = curl_init();
            if( $banned ) {
                if(!$this->safeMode) $this->setSafeMode(true); 
                curl_setopt($curl, CURLOPT_PROXY, $this->currentProxy);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, FALSE);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 600);
            }
            curl_setopt($curl, CURLOPT_URL, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($code != 200) {
              $this->currentProxy = $this->proxySystem->fire();
              curl_close($curl);
              return $this->translate($quote, true);
            }
            curl_close($curl);
            return json_decode($response, true)[0][0][0];
        
        }



    }; 

