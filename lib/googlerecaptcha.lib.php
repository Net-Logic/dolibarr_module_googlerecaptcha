<?php
/*
 * Copyright (C) 2019-2023  Frédéric FRANCE     <frederic.france@netlogic.fr>
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
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    googlerecaptcha/lib/googlerecaptcha.lib.php
 * \ingroup googlerecaptcha
 * \brief   Library files with common functions for GoogleRecaptcha
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function googlerecaptchaAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("googlerecaptcha@googlerecaptcha");

	$h = 0;
	$head = [];

	$head[$h][0] = dol_buildpath("/googlerecaptcha/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("GoogleRecaptchaSettings");
	$head[$h][2] = 'settings';
	$h++;
	$head[$h][0] = dol_buildpath("/googlerecaptcha/admin/about.php", 1);
	$head[$h][1] = $langs->trans("GoogleRecaptchaAbout");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//    'entity:+tabname:Title:@googlerecaptcha:/googlerecaptcha/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//    'entity:-tabname:Title:@googlerecaptcha:/googlerecaptcha/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, null, $head, $h, 'googlerecaptcha');

	return $head;
}

/**
 * Return the minimum reCAPTCHA v3 score accepted (0.0 to 1.0)
 *
 * @return float
 */
function googlerecaptchaMinScore()
{
	$minscore = (float) str_replace(',', '.', getDolGlobalString('GOOGLERECAPTCHA_MIN_SCORE', '0.7'));
	if ($minscore < 0 || $minscore > 1) {
		$minscore = 0.7;
	}
	return $minscore;
}

/**
 * Return the HTML/JS that adds a reCAPTCHA v3 token to a form when it is submitted.
 * Without selector, the form is the one that contains the script (login, password forgotten, public ticket, member or
 * donation forms...), so the same code works on every form that shows the captcha.
 *
 * @param	string	$action			reCAPTCHA action name (letters, digits, underscore). Empty = id or name of the form, else 'submit'
 * @param	string	$formselector	jQuery selector of the form. Empty = the form containing the script
 * @return	string					HTML to print, empty if the site key is not set
 */
function googlerecaptchaGetFormJs($action = '', $formselector = '')
{
	$sitekey = getDolGlobalString('GOOGLERECAPTCHA_SITE_KEY');
	if (empty($sitekey)) {
		return '';
	}
	$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

	$out = '<script src="https://www.google.com/recaptcha/api.js?render=' . urlencode($sitekey) . '"></script>' . "\n";
	$out .= '<script>' . "\n";
	$out .= '(function () {' . "\n";
	if ($formselector) {
		$out .= '	var form = document.querySelector(' . json_encode($formselector) . ');' . "\n";
	} else {
		$out .= '	var form = document.currentScript ? document.currentScript.closest("form") : null;' . "\n";
		$out .= '	if (!form) { form = document.getElementById("login"); }' . "\n";
	}
	$out .= '	if (!form) { return; }' . "\n";
	$out .= '	var action = ' . json_encode($action) . ' || (form.id || form.name || "submit").replace(/[^A-Za-z0-9_]/g, "_");' . "\n";
	$out .= '	var nativeSubmit = HTMLFormElement.prototype.submit;' . "\n";
	$out .= '	function addToken(then) {' . "\n";
	$out .= '		grecaptcha.ready(function () {' . "\n";
	$out .= '			grecaptcha.execute(' . json_encode($sitekey) . ', {action: action}).then(function (token) {' . "\n";
	$out .= '				var input = form.querySelector("input[name=\'g-recaptcha-response\']");' . "\n";
	$out .= '				if (!input) {' . "\n";
	$out .= '					input = document.createElement("input");' . "\n";
	$out .= '					input.type = "hidden";' . "\n";
	$out .= '					input.name = "g-recaptcha-response";' . "\n";
	$out .= '					form.appendChild(input);' . "\n";
	$out .= '				}' . "\n";
	$out .= '				input.value = token;' . "\n";
	$out .= '				form.dataset.recaptchaDone = "1";' . "\n";
	$out .= '				then();' . "\n";
	$out .= '			});' . "\n";
	$out .= '		});' . "\n";
	$out .= '	}' . "\n";
	$out .= '	// Buttons of Dolibarr call form.submit() in their onclick: it does not fire the submit event, so it is wrapped too' . "\n";
	$out .= '	form.submit = function () {' . "\n";
	$out .= '		if (form.dataset.recaptchaDone === "1") { return nativeSubmit.call(form); }' . "\n";
	$out .= '		addToken(function () { nativeSubmit.call(form); });' . "\n";
	$out .= '	};' . "\n";
	$out .= '	form.addEventListener("submit", function (event) {' . "\n";
	$out .= '		if (form.dataset.recaptchaDone === "1") { return; }' . "\n";
	$out .= '		event.preventDefault();' . "\n";
	$out .= '		var submitter = event.submitter || null;' . "\n";
	$out .= '		addToken(function () {' . "\n";
	$out .= '			// Keep the clicked button (its name/value is used by the page), requestSubmit() also runs the other submit handlers' . "\n";
	$out .= '			if (typeof form.requestSubmit === "function") {' . "\n";
	$out .= '				form.requestSubmit(submitter && submitter.form === form && !submitter.disabled ? submitter : undefined);' . "\n";
	$out .= '			} else {' . "\n";
	$out .= '				nativeSubmit.call(form);' . "\n";
	$out .= '			}' . "\n";
	$out .= '		});' . "\n";
	$out .= '	});' . "\n";
	$out .= '})();' . "\n";
	$out .= '</script>' . "\n";

	return $out;
}

