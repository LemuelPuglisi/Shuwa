# charlemagne/shuwa

![](https://img.shields.io/packagist/v/charlemagne/shuwa) ![](https://img.shields.io/packagist/dm/charlemagne/shuwa) ![](https://img.shields.io/github/license/lemuelpuglisi/Shuwa) ![](https://img.shields.io/github/issues/lemuelpuglisi/shuwa)

> Disclaimer: This project has education purpose only. Consider buying the Official [Google Translate API](https://cloud.google.com/translate/docs/). 

## v1.2.1 Features 

> - Implementing the request timeout to prevent blocking requests.
> - Auto-detect source language.
> - Detect language from a quote function.
> - Bug fixes. 

*Notes: this feature break the backward compatibility due to the constructor edit. Despite this, it won't require a brand new version, so please read the new documentation about the constructor.*

------

## *Documentation*

- **[Requirements](#requirements)**
- **[Installation](#Installation)**
- **[Usage](#Usage)**
- **[Risks & Safe Mode](#SafeMode)**
- **[Proxy System Class](#ProxySystem)**
- **[Donate](#Donate)**

------

# Requirements

This project require: 

> - php 7.*
> - Composer

Package used: 

> - Fabpot \ Goutte



------

# Installation

Run the command below to install the package:

```sh
$ composer require charlemagne/shuwa
```

Or just create a **composer.json** file as it follow: 

```json
{
    "require": {
        "charlemagne/shuwa": "*"
    }
}
```

And then run: 

```sh
$ composer update
```

------

# Usage 

### Shuwa class

Require Composer autoloader into your working file and use Shuwa class: 

```php
require '[PATH]/vendor/autoloader.php'; 

use charlemagne\Shuwa\Shuwa; 
```

Create a Shuwa object: 

```php
// will auto detect the source language and define the target language as 'en'
$shuwa = new Shuwa(); 
// will auto detect the source language and define the target language as TARGET
$shuwa = new Shuwa(TARGET);
// replace SOURCE & TARGET with your source language and target language code  
$shuwa = new Shuwa(TARGET, SOURCE);
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
    // set a proxy to the request
    $shuwa->setProxy("192.0.0.1:8080"); 
    // get the current proxy
    $shuwa->getProxy();
    // check if language code is valid
    $shuwa->checkLanguageCode('it'); 
    // detect the language code from a quote
    $shuwa->detectLangFromQuote("Lorem Ipsum");
    // translate a word or a quote
    $shuwa->translate('Hello world!');
    // translate a word or a quote using a proxy
    $shuwa->translate('Hello world!', true); 
    
```

### FShuwa class

use FShuwa class as it follows: 

```php
use charlemagne\Shuwa\FShuwa; 
```

Create an FShuwa object: 

```php
// will initialize ENGLISH => ITALIAN by default
$fileShuwa = new FShuwa(); 
// replace SOURCE & TARGET with your source language and target language code  
$fileShuwa = new FShuwa(SOURCE, TARGET);
```

FShuwa class extends Shuwa, so you can basically use all of Shuwa's methods.
There are few more methods that helps you to translate a whole Laravel\CodeIgniter Language file, but **it could take hours**.
Here's a list of all FShuwa's methods: 

```php
    // returns true if quote is valid (See validation on Options)
    $fileShuwa->validate($quote);
    // replace ' to \' 
    $fileShuwa->bind($quote);
    // translate a laravel Language file
    $fileShuwa->laravelTranslation('INPUT_FILE_PATH', 'OUTPUT_FILE_PATH'); 
    // translate the whole $lang array from codeIgniter lang file
    $fileShuwa->codeIgniterTranslation($lang); 
```

### Options

You can modify Shuwa and FShuwa class options by editing the **vendor\charlemagne\shuwa\src\config\shuwa.php** file.
Let's take a look: 

##### Shuwa class options

Enable the safe mode while creating the object.

> 'SAFE_MODE' => true,

Set timeout seconds to the requests (Preventing a deadlock).

> 'REQUEST_TIMEOUT' => 15, 

##### FShuwa class options

If you want to keep a word in native language while translating **a file**, you should use the target option.
Example [EN -> IT]: 
**'I bought :number apples';** 
If you set the target as ':', then the translated quote will be: 
**'Ho comprato :number mele';** 
**But remember that it can fail. If so, the algorithm will return the untranslated quote**

> 'TARGET' => ':', 

If you want to keep HTML quotes untraslated, keep the follow option to true. 

> 'HTML_INTEGRITY' => true, 

If you want to single words quotes untraslated, keep the follow option to true.

> 'MANTAIN_SINGLE_WORDS' => true, 

If you want to ban some words, so the translation doesn't affects the quotes that containts them, then add those words in the blacklist: 

> 'BLACKLIST' => [
>     'lorem', 'ipsum', 'docet'   
> ]

------

# SafeMode

There is a percentage of risk that you get temporary ip-banned from Google, because of Too Many Requests. 
Both to **prevent** and **cure** this issue, you can run the safe mode. 

I suppose that you don't use my package for mass translation, so I unset the safemode by default, but you can modify the default settings in vendor/charlemagne/shuwa/src/config/shuwa.php, just set **SAFE_MODE = true**.

This will slow down by 1 minute or less your script, if you want to know why, read the **Proxy System** section.

You can set the safe mode inside your code by using this function: 

```php
$shuwa->setSafeMode(true);
```

**When you get ip-banned, Shuwa automatically run the safe mode to get its work done.** 

------

# ProxySystem

ProxySys class protect you from Google IP ban, but slow down a lot the process. 
**If you want to quickly translate few quotes (< 20), I recommend you to disable SAFE_MODE**. 

#### Usage

Use the class as it follows: 

```php
use charlemagne\Shuwa\ProxySys; 

$system = new ProxySys(); 
```

Here's a list of ProxySys' methods: 

```php
// fill the list of proxyes 
$system->scrape(); 
// test proxyes and remove the slower ones. If list is empty, automatically call scrape()
$system->filter(); 
// optimize the list. If it isn't filtered, automatically call filter()
$system->optimize(); 
// fill the list in case the scrape() function doesn't works
$system->supportSource();
// same but fill the list with https proxyes
$system->supportSource(true);
// reload the list 
$system->reload(); 
// return a fresh new proxy and remove it from the list
$system->fire(); 
```

#### Options

You can modify ProxySys class options by editing the **vendor\charlemagne\shuwa\src\config\proxy.php** file.
Let's take a look: 

Enable https on supportSource function when Scrape() doesn't works. 

> 'RESERVE_SSL' => true, 

Time To Live of the TEST requests made to the TEST_URL server, we need to set an optimal number to ensure that the filter() function mantain only the working proxyes

> 'TTL' => 1000, 

Proxyes list dimension limit

> 'LIST_LIMIT' => 100

Scrape the proxy list when the class is instantiated

> 'AUTO_SCRAPE' => true, 

Filter the proxy list when the class is instantiated

> 'AUTO_FILTER' => true, 

Optimize the proxy list when the class is instantiated

> 'AUTO_OPTIMIZE' => true, 

Fitler the proxy list on reload

> 'FILTER_ON_RELOAD' => true, 

Optimize the proxy list on reload

> 'OPTIMIZE_ON_RELOAD' => true, 

Print proxySys actions, helpful while executing through terminal

> 'DYSPLAY' => [ 'SET' => true ]

------

# Donate 

if you appreciate my work and want to offer me a coffee, feel free to do so! :) 

Paypal: https://www.paypal.me/charlemgn

------
