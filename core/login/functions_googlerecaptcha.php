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
 * Check validity of user/password/entity
 * If test is ko, reason must be filled into $_SESSION["dol_loginmesg"]
 *
 * @param   string  $usertotest     Login
 * @param   string  $passwordtotest Password
 * @param   int     $entitytotest   Number of instance (always 1 if module multicompany not enabled)
 * @return	string                  Login if OK, '' if KO
 */
function check_user_password_googlerecaptcha($usertotest, $passwordtotest, $entitytotest)
{
	global $conf;

	// recaptcha 3
	require_once DOL_DOCUMENT_ROOT . '/core/login/functions_dolibarr.php';
	// We first check if user & password are OK
	dol_syslog("functions_googlerecaptcha::check_user_password_googlerecaptcha", LOG_DEBUG);
	if (check_user_password_dolibarr($usertotest, $passwordtotest, $entitytotest) == '') {
		// if test fails then no login...
		return '';
	}

	// Force master entity in transversal mode
	$entity = $entitytotest;
	if (!empty($conf->multicompany->enabled) && !empty($conf->multicompany->transverse_mode)) {
		$entity = 1;
	}
	dol_syslog("functions_googlerecaptcha::check_googlerecaptcha", LOG_DEBUG);
	$captcha = GETPOST('g-recaptcha-response', 'san_alpha');
	//$ip = $_SERVER['REMOTE_ADDR'];

	// post request to server
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = [
		'secret' => $conf->global->GOOGLERECAPTCHA_SERVER_KEY ?? '',
		'response' => $captcha,
	];
	$options = [
		'http' => [
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
		]
	];
	$context  = stream_context_create($options);
	$response = file_get_contents($url, false, $context);
	$responseKeys = json_decode($response, true);
	// {
	//     "success": true|false,      // whether this request was a valid reCAPTCHA token for your site
	//     "score": number             // the score for this request (0.0 - 1.0)
	//     "action": string            // the action name for this request (important to verify)
	//     "challenge_ts": timestamp,  // timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
	//     "hostname": string,         // the hostname of the site where the reCAPTCHA was solved
	//     "error-codes": [...]        // optional
	// }
	if ($responseKeys['success'] === true && (float) $responseKeys['score'] > 0.7 && $responseKeys['action'] == 'login') {
		// Now the user is authenticated
		// setEventMessage('Score recaptcha '. (string) $responseKeys['score']);
		return $usertotest;
	}
	$_SESSION["dol_loginmesg"] = 'No login for now... ';
	return '';
}
