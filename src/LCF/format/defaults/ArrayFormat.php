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

use LCF\format\defaults\utils\ArrayException;
use LCF\format\Format;
use LCF\format\Writer;
use LCF\format\Parser;

class ArrayFormat extends Format implements Parser, Writer {
    
    /**
     * Array constructor
     */
    public function __construct() {
        parent::__construct(self::ARRAY);
        $this->setAliases(["arr", "arp"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "PHP Array File";
    }
    
    /**
     * 
     * @param string $content
     * 
     * @return array
     * 
     * @throws ArrayException
     */
    public function parse(string $content = "") {
        try {
            $data = eval("return $content;");
        } catch(\Throwable $e) {
            throw new ArrayException($e->getMessage()." line ".$e->getLine());
        }
        return $data;
    }
    
    /**
     * 
     * @param array $data
     * 
     * @return string
     */
    public function write(array $data = []) {
        return var_export($data, true);
    }
}