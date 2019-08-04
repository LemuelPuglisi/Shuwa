<?php

    class ProxySys {

        public $list; 
        private $config = null; 

        public function __construct() {

            $this->list = array(); 
            $this->config = include("../config/proxy.php"); 
            if( $this->config['AUTO_SCRAPE'] ) $this->scrape(); 
            if( $this->config['AUTO_FILTER'] ) $this->filter(); 
            if( $this->config['AUTO_OPTIMIZE'] ) $this->optimize(); 

        }

        public function scrape() {
            
            if( $this->config['DISPLAY']['SET'] ) echo $this->config['DISPLAY']['SCRAPE']; 
            $response = file_get_contents($this->config['PROXY_URL']);
            $start = strpos($response, $this->config['TAGS']['MASS_OPEN']);
            $end = strpos($response, $this->config['TAGS']['MASS_CLOSE'], $start);
            $response = substr($response, $start, $end - $start);
            $separator = "\r\n";
            $response = str_replace($this->config['TAGS']['ENDLINE'], $this->config['TAGS']['ENDLINE'].$separator, $response);
            $line = strtok($response, $separator);
            while ($line !== false) {
                $ipSPos = strpos($line, $this->config['TAGS']['ATOMIC_OPEN']);
                $ipEPos = strpos($line, $this->config['TAGS']['ATOMIC_CLOSE'], $ipSPos);
                $portSPos = strpos($line, $this->config['TAGS']['ATOMIC_OPEN'], $ipEPos);
                $portEPos = strpos($line, $this->config['TAGS']['ATOMIC_CLOSE'], $portSPos);
                $atomTln = strlen($this->config['TAGS']['ATOMIC_OPEN']); 
                $ip = substr($line, $ipSPos + $atomTln, $ipEPos - $ipSPos - $atomTln);
                $port = substr($line, $portSPos + $atomTln, $portEPos - $portSPos - $atomTln);
                $newProxy = array("proxy" => $ip.":".$port, "ms" => null);
                array_push($this->list, $newProxy);
                $line = strtok( $separator );
            }

        }

        public function filter() {

            if(!isset($this->list[0])) $this->scrape(); 
            if( $this->config['DISPLAY']['SET'] ) echo $this->config['DISPLAY']['FILTER']; 
            foreach ($this->list as $index => &$proxy) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_PROXY, $proxy['proxy']);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, FALSE);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 600);
                curl_setopt($curl, CURLOPT_URL, $this->config['TEST_URL']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT_MS, $this->config['TTL']);
                curl_exec($curl);
                $info = curl_getinfo($curl);
                $proxy['ms'] = floatval($info['total_time']);
                if( $proxy['ms'] >= floatval($this->config['TTL']/1000) )
                  unset($this->list[$index]);
              }

        }

        public function optimize() {
            if(!isset($this->list[0]) || $this->list[0]['ms'] === null) $this->filter(); 
            if( $this->config['DISPLAY']['SET'] ) echo $this->config['DISPLAY']['OPTIMIZE'];     
            $this->list = array_values($this->list);
            $len = sizeof($this->list); 
            usort($this->list, function ($proxy_A, $proxy_B) {
                return $proxy_A['ms'] > $proxy_B['ms'];
            });
        }

        public function reload() {

            if( $this->config['DISPLAY']['SET'] ) echo $this->config['DISPLAY']['RELOAD'];     
            $this->scrape(); 
            if( $this->config['FILTER_ON_RELOAD'] ) $this->filter(); 
            if( $this->config['OPTIMIZE_ON_RELOAD'] ) $this->optimize(); 
        }

        public function fire() {
            if ( sizeof($this->list) > 0 ) {
                $proxy = $this->list[0]['proxy'];
                array_splice($this->proxyList, 0, 1);
                return $proxy;
            } else {
                $this->reload(); 
                $this->fire(); 
            }
        }

    }


