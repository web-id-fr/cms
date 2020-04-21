#CMS^ID
<p align="center">
<a href="blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square"></a>
</p>


## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
"repositories": [
        {
            "type": "vcs",
            "url" : "git@bitbucket.org:web-id/test.git"
        }
    ]
```

```bash
composer require webid/cms
```

## Publish vendor
####First install
```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider" --force
```
####Second install
```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider"
```
## Install migration

```bash
php artisan migrate
```

## Add nova-components in composer 

```bash
"extra": {
        "laravel": {
            "dont-discover": [],
            "providers": [
                "Webid\\ComponentTool\\ToolServiceProvider"
            ]
        }
    }
```  
```bash
"autoload": {
        "psr-4": {
            "Webid\\ComponentTool\\" : "nova-components/ComponentTool/src/"
        },
    },
```  
```bash
    "require": {
        "webid/ComponentItemField": "*"
    },
    
    "repositories": [
            {
                "type": "path",
                "url": "./nova-components/ComponentItemField"
            }
        ]
```

## Add image for components

```bash
public/components/gallery_component.png
public/components/newsletter_component.png
```

# For create a new components
##### 1. create Models, migration, repositories, Nova, Resource for the new component (register all elements in a Components folder)
##### 2. update config\component.php with the information of the new component and add the image of the component in public/components/
##### 3. update App\Models\Template with the information of the new component
##### 4. update nova-components\ComponentField with the information of the new component
##### 5. update nova-components\ComponentTool with the information of the new component
