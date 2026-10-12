# GOOGLERECAPTCHA FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## Features

This module adds Google reCAPTCHA v3 (invisible: Google gives a score, nothing to fill) to Dolibarr.

Compatibility: Dolibarr 20 to 25.

You need a Google account to create the keys (https://www.google.com/recaptcha/admin), then set them in the setup page of the module, with the minimum score accepted (0.7 by default).

### Usage

- **Dolibarr 21 and later (recommended)**: in *Setup > Security > Captcha*, enable the captcha and choose the handler `googlerecaptcha`. It protects the login page, the password forgotten page and, from Dolibarr 23, the public forms where the captcha is enabled (tickets, members, donations, recruitment...).
- **All versions (login page only)**: in `conf/conf.php`, replace `$dolibarr_main_authentication='dolibarr';` by `$dolibarr_main_authentication='googlerecaptcha';`. The token is checked before the password. This is the only mode on Dolibarr 20 (no captcha handlers in this version).

<!--
![Screenshot googlerecaptcha](img/screenshot_googlerecaptcha.png?raw=true "GoogleRecaptcha"){imgmd}
-->

Other modules are available on [Dolistore.com](https://www.dolistore.com).

## Translations

Translations can be define manually by editing files into directories *langs*.

<!--
This module contains also a sample configuration for Transifex, under the hidden directory [.tx](.tx), so it is possible to manage translation using this service.

For more informations, see the [translator's documentation](https://wiki.dolibarr.org/index.php/Translator_documentation).

There is a [Transifex project](https://transifex.com/projects/p/dolibarr-module-template) for this module.
-->

<!--

## Installation

### From the ZIP file and GUI interface

- If you get the module in a zip file (like when downloading it from the market place [Dolistore](https://www.dolistore.com)), go into
menu ```Home - Setup - Modules - Deploy external module``` and upload the zip file.

Note: If this screen tell you there is no custom directory, check your setup is correct:

- In your Dolibarr installation directory, edit the ```htdocs/conf/conf.php``` file and check that following lines are not commented:

    ```php
    //$dolibarr_main_url_root_alt ...
    //$dolibarr_main_document_root_alt ...
    ```

- Uncomment them if necessary (delete the leading ```//```) and assign a sensible value according to your Dolibarr installation

    For example :

    - UNIX:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = '/var/www/Dolibarr/htdocs/custom';
        ```

    - Windows:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = 'C:/My Web Sites/Dolibarr/htdocs/custom';
        ```

### From a GIT repository

- Clone the repository in ```$dolibarr_main_document_root_alt/googlerecaptcha```

```sh
cd ....../custom
git clone git@github.com:gitlogin/googlerecaptcha.git googlerecaptcha
```

### <a name="final_steps"></a>Final steps

From your browser:

  - Log into Dolibarr as a super-administrator
  - Go to "Setup" -> "Modules"
  - You should now be able to find and enable the module

-->

## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
