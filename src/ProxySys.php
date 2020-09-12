<?php

    namespace charlemagne\Shuwa;

    use Goutte\Client;

    class ProxySys
    {
        public $list;
        public $client;
        private $config = null;

        public function __construct()
        {
            $this->list = array();
            $this->client = new Client();
            $this->config = include("config/proxy.php");
            if ($this->config['AUTO_SCRAPE']) {
                $this->scrape();
            }
            if ($this->config['AUTO_FILTER']) {
                $this->filter();
            }
            if ($this->config['AUTO_OPTIMIZE']) {
                $this->optimize();
            }
        }


        // Settings

        public function setDisplay(bool $flag)
        {
            $this->config['DISPLAY']['SET'] = $flag;
        }

        public function setTTL(int $ttl)
        {
            $this->config['TTL'] = $ttl;
        }

        public function setReserveSSL(bool $flag)
        {
            $this->config['RESERVE_SSL'] = $flag;
        }

        public function setListLimit(int $limit)
        {
            $this->config['LIST_LIMIT'] = $limit;
        }



        public function scrape()
        {
            $this->list = array();
            if ($this->config['DISPLAY']['SET']) {
                echo $this->config['DISPLAY']['SCRAPE'];
            }
            $crawler = $this->client->request('GET', $this->config['PROXY_URL']);
            if ($this->client->getInternalResponse()->getStatusCode() !== 200) {
                if ($this->config['DISPLAY']['SET']) {
                    echo $this->config['DISPLAY']['SYS_FAULT'];
                }
                $this->supportSource($this->config['RESERVE_SSL']);
                return;
            }
            $table = $crawler->filter('tbody')->first()->filter('tr')->each(function ($tr, $i) {
                return $tr->filter('td')->each(function ($td, $i) {
                    return trim($td->text());
                });
            });
            foreach ($table as $record) {
                $newProxy = array("proxy" => $record[0].":".$record[1], "ms" => null);
                array_push($this->list, $newProxy);
            }
        }



        public function supportSource($https = false)
        {
            $request = $this->config['PROXY_API'];
            if ($https) {
                $request = $this->config['SPROXY_API'];
            }
            $reserve = file_get_contents($request);
            $line = strtok($reserve, "\n");
            $this->list = array();
            $proxyCounter = 0;
            while ($line !== false && $proxyCounter < $this->config['LIST_LIMIT']) {
                $newProxy = array("proxy" => trim($line), "ms" => null);
                array_push($this->list, $newProxy);
                $line = strtok("\n");
                $proxyCounter ++;
            }
        }



        public function filter()
        {
            if (!isset($this->list[0])) {
                $this->scrape();
            }
            if ($this->config['DISPLAY']['SET']) {
                echo $this->config['DISPLAY']['FILTER'];
            }
            foreach ($this->list as $index => &$proxy) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_PROXY, $proxy['proxy']);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, false);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 600);
                curl_setopt($curl, CURLOPT_URL, $this->config['TEST_URL']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT_MS, $this->config['TTL']);
                curl_exec($curl);
                $info = curl_getinfo($curl);
                $proxy['ms'] = floatval($info['total_time']);
                if ($proxy['ms'] >= floatval($this->config['TTL']/1000)) {
                    unset($this->list[$index]);
                }
            }
        }



        public function optimize()
        {
            if (!isset($this->list[0]) || $this->list[0]['ms'] === null) {
                $this->filter();
            }
            if ($this->config['DISPLAY']['SET']) {
                echo $this->config['DISPLAY']['OPTIMIZE'];
            }
            $this->list = array_values($this->list);
            $len = sizeof($this->list);
            usort($this->list, function ($proxy_A, $proxy_B) {
                return $proxy_A['ms'] > $proxy_B['ms'];
            });
        }



        public function reload()
        {
            if ($this->config['DISPLAY']['SET']) {
                echo $this->config['DISPLAY']['RELOAD'];
            }
            $this->scrape();
            if ($this->config['FILTER_ON_RELOAD']) {
                $this->filter();
            }
            if ($this->config['OPTIMIZE_ON_RELOAD']) {
                $this->optimize();
            }
        }



        public function fire()
        {
            if (sizeof($this->list) > 0) {
                $proxy = $this->list[0]['proxy'];
                array_splice($this->list, 0, 1);
                return $proxy;
            } else {
                $this->reload();
                $this->fire();
            }
        }
    }
