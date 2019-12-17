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
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

global $langs, $user;

// Libraries
require_once DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php";
require_once '../lib/googlerecaptcha.lib.php';
//require_once "../class/myclass.class.php";

// Translations
$langs->loadLangs(array("admin", "googlerecaptcha@googlerecaptcha"));

// Access control
if (!$user->admin) accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage', 'alpha');

$arrayofparameters = array(
    'GOOGLERECAPTCHA_SITE_KEY' => array(
        'css' => 'minwidth300',
        'type' => 'text',
        'enabled' => 1,
    ),
    'GOOGLERECAPTCHA_SERVER_KEY' => array(
        'css' => 'minwidth300',
        'type' => 'password',
        'enabled' => 1,
    ),
);



/*
 * Actions
 */
if ($action=='checkconfig') {
    $captcha = GETPOST('g-recaptcha-response', 'san_alpha');
    //$ip = $_SERVER['REMOTE_ADDR'];

    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $conf->global->GOOGLERECAPTCHA_SERVER_KEY,
        'response' => $captcha,
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        )
    );
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
    if ($responseKeys['success']) {
        setEventMessage($langs->trans('ValidScore', $responseKeys['score']));
    } else {
        setEventMessages('Error', $responseKeys['error-codes'], 'errors');
    }
}
if ((float) DOL_VERSION >= 6) {
    include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';
}



/*
 * View
 */

$page_name = "GoogleRecaptchaSetup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'object_googlerecaptcha@googlerecaptcha');

// Configuration header
$head = googlerecaptchaAdminPrepareHead();
dol_fiche_head($head, 'settings', '', -1, "googlerecaptcha@googlerecaptcha");

// Setup page goes here
echo '<span class="opacitymedium">'.$langs->trans("GoogleRecaptchaSetupPage").'</span><br><br>';


if ($action == 'edit') {
    print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print '<input type="hidden" name="action" value="update">';

    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';

    foreach ($arrayofparameters as $key => $val) {
        print '<tr class="oddeven">';
        print '<td>';
        $tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
        print $form->textwithpicto($langs->trans($key), $tooltiphelp);
        print '</td>';
        print '<td>';
        $type = empty($val['type'])?'text':$val['type'];
        print '<input name="'.$key.'" type="'.$type.'" class="flat '.(empty($val['css']) ? 'minwidth200' : $val['css']).'" value="'.$conf->global->$key.'">';
        print '</td>';
        print '</tr>';
    }
    print '</table>';

    print '<br><div class="center">';
    print '<input class="button" type="submit" value="'.$langs->trans("Save").'">';
    print '</div>';

    print '</form>';
    print '<br>';
} else {
    if (!empty($arrayofparameters)) {
        print '<table class="noborder centpercent">';
        print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';

        foreach ($arrayofparameters as $key => $val) {
            print '<tr class="oddeven">';
            print '<td>';
            $tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
            print $form->textwithpicto($langs->trans($key), $tooltiphelp);
            print '</td><td>';
            $value = $conf->global->$key;
            if (isset($val['type']) && $val['type']=='password') {
                $value = preg_replace('/./i', '*', $value);
            }
            print $value;
            print '</td></tr>';
        }

        print '</table>';

        print '<div class="tabsAction">';
        print '<a class="butAction" href="'.$_SERVER["PHP_SELF"].'?action=edit">'.$langs->trans("Modify").'</a>';
        print '</div>';
    } else {
        print '<br>'.$langs->trans("NothingToSetup");
    }
}
print $langs->trans("GOOGLERECAPTCHA_GO_HERE").': <a href="'.$langs->trans("GOOGLERECAPTCHA_SETUP_URL").'" target="_blank">'.$langs->trans("GOOGLERECAPTCHA_SETUP_URL").'</a><br>';
print $langs->trans("GOOGLERECAPTCHA_THEN").'<br>';

if (!empty($conf->global->GOOGLERECAPTCHA_SITE_KEY)) {
    print '<form id="checkconfig" name="checkconfig" method="POST" action="'.$_SERVER["PHP_SELF"].'">';
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print '<input type="hidden" name="action" value="checkconfig">';
    print '<input type="submit" class="button" value="'.$langs->trans("CheckConfig").'">';
    print '</form>';

    print '<script src="https://www.google.com/recaptcha/api.js?render='.$conf->global->GOOGLERECAPTCHA_SITE_KEY.'"></script>';
    print "<script>\n";
    print "$('#checkconfig').submit(function() {\n";
    print "    // we stoped it\n";
    print "    event.preventDefault();\n";
    print "    console.log('check config...');\n";
    print "    grecaptcha.ready(function() {\n";
    print "        grecaptcha.execute('".$conf->global->GOOGLERECAPTCHA_SITE_KEY."', {action: 'chekconfig'}).then(function(token) {\n";
    print "            // add token to form\n";
    print "            $('#checkconfig').prepend('<input type=\"hidden\" name=\"g-recaptcha-response\" value=\"' + token + '\">');\n";
    print "            $('#checkconfig').submit();\n";
    print "        });\n";
    print "    });\n";
    print "});\n";
    print "</script>\n";
}

// Page end
dol_fiche_end();

llxFooter();
$db->close();
