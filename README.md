# LiteConfig

[Packagist](https://packagist.org/packages/rollylni/liteconfig)

1. multifunctional 
2. the ability to add your own formats
3. convenient language structure
4. loading config by file extension

# default formats

* serialize
* array
* yaml
* json
* enum
* ini

## notice 

for yaml format operation, YAML extension is required

# usage example:

```php
require "vendor/autoload.php";

LCF\ConfigManager::init("eng");
$file = "MyConfig.yml";
$default = ["mykey" => "myval"];

$cfg = new LCF\Config($file, $default);
$cfg->set("mykey", "Hello World!");
$cfg->save();
```

# add format

```php

use LCF\format\Format;
use LCF\format\Parser;
use LCF\format\Writer;
use LCF\ConfigManager;

class MyFormat extends Format implements Parser, Writer {
    
    public function getName() {
        return "JavaScript Object Notation";
    }
    
    public function parse(string $content = "") {
        return json_decode($content, true);
    }
    
    public function write(array $data = []) {
        return json_encode($data);
    }
}

$format = new MyFormat("json");
$format->setDescription("my json parser and writer");
$format->setAliases(["js"]);

ConfigManager::addFormat($format);
```
