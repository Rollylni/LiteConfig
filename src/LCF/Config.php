<?php

/*
 *   ____          _  _          _           _
 *  |  _ \   ___  | || | \ \/ / | |  _ _    (_)
 *  | |_) ) / _ \ | || |  \  /  | | | '_ \  | |
 *  |  __ \| (_) || || |  / /   | | | | | | | |
 *  |_|  \_\\___/ |_||_| /_/    |_| |_| |_| |_|
 *                                               
 *  Copyright (c) september 2019 Rolly lni <vk.com/rollylni>
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 */ 
 
namespace LCF; 

use LCF\format\Format;
use LCF\format\Parser;
use LCF\format\Writer;

class Config {
    
    /**
     * 
     * @var string
     */
    public $file = "";
    
    /**
     * 
     * @var array
     */
    public $data = [];
    
    /**
     * 
     * @var array
     */
    protected $info = [];
    
    /**
     * 
     * @var array
     */
    public $default = [];
    
    /**
     * 
     * @var Format
     */
    private $format = null;
    
    /**
     * 
     * @var string
     */
    public $extension = null;
    
    /**
     * 
     * @param string $msg
     */
    protected function log($msg) {
        $_msg = date("[H:i:s]");
        $_msg .= "[Config";
        if($this->getFilename() !== null) {
            $_msg .= "/".$this->getFilename();
        }
        $_msg .= "] ".$msg;
        echo $_msg.PHP_EOL;
    }
    
    /**
     * 
     * Config constructor
     * 
     * @param string $file
     * @param array  $default
     */
    public function __construct($file, array $default = []) {
        if(!ConfigManager::inited()) {
            ConfigManager::init();
        }
        
        try {
            $this->setFile($file, $default);
        } catch(\Throwable $e) {
            $this->log("Error: ".$e->getMessage()." => ".$file);
            $this->clear();
        }
    }
    
    /**
     * 
     * @param string $file
     * @param array $default
     */
    public function setFile($file, $default = []) {
        $this->clear();
        $this->file = $file;
        $this->info = pathinfo($file);
        $this->default = $default;
        $this->format = ConfigManager::getFormat($this->getExtension());
        
        if($this->getFormat() === null) {
            throw new ConfigException(ConfigManager::getLang("format-not-found", $this->getFile()));
        }
        $this->setFormat();
        if($this->getFormat()->isParser()) {
            $this->read();
        } else {
            $this->data = $this->getDefault();
        }
    }
    
    /**
     * 
     * read a content
     */
    public function read() {
        try {
            if(!($this->getFormat() instanceof Parser)) {
                throw new ConfigException(ConfigManager::getLang("isnot-readable"));
            }
            
            if(file_exists($this->getFile())) {
                $this->data = $this->getFormat()->parse(file_get_contents($this->getFile()));
            } else {
                $this->data = $this->getDefault();
            }
        } catch(\Throwable $e) {
            $this->log(ConfigManager::getLang("config-parse-error"));
            $this->log("Parse error: ".$e->getMessage()." => ".$this->getFile());
        }
    }
    
    /**
     * 
     * write data
     */
    public function save() {
        try {
            if(!($this->getFormat() instanceof Writer)) {
                throw new ConfigException(ConfigManager::getLang("isnot-writeable"));
            }
            file_put_contents($this->getFile(), $this->getFormat()->write($this->getData()), LOCK_EX);
        } catch(\Throwable $e) {
            $this->log(ConfigManager::getLang("config-write-error"));
            $this->log("Write error: ".$e->getMessage()." => ".$this->getFile());
        }
    }
    
    /**
     * 
     * @return array
     */
    public function getAll() {
        return $this->getData();
    }
    
    /**
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value = true) {
        $this->data[$key] = $value;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed  $default
     * 
     * @return mixed
     */
    public function get($key, $default = false) {
        return $this->data[$key] ?? $default; 
    }
    
    /**
     * 
     * @param string key
     */
    public function remove($key) {
        unset($this->data[$key]);
    }
    
    /**
     * 
     * @param string $key
     *
     * @return bool
     */
    public function exists($key) {
        return isset($this->data[$key]);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function setNested($key, $value = true) {
        $keys = explode(".", $key);
        $cache = '$this->data';

        foreach($keys as $key) {
            $cache .= '['.$key.']';
        }
        $value = var_export($value, true);
        try {
            eval($cache."=".$value.";");
        } catch(\Throwable $e) {
            $this->log("Nested error: ".$e->getMessage()." => ".$this->getFile());
        }
    }
    
    /**
     * 
     * @param string $key
     * @param mixed  $value
     * 
     * return mixed
     */
    public function getNested($key, $default = false) {
        $keys = explode(".", $key);
        $cache = 'return $this->data';

        foreach($keys as $_key) {
            $cache .= '['.$_key.']';
        }
        try {
            $cache = eval($cache."??".var_export($default, true).";");
        } catch(\Throwable $e) {
            $this->log("Nested error: ".$e->getMessage()." => ".$this->getFile());
            return $default;
        }
        return $cache;
    }
    
    /**
     * 
     * @param string $key
     */
    public function removeNested($key) {
        $keys = explode(".", $key);
        $cache = 'unset($this->data';
        
        foreach($keys as $_key) {
            $cache .= '['.$_key.']';
        } try {
            eval($cache.");");
        } catch(\Throwable $e) {
            $this->log("Nested error: ".$e->getMessage()." => ".$this->getFile());
        }
    }
    
    /**
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }
    
    /**
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }
    
    /**
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function __isset($key) {
        return $this->exists($key);
    }
    
    /**
     * 
     * @param string $key
     */
    public function __unset($key) {
        $this->remove($key);
    }
    
    /**
     * 
     * @return array
     */
    public function __invoke() {
        return $this->getAll();
    }
    
    /**
     * 
     * @param string $format
     */
    public function setFormat($format = null) {
        $this->extension = $format;
    }
    
    /**
     * 
     * @param array $def
     */
    public function setDefault($def = []) {
        $this->default = $def;
    }
    
    /**
     * 
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * 
     * @return array
     */
    public function getDefault() {
        return $this->default;
    }
    
    /**
     * 
     * @return array
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * 
     * @return Format|null
     */
    public function getFormat() {
        return $this->format;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getExtension() {
        return $this->info["extension"] ?? $this->extension;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getDirname() {
        return $this->info["dirname"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getFilename() {
        return $this->info["filename"] ?? null;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getBasename() {
        return $this->info["basename"] ?? null;
    }
    
    /**
     * 
     * clear data
     */
    public function clear() {
        $this->file = "";
        $this->info = [];
        $this->data = [];
        $this->format = null;
    }
}