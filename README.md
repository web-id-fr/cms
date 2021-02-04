# CMS^ID

![](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

## Table of contents
* [Requirements](#requirements)
* [Installation](#installation)
  1. [Install the package](#install-the-package)
  2. [Publish files](#publish-files)
  3. [Install databases](#install-databases)
  4. [Add nova-components in composer](#add-nova-components)
  5. [Prepare routes](#prepare-routes)
  6. [Link storage files](#6-link-storage-files)
  7. [Configure sitemap.xml](#configure-sitemap)
* [Customization](#customization)
  1. [Use cookies.js](#use-cookies-js)
  2. [Use form & popin form](#use-form-popin)
  3. [Internationalization](#language-front)
  4. [Update email template](#update-mail-template)
  5. [Add images for components](#add-image-components)
* [Extending functionalities](#extending-cms)
  1. [Create a new component](#create-new-component)

---

## Requirements

* PHP >= 7.4
* Composer 2
* MariaDB / MySQL

## Installation

### 1. Install the package

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
"repositories": [
    {
        "type": "vcs",
        "url" : "git@github.com:web-id-fr/cms.git"
    }
]
```

```bash
composer require webid/cms
```

<a id="publish-files"></a>

### 2. Publish files
#### Case 1 : First install
```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider" --force
```
#### Case 2 : Second install (or after)
```bash
php artisan vendor:publish --provider="Webid\Cms\CmsServiceProvider"
```

<a id="install-databases"></a>
### 3. Install databases

```bash
make install_db
```

<a id="add-nova-components"></a>
### 4. Add nova-components in composer 

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
    "webid/component-item-field": "*",
},

"repositories": [
    {
        "type": "path",
        "url": "./nova-components/ComponentItemField"
    }
]
```

Then run `composer update`

<a id="prepare-routes"></a>
### 5. Prepare routes

You have to remove all routes from your `routes/web.php` file to make sure
the cms will work properly.

If the project is a fresh Laravel project, you may have some generated code like this to remove :
```php
Route::get('/', function () {
    return view('welcome');
});
 ```

### 6. Link storage files

Run command `php artisan storage:link`.

<a id="configure-sitemap"></a>
### 7. Configure sitemap.xml

If you want to allow robots to access your sitemap, add this line in the `robots.txt` file :
```
Sitemap: https://www.your-domain.com/sitemap.xml
```
⚠ Replace `www.your-domain.com` by your actual website domain.

## ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) ⚠ ⚠ Do not delete existing code in nova-components !!!  ⚠ ⚠ ![#f03c15](https://placehold.it/15/f03c15/000000?text=+)

---

<a id="customization"></a>
## Customization

<a id="use-cookies-js"></a>
### Use cookies.js
###### To use the cookies popin, just fill the ``resources/views/warning_cookies.blade.php`` view, include it in ``resources/views/template.blade.php`` with the js ``public/cms/js/cookies.js``

<a id="use-form-popin"></a>
### Use form & popin form
#### js
do not modify the files `send_form.js` and `send_form_popin.js` !
Edit the`helper.js` file with the form front information to display errors and the success message.
Added to `package.json` :
```bash
"dropzone": "^5.7.0",
"lang.js": "^1.1.14"
```
And add in the `webpack.mix` file the `send_form_js` and `send_form_popin_js` files. The files are already linked in the front.
#### front-end
You can change the form frontend but DO NOT TOUCH the `submit_form` class for sending forms.

<a id="language-front"></a>
### Internationalization
Don't forget to create a service to display the languages as you need them.
Use this service into a ViewServiceProvider to share both languages and translated slugs to views.

To create the service provider, you can run :
```bash
php artisan make:provider ViewServiceProvider
```

⚠ Don't forget to add the service provider in the file `config/app.php`.

<a id="update-mail-template"></a>
### Update email template
#### Template email in `resources/views/mail/form.blade.php`
You can change the design of the mail template but do not delete or modify the existing code! The present code allows you to display the fields of the form sent in the email.

<a id="add-image-components"></a>
### Add images for components

```bash
public/cms/images/components/gallery_component.png
public/cms/images/components/newsletter_component.png
```

---

<a id="extending-cms"></a>
## Extending functionalities
<a id="create-new-component"></a>
### Create a new component
##### 1. create Models, migration, repositories, Nova, Resource for the new component (register all elements in a Components folder)
##### 2. update `config\component.php` with the information of the new component and add the image of the component in `public/components/`
##### 3. update `App\Models\Template` with the information of the new component
##### 4. update `nova-components\ComponentField` with the information of the new component

---

