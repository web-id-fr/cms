#CMS^ID
<p align="center">
<a href="blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square"></a>
</p>


## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require webid/cms
```

### Add CmsServiceProvider in config/app

```bash
Webid\Cms\CmsServiceProvider::class
```

### Publish vendor

```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider"
```

## Add nova-components in composer 

```bash
"webid/language-tool": "*",
"webid/nova-translatable": "*",
"webid/component-field": "*",
"webid/component-tool": "*"
```

```bash
"repositories": [   
        {
            "type": "path",
            "url": "./src/nova-components/LanguageTool"
        },
        {
            "type": "path",
            "url": "./src/nova-components/TranslatableTool"
        },
        {
            "type": "path",
            "url": "./src/nova-components/ComponentTool"
        },
        {
            "type": "path",
            "url": "./src/nova-components/ComponentField"
        }
    ]
``
