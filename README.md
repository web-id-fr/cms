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
            "url" : "git@bitbucket.org:web-id/test.git",
            "branches-path": "master"
        }
    ]
```

```bash
composer require webid/cms
```

### Publish vendor

```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider"
```

## Add nova-components in composer 



```bash

```  

## Add image for components

```bash
public/components/gallery_component.png
public/components/newsletter_component.png
```
