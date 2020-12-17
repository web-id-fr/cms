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
        "webid/ComponentItemField": "*",
    },
    
    "repositories": [
            {
                "type": "path",
                "url": "./nova-components/ComponentItemField"
            }
        ]
```
##![#f03c15](https://placehold.it/15/f03c15/000000?text=+) :warning: :warning: Do not delete existing code in nova-components !!!  :warning: :warning: ![#f03c15](https://placehold.it/15/f03c15/000000?text=+)

   
## Use cookies.js
###### To use the cookies popin, just fill the ``resuorces/views/warning_cookies.blade.php`` view, include it in ``resources/views/template.blade.php`` with the js ``public/cms/js/cookies.js``

## Use form & popin form
###js
do not modify the files `send_form.js` and `send_form_popin.js` !
Edit the`helper.js` file with the form front information to display errors and the success message.
Added to `package.json` :
```bash
"dropzone": "^5.7.0",
"lang.js": "^1.1.14"
```
And add in the `webpack.mix` file the `send_form_js` and `send_form_popin_js` files. The files are already linked in the front.
###front-end
You can change the form frontend but DO NOT TOUCH the `submit_form` class for sending forms.

## Language for front
Don't forget to create a service to display the languages as you need them.
Use this service into a ViewServiceProvider to share both languages and translated slugs to views.

## Template email in `resources/views/mail/form.blade.php`
You can change the design of the mail template but do not delete or modify the existing code! The present code allows you to display the fields of the form sent in the email.

## Add image for components

```bash
public/cms/images/components/gallery_component.png
public/cms/images/components/newsletter_component.png
```

# To create a new component
##### 1. create Models, migration, repositories, Nova, Resource for the new component (register all elements in a Components folder)
##### 2. update `config\component.php` with the information of the new component and add the image of the component in `public/components/`
##### 3. update `App\Models\Template` with the information of the new component
##### 4. update `nova-components\ComponentField` with the information of the new component
