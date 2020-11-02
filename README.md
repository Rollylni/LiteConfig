# LiteConfig

[Packagist](https://packagist.org/packages/rollylni/liteconfig)

* multifunctional 
* the ability to add your own formats
* loading config by file extension
* 5 formats available by default

# Example usage
```php
require "vendor/autoload.php";

$cfg = new LiteConfig\Config("File.json"); //detect format by extension
$cfg->load([
    "default_formats" => ["yaml", "json", "ini", "enum", "serialize"],
    "default_key" => "default_value"
]);
$cfg->set("key", "value");
$cfg->save();
```
**File.json**:
```json
{
   "default_formats": ["yaml", "json", "ini", "enum", "serialize"],
   "default_key": "default_value",
   "key": "value"
}
```

# Make your own format
```php
require "vendor/autoload.php";

use LiteConfig\Format\Format;
use LiteConfig\Config;

class MyFormat extends Format {

    public function getName() {
        return "My own Format";
    }
    
    public function read(string $input): array {
         return json_decode(base64_decode($input));
    }
    
    public function write(array $input): string {
        return base64_encode(json_encode($input));
    }
}
$format = new MyFormat("json64");
$format->addAlias("js64");
Config::addFormat($format);
```
