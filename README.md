# LiteConfig

[Packagist](https://packagist.org/packages/rollylni/liteconfig)

* multifunctional 
* the ability to add your own formats
* loading config by file extension
* 5 formats available by default

# Example usage
```php
$cfg = new Config("File.json"); //detect format by extension
$cfg->load();
$cfg->set("key", "value");
$cfg->save();
```
**File.json**:
```json
{
   "key": "value"
}
```
