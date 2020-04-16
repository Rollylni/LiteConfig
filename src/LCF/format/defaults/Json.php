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

use LCF\format\defaults\utils\JsonException;
use LCF\format\Format;
use LCF\format\Writer;
use LCF\format\Parser;
use LCF\ConfigManager;

class Json extends Format implements Parser, Writer {
    
    /**
     * Json constructor
     */
    public function __construct() {
        parent::__construct(self::JSON);
        $this->setAliases(["js", "j"]);
        
        ConfigManager::setLangSettings([
            "eng" => [
                "json-error-none" => "no errors.",
                "json-error-unknown" => "Unknown error",
                "json-error-depth" => "maximum stack depth reached!",          
                "json-error-state-mismatch" => "Invalid or invalid JSON",      
                "json-error-ctrl-char" => "Control character error, possibly incorrect encoding",   
                "json-error-syntax" => "Syntax error",       
                "json-error-utf8" => "Incorrect UTF-8 characters, possibly incorrect encoding",      
                "json-error-recursion" => "One or more loop references in the encoded value",    
                "json-error-inf-or-nan" => "One or more NAN or INF value in the encoded value",       
                "json-error-unsupported-type" => "The value passed with an unsupported type!",         
                "json-error-invalid-property-name" => "The name of the property cannot be encoded.",       
                "json-error-utf16" => "Invalid UTF-16 character, possibly encoded incorrectly!"
            ],
            "rus" => [
                "json-error-unknown" => "Неизвестная ошибка",
                "json-error-depth" => "достигнута максимальная глубина стека!",
                "json-error-state-mismatch" => "Неверный или некорректный JSON",
                "json-error-ctrl-char" => "Ошибка управляющего символа, возможно неверная кодировка",
                "json-error-syntax" => "Синтаксическая ошибка",
                "json-error-utf8" => "Некорректные символы UTF-8, возможно неверная кодировка",
                "json-error-recursion" => "Одна или несколько зацикленных ссылок в кодируемом значении",
                "json-error-inf-or-nan" => "Одно или несколько значение NAN или INF в кодируемом значении",
                "json-error-unsupported-type" => "Передано значение с неподдерживаемым типом!",
                "json-error-invalid-property-name" => "Имя свойства не может быть закодировано.",
                "json-error-utf16" => "Некорректный символ UTF-16, возможно некорректно закодирован!"
            ]
        ]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return "JavaScript Object Notation";
    }
    
    /**
     * 
     * @param string $content
     * 
     * @throws JsonException
     * 
     * @return array
     */
    public function parse(string $content = "") {
        $json = json_decode($content, true);
        if($json === null or json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException($this->error());
        }
        return $json;
    }
    
    /**
     * 
     * @param array $data
     * 
     * @return string
     * 
     * @throws JsonException
     */
    public function write(array $data = []) {
        $json = json_encode($data,
            JSON_OBJECT_AS_ARRAY|JSON_PRETTY_PRINT| 
            JSON_UNESCAPED_UNICODE|JSON_BIGINT_AS_STRING|
            JSON_PRESERVE_ZERO_FRACTION
        );
        
        if(!$json or json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException($this->error());
        }
        return $json;
    }
    
    /**
     * 
     * @return string
     */
    public function error() {
        switch(json_last_error()) {
            case JSON_ERROR_DEPTH:
                $error_msg = ConfigManager::getLang("json-error-depth"); 
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error_msg = ConfigManager::getLang("json-error-state-mismatch");
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error_msg = ConfigManager::getLang("json-error-ctrl-char");
                break;
            case JSON_ERROR_SYNTAX:
                $error_msg = ConfigManager::getLang("json-error-syntax");
                break;
            case JSON_ERROR_UTF8:
                $error_msg = ConfigManager::getLang("json-error-utf8");
                break;
            case JSON_ERROR_RECURSION:
                $error_msg = ConfigManager::getLang("json-error-recursion"); 
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error_msg = ConfigManager::getLang("json-error-inf-or-nan");
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error_msg = ConfigManager::getLang("json-error-unsupported-type");
                break;
            case JSON_ERROR_INVALID_PROPERTY_NAME:
                $error_msg = ConfigManager::getLang("json-error-invalid-property-name");
                break;
            case JSON_ERROR_UTF16:
                $error_msg = ConfigManager::getLang("json-error-utf16"); 
                break;
            default:
                $error_msg = ConfigManager::getLang("json-error-unknown");
            break;
        }
        return $error_msg;
    }
}