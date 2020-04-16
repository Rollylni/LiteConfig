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
 
namespace LCF\format;

abstract class Format {
    
    /**
     * 
     * defaults
     * 
     * @const string
     */
    const SERIALIZE = "serialize";
    const ARRAY = "array";
    const ENUM = "enum";
    const YAML = "yaml";
    const JSON = "json";
    const INI = "ini";
    
    /**
     * 
     * @var string
     */
    protected $format = "";
    
    /**
     * 
     * @var string
     */
    protected $description = "";
    
    /**
     * 
     * @var array
     */
    protected $aliases = [];
    
    /**
     * 
     * @param string $format
     * @param string $description
     * @param array  $aliases
     */
    public function __construct($format = "", $description = null, $aliases = []) {
        $this->format = $format;
        $this->description = $description;
        $this->aliases = $aliases;
    }
    
    /**
     * 
     * @return bool
     */
    public final function isParser() {
        return $this instanceof Parser;
    }
    
    /**
     * 
     * @return bool
     */
    public final function isWriter() {
        return $this instanceof Writer;
    }
    
    /**
     * 
     * @return string
     */
    public function getFormat() {
        if(empty($this->format)) {
            return strtolower(get_class($this));
        } else {
            return $this->format;
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "Unknown";
    }
    
    /**
     * 
     * @param string $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }
    
    /**
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * 
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    
    /**
     * 
     * @return array
     */
    public function getAliases() {
        return $this->aliases;
    }
    
    /**
     * 
     * @param array $aliases
     */
    public function setAliases($aliases) {
        $this->aliases = $aliases;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->getFormat();
    }
}