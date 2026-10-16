## 2.1.3

* DoliStore page: Net Logic banner at the top of the description (dolistore-sync v0.5.0). No code change.

## 2.1.2

* DoliStore: page in the shared Net-Logic layout (dolistore-sync v0.4.4), product name "Google reCAPTCHA v3 - invisible captcha for Dolibarr". No code change.

## 2.1.1

* DoliStore: new cover image and automatic update of the product page (texts, cover) on each release.

## 2.1.0

#### Compatibility with Dolibarr 20 to 25
* Captcha handler (Dolibarr 21+): the token is added to every form that shows the captcha (login, password forgotten, public ticket, member, donation and recruitment forms of Dolibarr 23+), not only to the login form, also when the form is submitted by `form.submit()` (buttons of Dolibarr), keeping the clicked button.
* `getFieldInputTitle()` (Dolibarr 23+): no input field shown, reCAPTCHA v3 is invisible.
* Login method `googlerecaptcha` (all versions, the only mode on Dolibarr 20): the token is added by the hook of the login page again, only when the captcha handler does not already add it.

#### Fixes
* Login method: the reCAPTCHA token is checked before the password (a request without token could learn if a password is right from the message), and the login returned by Dolibarr is used (login by email).
* A token is verified once per request (Google refuses a second check of the same token when both the captcha handler and the login method are used).
* Call to Google with `getURLContent()` (works without `allow_url_fopen`, timeouts, external URL only) instead of `file_get_contents()`.
* Setup page: values escaped, CSRF token, minimum score setting (`GOOGLERECAPTCHA_MIN_SCORE`, 0.7 by default), check of the keys with the score or the error code.
* Removed the hook of the user card that showed the password in a text field.
* Minimum Dolibarr version: 20.

#### Publication
* Automatic packaging (`build/makepack.sh`) and DoliStore publication on release.


## v2.0.0 (2025-10-23)

#### :rocket: Enhancement
* [#5](https://github.com/Net-Logic/dolibarr_module_googlerecaptcha/pull/5) min dolibarr version is 19 ([@frederic34](https://github.com/frederic34))

#### :bug: Bug Fix
* [#1](https://github.com/Net-Logic/dolibarr_module_googlerecaptcha/pull/1) last fix ([@frederic34](https://github.com/frederic34))

#### Committers: 1
- Frédéric FRANCE ([@frederic34](https://github.com/frederic34))
