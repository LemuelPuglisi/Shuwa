<?php

    class Shuwa {

        // proxy system attributes
        private $config; 
        private $proxySys;
        private $currentProxy;
        
        // translator attributes
        public $srcLang; 
        public $tgtLang; 
        
        public function __construct($source = "en", $target = "it") {
            $this->srcLang = $source; 
            $this->tgtLang = $target; 
            // $this->proxySys = new ProxySys(); 
            // $this->currentProxy = $this->proxySys->fire(); 
            $this->config = include('../config/config.php'); 
        }

        // set methods 
        public function setSourceLang( $LangCode ) { $this->srcLang = $ln; }
        public function setTargetLang( $LangCode ) { $this->tgtLang = $ln; }
   
        //get methods 
        public function getSourceLang() { return $this->srcLang; }
        public function getTargetLang() { return $this->tgtLang; }

        
    }

