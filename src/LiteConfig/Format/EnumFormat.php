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

class EnumFormat extends Format {
    
    public function __construct() {
        parent::__construct(Config::ENUM_FORMAT, ["list", "ls"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string {
       return "Enumeration-list(ENUM)"; 
    }
    
    /**
     *
     * @param string $content
     * @return (scalar|array)[]
     */
    public function read(string $content): array {
        $lines = explode(PHP_EOL, $content);
        $content = [];
        foreach ($lines as $line) {
           $line = trim($line);
           if ($line) {
               $content[$line] = true;
           }
        }
        return $content;
    }

    /**
     *
     * @param (scalar|array)[] $content
     * @return string
     */
    public function write(array $content): string {
        $data = "";
        foreach ($content as $line => $b) {
            $data .= $line . PHP_EOL;
        }
        return $data;
    }
}