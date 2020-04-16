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

class Ini extends Format implements Parser, Writer {
    
    /**
     * Ini constructor
     */
    public function __construct() {
        parent::__construct(self::INI);
        $this->setAliases(["properties", "initialized"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "Initialization file";
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
        $inside_section = false;
        $this->cache = [];
        
        foreach($lines as $line) {
            $line = trim($line);
            
            if(!$line or $line[0] == "#" or $line[0] == ";") {
                continue;
            }
            
            if($line[0] == "[" && $endIdx = strpos($line, "]")) {
                $inside_section = substr($line, 1, $endIdx - 1);
                continue;
            }
            
            if(!strpos($line, "=")) {
                continue;
            }
            
            $tmp = explode("=", $line, 2);
            
            if($inside_section) {
                $key = rtrim($tmp[0]);
                $value = ltrim($tmp[1]);
                
                if(preg_match("/^\".*\"$/", $value) or preg_match("/^'.*'$/", $value)) {
                    $value = substr($value, 1, strlen($value) - 2);
                }
                
                $t = preg_match("^\[(.*?)\]^", $key, $matches);
                if(!empty($matches) and isset($matches[0])) {
                    $arr_name = preg_replace('#\[(.*?)\]#is', '', $key);
                    
                    if(!isset($content[$inside_section]) or !is_array($content[$inside_section][$arr_name])) {
                        $content[$inside_section][$arr_name] = [];
                    }
                    
                    if(isset($matches[1]) and !empty($matches[1])) {
                        $content[$inside_section][$arr_name][$matches[1]] = $value; 
                    } else {
                        $content[$inside_section][$arr_name][] = $value;
                    }
                }
                else{
                    $content[$inside_section][trim($tmp[0])] = $value;
                }
            }
            else{
                $content[trim($tmp[0])] = ltrim($tmp[1]);
            }
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
            if(is_array($section = $value)) {
                $content .= "[".$key."]\n";
                foreach($section as $_key => $_value) {
                    if(is_array($_value)) {
                        if($_value !== [] and array_keys($_value) !== range(0, sizeof($_value) - 1)) {
                            foreach($_value as $k => $v) {
                                $content .= $_key."[".$k."]"."=".$v."\n";
                            }
                        } else {
                            foreach($_value as $v) {
                                $content .= $_key."[]"."=".$v."\n";
                            }
                        }
                    } else {
                        $content .= $_key."=".$_value."\n";
                    }
                }
            } else {
                $content .= $key."=".$value."\n";
            }
        }
        return $content;
    }
}