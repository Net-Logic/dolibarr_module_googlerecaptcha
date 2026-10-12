<?php
/* Copyright (C) 2006-2011  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2024		Frédéric France			<frederic.france@free.fr>
 * Copyright (C) 2024		MDW						<mdeweerd@users.noreply.github.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * or see https://www.gnu.org/
 */

/**
 *      \file       googlerecaptcha/core/modules/security/captcha/modCaptchaGooglerecaptcha.class.php
 *      \ingroup    googlerecaptcha
 *		\brief      Captcha handler using Google reCAPTCHA v3 (Dolibarr 21+: Setup > Security > Captcha)
 */

require_once DOL_DOCUMENT_ROOT . '/core/modules/security/captcha/modules_captcha.php';
dol_include_once('/googlerecaptcha/lib/googlerecaptcha.lib.php');

// phpcs:disable
/**
 *	Captcha handler using Google reCAPTCHA v3 (invisible: a score is asked to Google when the form is submitted)
 */
class modCaptchaGooglerecaptcha extends ModeleCaptcha
{
	// phpcs:enable
	/**
	 * @var string ID
	 */
	public $id;

	/**
	 * @var string
	 */
	public $picto = 'fa-shield-alt';

	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$db			Database handler
	 *	@param		Conf		$conf		Handler de conf
	 *	@param		Translate	$langs		Handler de langue
	 *	@param		?User		$user		Handler du user connected
	 */
	public function __construct($db, $conf, $langs, $user)
	{
		$this->id = strtolower(preg_replace('/^modCaptcha/i', '', get_class($this)));

		$this->db = $db;
		$this->conf = $conf;
		$this->langs = $langs;
		$this->user = $user;
		$this->langs->load('googlerecaptcha@googlerecaptcha');
	}

	/**
	 * Return if the handler can be used (keys are set)
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return getDolGlobalString('GOOGLERECAPTCHA_SITE_KEY') !== '' && getDolGlobalString('GOOGLERECAPTCHA_SERVER_KEY') !== '';
	}

	/**
	 *		Return description of module
	 *
	 *      @return     string      Description of module
	 */
	public function getDescription()
	{
		return $this->langs->trans("DolibarrGoogleRecaptchaCaptcha");
	}

	/**
	 *		Return the title of the input field shown on the forms (Dolibarr 23+): none, reCAPTCHA v3 is invisible
	 *
	 *      @return     string      Title of field
	 */
	public function getFieldInputTitle()
	{
		return '';
	}

	/**
	 * 		Return an example of what is added on the forms
	 *
	 *      @return     string      Example
	 */
	public function getExample()
	{
		return $this->langs->trans("GoogleRecaptchaExample");
	}

	/**
	 * 	Return the HTML content to output on a form that need the captcha (login, password forgotten, public forms...)
	 *
	 *  @param		string	$php_self	An URL for the a href link
	 *  @return     string				The HTML code to output
	 */
	public function getCaptchaCodeForForm($php_self = '')
	{
		return googlerecaptchaGetFormJs();
	}

	/**
	 * 	Validate a captcha
	 * 	This function is called after a submit of a form with the captcha (login, password forgotten, public forms...)
	 *
	 *  @return     int					0 if KO, >0 if OK
	 */
	public function validateCodeAfterLoginSubmit()
	{
		$check = googlerecaptchaVerifyToken(GETPOST('g-recaptcha-response', 'aZ09'));
		if (!$check['ok']) {
			$this->error = $check['error'];
			return 0;
		}

		return 1;
	}
}
