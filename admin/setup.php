<?php
/* Copyright (C) 2004-2017  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2019       Frédéric FRANCE         <frederic.france@netlogic.fr>
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
 * \file    googlerecaptcha/admin/setup.php
 * \ingroup googlerecaptcha
 * \brief   GoogleRecaptcha setup page.
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)) . "/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1)) . "/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var Translate $langs
 * @var User $user
 */

require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
dol_include_once('/googlerecaptcha/lib/googlerecaptcha.lib.php');

// Translations
$langs->loadLangs(["admin", "googlerecaptcha@googlerecaptcha"]);

// Access control
if (!$user->admin) {
	accessforbidden();
}

// Parameters
$action = GETPOST('action', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');

$arrayofparameters = [
	'GOOGLERECAPTCHA_SITE_KEY' => [
		'css' => 'minwidth300',
		'type' => 'text',
		'enabled' => 1,
	],
	'GOOGLERECAPTCHA_SERVER_KEY' => [
		'css' => 'minwidth300',
		'type' => 'password',
		'enabled' => 1,
	],
	'GOOGLERECAPTCHA_MIN_SCORE' => [
		'css' => 'width75',
		'type' => 'text',
		'enabled' => 1,
	],
];

$form = new Form($db);


/*
 * Actions
 */

if ($action == 'update') {
	$error = 0;
	foreach ($arrayofparameters as $key => $val) {
		$value = GETPOST($key, 'alphanohtml');
		if ($key == 'GOOGLERECAPTCHA_MIN_SCORE' && $value !== '') {
			$value = str_replace(',', '.', $value);
			if (!is_numeric($value) || (float) $value < 0 || (float) $value > 1) {
				setEventMessages($langs->trans("GoogleRecaptchaErrorMinScore"), null, 'errors');
				$error++;
				continue;
			}
		}
		$result = dolibarr_set_const($db, $key, $value, 'chaine', 0, '', $conf->entity);
		if ($result < 0) {
			$error++;
		}
	}
	if (!$error) {
		setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
		header("Location: " . $_SERVER["PHP_SELF"]);
		exit;
	}
	$action = 'edit';
}

if ($action == 'checkconfig') {
	// Check of the keys: the form of this page sends a token of the site key, verified with the server key
	$check = googlerecaptchaVerifyToken(GETPOST('g-recaptcha-response', 'aZ09'), 'checkconfig');
	if ($check['ok']) {
		setEventMessages($langs->trans('ValidScore', (string) $check['score']), null, 'mesgs');
	} else {
		setEventMessages($langs->trans('GoogleRecaptchaCheckFailed', $check['error'], (string) $check['score']), null, 'errors');
	}
}


/*
 * View
 */

$page_name = "GoogleRecaptchaSetup";
llxHeader('', $langs->trans($page_name));

$linkback = '<a href="' . ($backtopage ? $backtopage : DOL_URL_ROOT . '/admin/modules.php?restore_lastsearch_values=1') . '">' . $langs->trans("BackToModuleList") . '</a>';
print load_fiche_titre($langs->trans($page_name), $linkback, 'object_googlerecaptcha@googlerecaptcha');

$head = googlerecaptchaAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', '', -1, "googlerecaptcha@googlerecaptcha");

print '<span class="opacitymedium">' . $langs->trans("GoogleRecaptchaSetupPage") . '</span><br><br>';

if ($action == 'edit') {
	print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
	print '<input type="hidden" name="token" value="' . newToken() . '">';
	print '<input type="hidden" name="action" value="update">';
}

print '<table class="noborder centpercent">';
print '<tr class="liste_titre"><td class="titlefield">' . $langs->trans("Parameter") . '</td><td>' . $langs->trans("Value") . '</td></tr>';
foreach ($arrayofparameters as $key => $val) {
	print '<tr class="oddeven">';
	print '<td>';
	$tooltiphelp = (($langs->trans($key . 'Tooltip') != $key . 'Tooltip') ? $langs->trans($key . 'Tooltip') : '');
	print $form->textwithpicto($langs->trans($key), $tooltiphelp);
	print '</td><td>';
	$value = getDolGlobalString($key);
	if ($action == 'edit') {
		$type = empty($val['type']) ? 'text' : $val['type'];
		print '<input name="' . $key . '" type="' . $type . '" class="flat ' . (empty($val['css']) ? 'minwidth200' : $val['css']) . '" value="' . dol_escape_htmltag($value) . '"' . ($type == 'password' ? ' autocomplete="new-password"' : '') . '>';
		if ($key == 'GOOGLERECAPTCHA_MIN_SCORE') {
			print ' <span class="opacitymedium">(' . $langs->trans("Default") . ': 0.7)</span>';
		}
	} else {
		if (isset($val['type']) && $val['type'] == 'password') {
			$value = preg_replace('/./', '*', $value);
		}
		if ($key == 'GOOGLERECAPTCHA_MIN_SCORE' && $value === '') {
			$value = '<span class="opacitymedium">' . googlerecaptchaMinScore() . '</span>';
		} else {
			$value = dol_escape_htmltag($value);
		}
		print $value;
	}
	print '</td></tr>';
}
print '</table>';

if ($action == 'edit') {
	print '<br><div class="center">';
	print '<input class="button button-save" type="submit" value="' . $langs->trans("Save") . '">';
	print '</div>';
	print '</form>';
} else {
	print '<div class="tabsAction">';
	print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=edit&token=' . newToken() . '">' . $langs->trans("Modify") . '</a>';
	print '</div>';
}

print '<br>';
print $langs->trans("GOOGLERECAPTCHA_GO_HERE") . ': <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener noreferrer">https://www.google.com/recaptcha/admin</a><br><br>';

// How to use the module: captcha handler (Dolibarr 21+) and/or login method (all versions)
print '<div class="info">';
if (file_exists(DOL_DOCUMENT_ROOT . '/core/modules/security/captcha/modules_captcha.php')) {
	print $langs->trans("GoogleRecaptchaHowToCaptcha", '<a href="' . DOL_URL_ROOT . '/admin/security_captcha.php">' . $langs->trans("Security") . ' - Captcha</a>') . '<br>';
}
print $langs->trans("GoogleRecaptchaHowToLogin");
print '</div>';

if (getDolGlobalString('GOOGLERECAPTCHA_SITE_KEY') && $action != 'edit') {
	print '<br>';
	print '<form id="checkconfig" name="checkconfig" method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
	print '<input type="hidden" name="token" value="' . newToken() . '">';
	print '<input type="hidden" name="action" value="checkconfig">';
	print '<input type="submit" class="button" value="' . $langs->trans("CheckConfig") . '">';
	print googlerecaptchaGetFormJs('checkconfig');
	print '</form>';
}

// Page end
print dol_get_fiche_end();

llxFooter();
$db->close();
