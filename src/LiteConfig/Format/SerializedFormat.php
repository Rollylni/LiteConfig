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

use function is_array;
use function serialize, unserialize;

class SerializedFormat extends Format {
    
    public function __construct() {
        parent::__construct(Config::SERIALIZED_FORMAT, ["so"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string {
       return "Serialized Object"; 
    }
    
    /**
     *
     * @param string $content
     * @return (scalar|array)[]
     */
    public function read(string $content): array {
       return is_array($s = unserialize($content)) ? $s : [];
    }

    /**
     *
     * @param (scalar|array)[] $content
     * @return string
     */
    public function write(array $content): string {
        return serialize($content);
    }
}