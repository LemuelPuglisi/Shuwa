<?php

    namespace charlemagne\Shuwa;

    use charlemagne\Shuwa\ProxySys as ProxySystem;

    class Shuwa
    {
        protected $config;
        protected $codes;

        protected $proxySystem;
        protected $currentProxy;
        protected $safeMode;

        protected $srcLang;
        protected $tgtLang;
        protected $request;



        private function detectLangFromResponse(string $response)
        {
            return json_decode($response, 1)[2];
        }



        private function getQuoteFromResponse(string $response)
        {
            return json_decode($response, 1)[0][0][0];
        }



        public function __construct($target = 'en', $source = 'auto')
        {
            $this->codes = include('config/codes.php');
            $this->config = include('config/shuwa.php');

            $this->srcLang = strtolower($source);
            $this->tgtLang = strtolower($target);

            if (!$this->checkLanguageCode($this->srcLang) || !$this->checkLanguageCode($this->tgtLang)) {
                throw new \Exception($this->config['ERRORS']['CODES']);
            }

            $this->request = $this->config['API_URL'];
            $this->request = str_ireplace("SOURCE", $this->srcLang, $this->request);
            $this->request = str_ireplace("TARGET", $this->tgtLang, $this->request);

            $this->safeMode = $this->config['SAFE_MODE'];
            if ($this->safeMode) {
                $this->proxySystem = new ProxySystem();
                $this->currentProxy = $this->proxySystem->fire();
            } else {
                $this->proxySystem = null;
                $this->currentProxy = null; 
            }
        }



        public function checkLanguageCode($code)
        {
            return in_array($code, $this->codes);
        }



        public function setSourceLang($langCode)
        {
            if (!$this->checkLanguageCode($langCode)) {
                throw new \Exception($this->config['ERRORS']['CODES']);
            } else {
                $this->srcLang = $langCode;
            }
        }



        public function setTargetLang($langCode)
        {
            if (!$this->checkLanguageCode($langCode)) {
                throw new Exception($this->config['ERRORS']['CODES']);
            } else {
                $this->tgtLang = $langCode;
            };
        }



        public function getSourceLang()
        {
            return $this->srcLang;
        }


        
        public function getTargetLang()
        {
            return $this->tgtLang;
        }



        public function setSafeMode(bool $mode)
        {
            if ($mode && $this->proxySystem === null) {
                $this->safeMode = true;
                $this->proxySystem = new ProxySystem();
                $this->currentProxy = $this->proxySystem->fire();
            } else {
                $this->safeMode = false;
            }
        }



        public function setProxy(string $proxy) 
        {
            if (!preg_match('/^(\d[\d.]+):(\d+)\b/', $proxy)) {
                throw new \Exception($this->config['ERRORS']['PROXY']);
            }   
            else {
                $this->currentProxy = $proxy; 
            }
        }



        public function getProxy()
        {
            return $this->currentProxy; 
        }



        public function detectLangFromQuote(string $quote)
        {
            $buffer = $this->srcLang;
            $this->srcLang = "auto";
            $proxyEnable = false; 
            $this->translate($quote);
            $detectedLang = $this->srcLang; 
            $this->srcLang = $buffer;
            return $detectedLang;
        }



        public function translate(string $quote, bool $enableProxy = false)
        {
            $request = $this->request . urlencode($quote);
            $curl = curl_init();

            if ($enableProxy || $this->safeMode) {

                if($this->currentProxy === null) {
                    $this->setSafeMode(true); 
                }

                curl_setopt($curl, CURLOPT_PROXY, $this->currentProxy);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, false);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 600);
            }

            curl_setopt($curl, CURLOPT_URL, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($code != 200) {
                $this->currentProxy = null;
                curl_close($curl);
                return $this->translate($quote, true);
            }

            curl_close($curl);

            if ($this->srcLang == 'auto') {
                $this->srcLang = $this->detectLangFromResponse($response);
            }

            return $this->getQuoteFromResponse($response);
        }
    };
