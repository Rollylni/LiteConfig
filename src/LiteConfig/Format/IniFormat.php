<?php

/**
 * LiteConfig
 * 
 * @copyright 2019-2020 Rollylni
 * @author RoLLy <gdrolly@gmail.com>
 * @version 2.0.0
 * @license MIT
 */
namespace LiteConfig\Format;

use LiteConfig\Config;

class IniFormat extends Format {
    
    public function __construct() {
        parent::__construct(Config::INI_FORMAT);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string {
       return "Initialization-file(INI)"; 
    }
    
    /**
     *
     * @param string $content
     * @return (scalar|array)[]
     */
    public function read(string $content): array {
       return ($ini = parse_ini_string($content, true)) ? $ini : [];
    }
}