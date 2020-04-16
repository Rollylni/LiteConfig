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
 
namespace LCF\format\defaults;

use LCF\format\Format;
use LCF\format\Writer;
use LCF\format\Parser;

class Enum extends Format implements Parser, Writer {
    
    /**
     * Enum constructor
     */
    public function __construct() {
        parent::__construct(self::ENUM);
        $this->setAliases(["text", "list", "txt", "enumeration", "log", "etf"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "Enumeration Text File";
    }
    
    /**
     * 
     * @param string $content
     * 
     * @return array
     */
    public function parse(string $content = "") {
        $lines = explode("\n", $content);
        $content = [];
        
        foreach($lines as $line) {
            $line = trim($line);
            
            if(empty($line)) {
                continue;
            }
            $content[$line] = true;
        }
        return $content;
    }
    
    /**
     * 
     * @param array $data
     * 
     * @return string
     */
    public function write(array $data = []) {
        $content = "";
        foreach($data as $key => $value) {
            $content .= (string) $key."\n";
        }
        return $content;
    }
}