/**
 * Check the answer of the reCAPTCHA siteverify API (pure function, no call)
 *
 * @param	array<string,mixed>|null	$answer			Decoded JSON answer of siteverify
 * @param	string						$expectedaction	Action that must match ('' = any action)
 * @param	float						$minscore		Minimum score
 * @return	array{ok:bool,score:float,error:string}
 */
function googlerecaptchaCheckAnswer($answer, $expectedaction = '', $minscore = 0.7)
{
	if (!is_array($answer)) {
		return array('ok' => false, 'score' => 0.0, 'error' => 'BadAnswer');
	}
	$score = isset($answer['score']) ? (float) $answer['score'] : 0.0;
	if (empty($answer['success'])) {
		$codes = (isset($answer['error-codes']) && is_array($answer['error-codes'])) ? implode(',', $answer['error-codes']) : '';
		return array('ok' => false, 'score' => $score, 'error' => 'NotSuccess' . ($codes ? ' (' . $codes . ')' : ''));
	}
	if ($expectedaction !== '' && (!isset($answer['action']) || $answer['action'] !== $expectedaction)) {
		return array('ok' => false, 'score' => $score, 'error' => 'BadAction');
	}
	if ($score < $minscore) {
		return array('ok' => false, 'score' => $score, 'error' => 'ScoreTooLow');
	}
	return array('ok' => true, 'score' => $score, 'error' => '');
}

/**
 * Verify a reCAPTCHA v3 token with Google
 *
 * @param	string	$token			Token posted by the form (g-recaptcha-response)
 * @param	string	$expectedaction	Action that must match ('' = any action)
 * @return	array{ok:bool,score:float,error:string}
 */
function googlerecaptchaVerifyToken($token, $expectedaction = '')
{
	$secret = getDolGlobalString('GOOGLERECAPTCHA_SERVER_KEY');
	if (empty($secret) || empty($token)) {
		return array('ok' => false, 'score' => 0.0, 'error' => empty($secret) ? 'NoServerKey' : 'NoToken');
	}

	// A token can be verified only once by Google (then "timeout-or-duplicate"): the answer is kept for the request,
	// as the captcha handler and the login method may both check the token of the same login
	static $answers = array();
	if (!array_key_exists($token, $answers)) {
		include_once DOL_DOCUMENT_ROOT . '/core/lib/geturl.lib.php';
		$param = http_build_query(array('secret' => $secret, 'response' => $token, 'remoteip' => getUserRemoteIP()));
		$result = getURLContent('https://www.google.com/recaptcha/api/siteverify', 'POST', $param, 1, array(), array('https'), 0);	// 0 = external URL only
		if (!empty($result['curl_error_no']) || (int) ($result['http_code'] ?? 0) != 200) {
			dol_syslog('googlerecaptcha: siteverify call failed ' . ($result['curl_error_msg'] ?? '') . ' http=' . ($result['http_code'] ?? ''), LOG_WARNING);
			return array('ok' => false, 'score' => 0.0, 'error' => 'CallFailed');
		}
		$answers[$token] = json_decode($result['content'], true);
	}

	$check = googlerecaptchaCheckAnswer($answers[$token], $expectedaction, googlerecaptchaMinScore());
	dol_syslog('googlerecaptcha: score=' . $check['score'] . ' ok=' . (int) $check['ok'] . ' ' . $check['error'], $check['ok'] ? LOG_DEBUG : LOG_NOTICE);

	return $check;
}
