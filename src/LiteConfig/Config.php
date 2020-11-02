<?php

/**
 * LiteConfig
 * 
 * @copyright 2019-2020 Rollylni
 * @author RoLLy <gdrolly@gmail.com>
 * @version 2.0.0
 * @license MIT
 */
namespace LiteConfig;

use LiteConfig\Format\Format;
use LiteConfig\Format\IniFormat;
use LiteConfig\Format\EnumFormat;
use LiteConfig\Format\YamlFormat;
use LiteConfig\Format\JsonFormat;
use LiteConfig\Format\SerializedFormat;
use LiteConfig\Exception\FormatException;
use LiteConfig\Exception\ConfigException;

use function pathinfo;
use Throwable;

class Config {
    
    /**
     * 
     * @var Format[]
     */
    private static $formats = [];
    
    public const SERIALIZED_FORMAT = "serialize";
    public const YAML_FORMAT = "yaml";
    public const JSON_FORMAT = "json";
    public const ENUM_FORMAT = "enum";
    public const INI_FORMAT  = "ini";
    
    /**
     * Detect format by Extension
     * 
     * @var null
     */
    public const DETECT_FORMAT = null;
    
    /**
     * 
     * @var mixed[]
     */
    public $content = [];
    
    /**
     *
     * @var string 
     * @var Format
     */
    private $file, $format;
    
    /**
     * 
     * @param string $file
     * @param string|null $format
     * @throws FormatException
     */
    public function __construct($file, $format = self::DETECT_FORMAT) {
        $this->file = $file;
        $this->setFormat($format);
    }
    
    /**
     * 
     * @param string|null $format
     * @throws FormatException
     */
    public function setFormat($format) {
        if ($format === self::DETECT_FORMAT) {
            $format = pathinfo($this->getFile())["extension"] ?? null;
        }
        $this->loadFormats();
        $f = $this->getFormat($format);
        if (!$f) {
            throw new FormatException("format '$format' does not exist!");
        }
        $this->format = $f;
    }
    
    /**
     * 
     * @param array $default
     * @throws ConfigException
     */
    public function load($default = []) {
        if (!file_exists($this->getFile()) or !$this->getCFormat()->isReadable()) {
            $this->content = $default;
            return;
        }
        
        try {
            $contents = $this->getFileContents();
            if (!$contents) {
                throw new ConfigException("can not open the file");
            }
            $this->content = $this->getCFormat()->read($contents) ?? $default;
        } catch (Throwable $ex) {
            throw new ConfigException("Load error: " .$ex->getMessage(). " => \"" .$this->getFile(). "\"");
        }
    }
    
    /**
     * 
     * @throws ConfigException
     */
    public function save() {
        try {
            file_put_contents($this->getFile(), $this->getCFormat()->write($this->getContent()), LOCK_EX);
        } catch (Throwable $ex) {
            throw new ConfigException("Save error: " .$ex->getMessage(). " => \"" .$this->getFile(). "\"");
        }
    }
    
    /**
     * 
     * @param string $key
     * @param scalar|array $value
     */
    public function set($key, $value = true) {
        $this->content[$key] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = false) {
        return $this->content[$key] ?? $default;
    }
    
    /**
     * 
     * @param string $key
     * @return self
     */
    public function remove($key) {
        unset($this->content[$key]);
        return $this;
    }
    
    /**
     * 
     * @param string $key
     * @return bool
     */
    public function exists($key) {
        return isset($this->content[$key]);
    }
    
    /**
     * 
     * @return self
     */
    public function sort() {
        sort($this->content);
        return $this;
    }
    
    /**
     * 
     * @param array $content
     */
    public function setContent(array $content = []) {
        $this->content = $content;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getContent() {
        return $this->content;
    }
    
    /**
     * 
     * @return string
     */
    public function getFileContents() {
        return file_get_contents($this->getFile());
    }
    
    /**
     * 
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Gets Config Format
     * 
     * @return Format
     */
    public function getCFormat() {
        return $this->format;
    }
    
    public static function loadFormats() {
       $formats = [
           new SerializedFormat(),
           new YamlFormat(),
           new JsonFormat(),
           new EnumFormat(),
           new IniFormat(),
       ];
       
       /** @var Format $format*/
       foreach ($formats as $format) {
           if (!static::isFormat($format)) {
               static::addFormat($format);
           }
       }
    }
    
    /**
     * 
     * @param string $format
     * @return Format|null
     */
    public static function getFormat($format): ?Format {
        return static::$formats[$format] ?? null;
    }
    
    /**
     * 
     * @param string|Format $format
     */
    public static function isFormat($format) {
        if ($format instanceof Format) {
            $format = $format->getFormat();
        }
        return isset(static::$formats[$format]);
    }
    
    /**
     * 
     * @param Format $format
     */
    public static function addFormat(Format $format) {
        static::removeFormat($format);
        static::$formats[$format->getFormat()] = $format;
        foreach ($format->getAliases() as $alias) {
            if (!static::isFormat($alias)) {
                static::$formats[$alias] = $format;
            } else {
                $format->remAlias($alias);
            }
        }
    }
    
    /**
     * 
     * @param string|Format $format
     */
    public static function removeFormat($format) {
        if (is_string($format)) {
            $format = static::getFormat($format);
        }
        
        if ($format instanceof Format && static::isFormat($format)) {
            unset(static::$formats[$format->getFormat()]);
            foreach ($format->getAliases() as $alias) {
                if (static::isFormat($alias)) {
                    unset(static::$formats[$alias]);
                }
            }
        }
    }
}