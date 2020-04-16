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

use LCF\format\defaults\Serialize;
use LCF\format\defaults\ArrayFormat;
use LCF\format\defaults\Enum;
use LCF\format\defaults\Yaml;
use LCF\format\defaults\Json;
use LCF\format\defaults\Ini;
use LCF\format\Format;

final class ConfigManager {
    
    /**
     * init
     * 
     * @var bool
     */
    private static $inited = false;
    
    /**
     * 
     * added formats
     * 
     * @var array
     */
    private static $formats = [];
    
    /**
     * 
     * language settings
     * 
     * @var array
     */
    private static $languages = [
        "eng" => [],
        "rus" => []
    ];
    
    /**
     * 
     * current language
     * 
     * @var string
     */
    public static $language = "eng";
    
    /**
     * init
     * 
     * @param string $lang
     */
    public static function init($lang = "eng") {
        if(!static::$inited) {
            self::setLangSettings([
                "eng" => [
                    "format-not-found" => "There is no suitable format for config", 
                    "config-parse-error" => "Failed to process the file!", 
                    "config-write-error" => "Failed to write data!",
                    "isnot-writeable" => "The format is not writable!", 
                    "isnot-readble" => "The format is not intended to be read!"
                ],
                "rus" => [
                    "format-not-found" => "Нет подходящего формата для конфига",
                    "config-parse-error" => "Не удалось обработать файл!",
                    "config-write-error" => "Не удалось записать данные!",
                    "isnot-writeable" => "Формат не является записываемым!",
                    "isnot-readble" => "Формат не предназначен для чтения!"
                ]
            ]);
            self::setLang($lang);
            self::addFormat(new Serialize());
            self::addFormat(new ArrayFormat());
            self::addFormat(new Enum());
            self::addFormat(new Yaml());
            self::addFormat(new Json());
            self::addFormat(new Ini());
            static::$inited = true;
        }
    }
    
    /**
     * init
     * 
     * @return bool
     */
    public static function inited() {
        return static::$inited;
    }
    
    /**
     * 
     * set current language
     * 
     * @param string $lang
     */
    public static function setLang($lang = "eng") {
        if(isset(self::$languages[$lang])) {
            self::$language = $lang;
        }
    }
    
    /**
     * 
     * set language settings or create a new language
     *
     * @param array  $settings
     */
    public static function setLangSettings(array $settings = []) {
        foreach($settings as $lang => $stgs) {
            if(is_array($stgs) && is_string($lang)) {
                if(isset(self::$languages[$lang])) {
                    self::$languages[$lang] += $stgs;
                } else {
                    self::$languages[$lang] = $stgs;
                }
            }
        }
    }
    
    /**
     * 
     * @param string $key
     * @param array  $vars
     * 
     * @return string
     */
    public static function getLang($key, ...$vars) {
        if(isset(self::$languages[self::$language][$key])) {
            $value = self::$languages[self::$language][$key];
            
            $i = 0;
            foreach($vars as $var) {
                $value = str_replace('%'. $i, $var, $value);
                $i++;
            }
            return $value;
        }
        return "";
    }
    
    /**
     * 
     * @param Format $format
     */
    public static function addFormat(Format $format) {
        self::$formats[$format->getFormat()] = $format;
        foreach($format->getAliases() as $alias) {
            if(!self::isFormat($alias)) {
                self::$formats[$alias] = $format; 
            }
        }
    }
    
    /**
     * 
     * @param Format $format
     */
    public static function removeFormat(Format $format) {
        unset(self::$formats[$format->getFormat()]);
        foreach($format->getAliases() as $alias) {
            if(self::isFormat($alias)) {
                unset(self::$formats[$alias]); 
            }
        }
    }
    
    /**
     * 
     * @return array
     */
    public static function getFormats() {
        return self::$formats;
    }
    
    /**
     * 
     * @param string $format
     *
     * @return Format|null
     */
    public static function getFormat($format) {
        if(self::isFormat($format)) {
            return self::$formats[$format];
        }
        return null;
    }
    
    /**
     * 
     * @param string $format
     * 
     * @return bool
     */
    public static function isFormat($format) {
        return isset(self::$formats[$format]);
    }
}