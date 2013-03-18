Integrate Twig with ZF2.

[![Build Status](https://secure.travis-ci.org/alexshelkov/ApptTwig.png)](http://travis-ci.org/alexshelkov/ApptTwig)
_____________________________________________________________________________________________________________________
#### Install
##### Using composer
Add following in your composer.json:
```json
{
    "require": {
        "appt/twig": "1.*"
    }
}
```
And enable module in your application.config.php:
```php
return array(
    'modules' => array(
        'ApptTwig',
    )
);

```
_____________________________________________________________________________________________________________________
#### Usage
##### Configuration
###### Templates
ApptTwig support adding templates using common for Zend Framework 2 TemplateMapResolver and TemplatePathStack loaders.
```php
return array(
    'appt' => array(
        'twig' => array(
            'default_template_suffix' => 'twg', // you can change file extension used by TemplatePathStack
            'template_path_stack' => array(
                'dir/1/',
                'dir/2/'
            ),
            'template_map' => array(
                'layout/layout' => 'dir/layout.twig',
                'error' => __DIR__ . 'dir/error/.twig',
            ),
        ),
    ),
);
```
###### Twig options
You can change twig engine option in config:
```php
return array(
 'appt' => array(
        'twig' => array(
            'engine_options' => array(
                'debug' => true, // turn on degug mode
            )
        ),
    ),
);
```
###### Twig extensions
Also it is possible to add new twig extensions:
```php
return array(
    'appt' => array(
        'twig' => array(
            'extension_manager' => array(
                'factories' => array (
                    'ZendViewHelpers' => 'ApptTwig\Service\Extension\ZendViewHelpersFactory'
                )
            ),
        )
    )
);
```
All extensions must implement Twig_ExtensionInterface.

##### Provided services
ApptTwig provide following services:

__appt.twig.renderer__ -- renderer service capapabile with PhpRenderer;  
__appt.twig.renderer_strategy__ -- renderer strategy;  
__appt.twig.resolver__ -- resolver service extend Zend\View\Resolver\AggregateResolver and used for resolving templetes;    
__appt.twig.extension_manager__ -- used for managine twig extensions, it extend Zend\ServiceManager\AbstractPluginManager.

##### Rendering
When you setup resolvers in your config ApptTwig is ready for rendering view templates as it was regular PhpRenderer. All you need is create templates in your view directory.