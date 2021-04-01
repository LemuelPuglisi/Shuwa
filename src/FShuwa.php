<?php

    namespace charlemagne\Shuwa;

    use charlemagne\Shuwa\Shuwa;

    class FShuwa extends Shuwa
    {
        protected $blacklist;

        public function __construct($source = 'en', $target = 'it')
        {
            parent::__construct($target, $source);
            $this->blacklist = $this->config['FILE']['BLACKLIST'];
        }

        // private class services

        private function safeTranslation($quote)
        {
            $targetToken = $this->config['FILE']['TARGET'];
            $wordsInQuote = explode(' ', $quote);
            $target = null;
            foreach ($wordsInQuote as $word) {
                if (strpos($word, $targetToken) !== false) {
                    $target = $word;
                }
            }
            $target = substr($target, 1);
            $cleanQuote = str_replace($targetToken, '', $quote);
            $translatedTarget = $this->translate($target);
            $translatedQuote = $this->translate($cleanQuote);
            $safeQuote = str_replace($translatedTarget, $targetToken.$target, $translatedQuote);
            if (strpos($safeQuote, $targetToken) !== false) {
                return $safeQuote;
            }
            return $quote;
        }



        private function innerTranslate($file, $key, $value)
        {
            if (is_array($value)) {
                fwrite($file, "'{$key}' => [\n");
                foreach ($value as $subkey => $subvalue) {
                    $this->innerTranslate($file, $subkey, $subvalue);
                }
                fwrite($file, "\n ], \n");
            } else {
                if ($this->validate($value)) {
                    $targetToken = $this->config['FILE']['TARGET'];
                    if ($targetToken != -1 && strpos($value, $targetToken) !== false) {
                        $value = $this->safeTranslation($value, true);
                    } else {
                        $value = $this->translate($value, true);
                    }
                    $value = $this->bind($value);
                    fwrite($file, "'{$key}' => '{$value}', \n");
                } else {
                    $value = $this->bind($value);
                    fwrite($file, "'{$key}' => '{$value}', \n");
                }
            }
        }
        


        public function validate($quote)
        {
            if (! $this->config['FILE']['VALIDATION']) {
                return true;
            }
            if ($this->config['FILE']['HTML_INTEGRITY'] && $quote != strip_tags($quote)) {
                return false;
            }
            if ($this->config['FILE']['MANTAIN_SINGLE_WORDS'] && strpos($quote, " ") === false) {
                return false;
            }
            
            foreach ($this->blacklist as $word) {
                if (strpos($quote, $word) !== false) {
                    return false;
                }
            }
           
            return true;
        }



        public function bind($quote)
        {
            return str_replace("'", "\\'", $quote);
        }


        public function laravelTranslation($source, $destination)
        {
            $quotes = include($source);
            $output = fopen($destination, "w");
            fwrite($output, $this->config['FILE']['HEADER']);
            foreach ($quotes as $key => $value) {
                $this->innerTranslate($output, $key, $value);
            }
            fwrite($output, $this->config['FILE']['TRAILER']);
        }



        public function codeIgniterTranslation(&$lang)
        {
            foreach ($lang as $key => $value) {
                if ($this->validate($value)) {
                    $lang[$key] = $this->bind($this->translate($value));
                }
            }
            return $lang;
        }
    };
