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

use LiteConfig\Exception\FormatException;

abstract class Format {
      
    /**
     * 
     * @var string
     */
    private $format;
    
    /**
     * 
     * @var string[]
     */
    public $aliases = [];
    
    /**
     * 
     * @param string $format
     * @param string[] $aliases
     */
    public function __construct(string $format, array $aliases = []) {
        $this->format = $format;
        $this->aliases = $aliases;
    }
    
    /**
     * 
     * @return bool
     */
    public function isReadable(): bool {
        try {
            $this->read("");
            return true;
        } catch (FormatException $ex) {
            return false;
        }
    }
    
    /**
     * 
     * @return bool
     */
    public function isWritable(): bool {
        try {
            $this->write([]);
            return true;
        } catch (FormatException $ex) {
            return false;
        }
    }
    
    /**
     * 
     * @param string $content
     * @return (scalar|array)[]
     * @throws FormatException
     */
    public function read(string $content): array {
        throw new FormatException("format '" .$this->getName(). "' is not readable!");
    }
    
    /**
     * 
     * @param (scalar|array)[] $content
     * @return string
     * @throws FormatException
     */
    public function write(array $content): string {
        throw new FormatException("format '" .$this->getName(). "' is not writable!");
    }
    
    
    /**
     * 
     * @param string $alias
     */
    public function addAlias(string $alias) {
        $this->aliases[] = $alias;
    }
    
    /**
     * 
     * @param string $alias
     */
    public function remAlias(string $alias) {
        foreach ($this->getAliases() as $k => $v) {
            if ($v === $alias) {
                unset($this->aliases[$k]);
            }
        }
    }
    
    /**
     * 
     * @return string[]
     */
    public function getAliases(): array {
        return $this->aliases;
    }
    
    /**
     * 
     * @return string
     */
    public final function getFormat(): string {
        return $this->format;
    }
            
    /**
     * 
     * @return string
     */
    public function getName(): string {
        return "Unknown";
    }
}