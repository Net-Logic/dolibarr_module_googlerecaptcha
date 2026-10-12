<?php
/*
 * Copyright (C) 2019       Frédéric France     <frederic.france@netlogic.fr>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Check validity of user/password/entity, after a check of the reCAPTCHA v3 token of the login form.
 * Used with $dolibarr_main_authentication = 'googlerecaptcha' in conf.php.
 * If test is ko, reason must be filled into $_SESSION["dol_loginmesg"]
 *
 * @param   string  $usertotest     Login
 * @param   string  $passwordtotest Password
 * @param   int     $entitytotest   Number of instance (always 1 if module multicompany not enabled)
 * @return	string                  Login if OK, '' if KO
 */
function check_user_password_googlerecaptcha($usertotest, $passwordtotest, $entitytotest)
{
	global $langs;

	dol_syslog("functions_googlerecaptcha::check_user_password_googlerecaptcha", LOG_DEBUG);

	// The captcha is checked first: the password must not be tested (nor a different message given) for a request without a valid token
	dol_include_once('/googlerecaptcha/lib/googlerecaptcha.lib.php');
	$check = googlerecaptchaVerifyToken(GETPOST('g-recaptcha-response', 'aZ09'), 'login');
	if (!$check['ok']) {
		$langs->load('googlerecaptcha@googlerecaptcha');
		$_SESSION["dol_loginmesg"] = $langs->transnoentitiesnoconv('GoogleRecaptchaLoginRefused');
		return '';
	}

	// Then the login and password, as the standard Dolibarr method (it returns the login, also when the email was typed)
	require_once DOL_DOCUMENT_ROOT . '/core/login/functions_dolibarr.php';
	return check_user_password_dolibarr($usertotest, $passwordtotest, $entitytotest);
}
