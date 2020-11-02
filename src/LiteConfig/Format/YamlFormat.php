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
use LiteConfig\Exception\FormatException;

use function yaml_emit, yaml_parse;

class YamlFormat extends Format {
    
    public function __construct() {
        parent::__construct(Config::YAML_FORMAT, ["yml"]);
    }
    
    /**
     * 
     * @return string
     */
    public function getName(): string {
       return "Yet Another Markup Language(YAML)"; 
    }
    
    /**
     *
     * @param string $content
     * @return (scalar|array)[]
     * @throws FormatException
     */
    public function read(string $content): array {
        if (!extension_loaded("yaml")) {
            throw new FormatException("this format requires a YAML-extension to work");
        }
        return ($yaml = yaml_parse($content)) ? $yaml : [];
    }

    /**
     *
     * @param (scalar|array)[] $content
     * @throws FormatException
     * @return string
     */
    public function write(array $content): string {
        if (!extension_loaded("yaml")) {
            throw new FormatException("this format requires a YAML-extension to work");
        }
        return yaml_emit($content);
    }
}