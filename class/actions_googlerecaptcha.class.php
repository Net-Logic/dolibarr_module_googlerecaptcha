<?php
/* Copyright (C) 2019-2020  Frédéric France         <frederic.france@netlogic.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    htdocs/googlerecaptcha/class/actions_googlerecaptcha.class.php
 * \ingroup googleRecaptcha
 * \brief   google recaptcha hook overload.
 *
 */

/**
 * Class ActionsgoogleRecaptcha
 */
class ActionsGoogleRecaptcha
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 *  @var array Errors
	 */
	public $errors = [];

	/**
	 *  @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = [];

	/**
	 *  @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 *  Constructor
	 *
	 *  @param  DoliDB  $db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Add the reCAPTCHA v3 token to the login form, for the login method "googlerecaptcha"
	 * ($dolibarr_main_authentication = 'googlerecaptcha' in conf.php, all Dolibarr versions).
	 * Not done when the captcha handler of the module is already used on the login page (Dolibarr 21+), it adds the token.
	 *
	 * @return int
	 */
	public function getLoginPageOptions()
	{
		global $dolibarr_main_authentication;

		$authmodes = explode(',', (string) $dolibarr_main_authentication);
		if (!in_array('googlerecaptcha', array_map('trim', $authmodes))) {
			return 0;
		}
		if (
			getDolGlobalString('MAIN_SECURITY_ENABLECAPTCHA') && getDolGlobalString('MAIN_SECURITY_ENABLECAPTCHA_HANDLER') == 'googlerecaptcha'
			&& file_exists(DOL_DOCUMENT_ROOT . '/core/modules/security/captcha/modules_captcha.php')
		) {
			return 0;
		}

		dol_include_once('/googlerecaptcha/lib/googlerecaptcha.lib.php');
		$this->resprints = googlerecaptchaGetFormJs('login', '#login');

		return 0;
	}
}
