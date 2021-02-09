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

use function json_encode, json_decode;

class JsonFormat extends Format {
    
    public function __construct() {
        parent::__construct(Config::JSON_FORMAT, ["js"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string {
       return "JavaScript Object Notation(JSON)"; 
    }

    /**
     *
     * @param string $content
     * @return (scalar|array)[]
     */
    public function read(string $content): array {
       return json_decode($content, true) ?? [];
    }

    /**
     *
     * @param (scalar|array)[] $content
     * @return string
     */
    public function write(array $content): string {
        return json_encode($content);
    }
}
