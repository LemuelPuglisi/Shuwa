# charlemagne/shuwa

> Disclaimer: This project has education purpose only. Consider buying the Official [Google Translate API](https://cloud.google.com/translate/docs/). 

****
## *Documentation*
- **[Requirements](#requirements)**
- **[Installation](#Installation)**
- **[Usage](#Usage)**
- **[Risks & Safe Mode](#Risks&SafeMode)**
- **[Proxy System](#ProxySystem)**
- **[Mass Translation]()**
- **[Laravel files]()**
- **[Donate]()**

****

# Requirements
This project require: 
- php >= 7.0
- Composer
****

# Installation
Run the command below to install the package. 
```sh
$ composer require charlemagne/shuwa
```
Or just create a **composer.json** file as it follow: 
```
{
    "require": {
        "charlemagne/shuwa": "*@dev"
    }
}
```
And then run: 
```sh
$ composer update
```
**Notes** : Currently developing this package, so it's strongly recommended to use @dev to the requirements
****

# Usage 
Require Shuwa class from vendor: 
```php
require 'vendor/charlemagne/shuwa/src/Shuwa.php'; 
```
Create a Shuwa object: 
```php
// will initialize ENGLISH => ITALIAN by default
$shuwa = new Shuwa(); 
// replace SOURCE & TARGET with your source language and target language code  
$shuwa = new Shuwa(SOURCE, TARGET);
```
Here's a list of all [language codes](https://www.loc.gov/standards/iso639-2/php/code_list.php) (Please use the ISO 639-1 Code version).

Quick list of all basic functions:
```php
    // return the source language code
    $shuwa->getSourceLang(); 
    // return the target language code
    $shuwa->getTargetLang(); 
    // set the source language code
    $shuwa->setSourceLang('en'); 
    // set the target language code
    $shuwa->setTargetLang('it');
    // set the safe mode
    $shuwa->setSafeMode(true/false); 
    // check if language code is valid
    $shuwa->checkLanguageCode('it'); 
    // translate a word or a quote
    $shuwa->translate('Hello world!'); 
```
****

### Risks & Safe mode
There is a percentage of risk that you get temporary ip-banned from Google, because of Too Many Requests. 
Both to **prevent** and **cure** this issue, you can run the safe mode. 

I suppose that you don't use my package for mass translation, so I unset the safemode by default, but you can modify the default settings in vendor/charlemagne/shuwa/src/config/shuwa.php, just set **SAFE_MODE = true**.

This will slow down by 1 minute or less your script, if you want to know why, read the **Proxy System** section.

You can set the safe mode inside your code by using this function: 
```php
$shuwa->setSafeMode(true);
```
**When you get ip-banned, Shuwa automatically run the safe mode to get its work done.** 

****

# Proxy System

Documentation is coming. 

****

# Mass translation

Documentation is coming. 

****
# Laravel files Translation

Documentation is coming.

****

# Donate 

Documentation is coming. 

****

