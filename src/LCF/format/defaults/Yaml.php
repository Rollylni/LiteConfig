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

use LCF\format\defaults\utils\YamlException;
use LCF\format\Format;
use LCF\format\Writer;
use LCF\format\Parser;
use LCF\ConfigManager;

class Yaml extends Format implements Parser, Writer {
    
    /**
     * Yaml constructor
     */
    public function __construct() {
        if(!extension_loaded("yaml")) {
            if(ConfigManager::isFormat($this)) {
                ConfigManager::removeFormat($this);
            }
            throw new YamlException("For the Yaml format to work, the YAML extension is required");
        }
        
        parent::__construct(self::YAML);
        $this->setAliases(["yml", "y"]);
        
        ConfigManager::setLangSettings([
            "eng" => [
                "yaml-parse-error" => "An error occurred while converting the YAML stream",
            ],
            "rus" => [
                "yaml-parse-error" => "Не удалось обработать YAML: Произошла ошибка при конвертации YAML-потока!",
            ]
        ]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "Yet Another Markup Language";
    }
    
    /**
     * 
     * @param string $content
     * 
     * @return array
     * 
     * @throws YamlException
     */
    public function parse(string $content = "") {
        $yaml = yaml_parse($content);
        if(!$yaml) {
            throw new YamlException(ConfigManager::getLang("yaml-parse-error"));
        }
        return $yaml;
    }
    
    /**
     * 
     * @param array $data
     * 
     * @return string
     */
    public function write(array $data = []) {
        return yaml_emit($data);
    }
}