<?php
/* Copyright (C) 2019       Frédéric France         <frederic.france@netlogic.fr>
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
    public $errors = array();

    /**
     *  @var array Hook results. Propagated to $hookmanager->resArray for later reuse
     */
    public $results = array();

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
     * Edits the login form to allow entering GoogleRecaptcha Login
     * @return int
     */
    public function getLoginPageOptions()
    {
        global $conf;
        $tpl .= '<script src="https://www.google.com/recaptcha/api.js?render='.$conf->global->GOOGLERECAPTCHA_SITE_KEY.'"></script>';
        $tpl .= "<script>\n";
        $tpl .= "$('#login').submit(function() {\n";
        $tpl .= "    // we stoped it\n";
        $tpl .= "    event.preventDefault();\n";
        $tpl .= "    console.log('try login...');\n";
        $tpl .= "    grecaptcha.ready(function() {\n";
        $tpl .= "        grecaptcha.execute('".$conf->global->GOOGLERECAPTCHA_SITE_KEY."', {action: 'login'}).then(function(token) {\n";
        $tpl .= "            // add token to form\n";
        $tpl .= "            $('#login').prepend('<input type=\"hidden\" name=\"g-recaptcha-response\" value=\"' + token + '\">');\n";
        $tpl .= "            $('#login').submit();\n";
        $tpl .= "        });\n";
        $tpl .= "    });\n";
        $tpl .= "});\n";
        $tpl .= "</script>\n";
        if (!empty($conf->global->GOOGLERECAPTCHA_SITE_KEY)) {
            $this->resprints = $tpl;
        }
        return 0;
    }

    /**
     * Return password field for new user create
     * @return int
     */
    public function printUserPasswordField($parameters)
    {
        global $conf;

        $tpl = ($parameters['valuetoshow']?', ':'').'<input size="30" maxsize="32" type="text" name="password" value="'.$parameters['password'].'" autocomplete="new-password">';

        $this->resprints = $tpl;
        return 1;
    }
}
