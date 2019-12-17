<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2019       Frédéric France         <frederic.france@netlogic.fr>
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
 */

/**
 * 	\defgroup   googlerecaptcha     Module GoogleRecaptcha
 *  \brief      GoogleRecaptcha module descriptor.
 *
 *  \file       htdocs/googlerecaptcha/core/modules/modGoogleRecaptcha.class.php
 *  \ingroup    googlerecaptcha
 *  \brief      Description and activation file for module GoogleRecaptcha
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module GoogleRecaptcha
 */
class modGoogleRecaptcha extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;
        $this->db = $db;

        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 135130; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'googlerecaptcha';
        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
        // It is used to group modules by family in module setup page
        $this->family = "Net-Logic";
        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '90';
        // Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        //$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
        // Module label (no space allowed), used if translation string 'ModuleGoogleRecaptchaName' not found (GoogleRecaptcha is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description, used if translation string 'ModuleGoogleRecaptchaDesc' not found (GoogleRecaptcha is name of module).
        $this->description = "GoogleRecaptchaDescription";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "GoogleRecaptcha description (Long)";
        $this->editor_name = '<strong>Net Logic</strong>';
        $this->editor_url = 'https://netlogic.fr';
        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = '1.2';
        // Url to the file with your last numberversion of this module
        //$this->url_last_version = 'http://www.example.com/versionmodule.txt';

        // Key used in llx_const table to save module status enabled/disabled (where GOOGLERECAPTCHA is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        $this->picto = 'technic';
        // Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
        $this->module_parts = array(
            // Set this to 1 if module has its own trigger directory (core/triggers)
            'triggers' => 0,
            // Set this to 1 if module has its own login method file (core/login)
            'login' => 1,
            // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 0,
            // Set this to 1 if module has its own menus handler directory (core/menus)
            'menus' => 0,
            // Set this to 1 if module overwrite template dir (core/tpl)
            'tpl' => 0,
            // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'barcode' => 0,
            // Set this to 1 if module has its own models directory (core/modules/xxx)
            'models' => 0,
            // Set this to 1 if module has its own theme directory (theme)
            'theme' => 0,
            // Set this to relative path of css file if module has its own css file
            'css' => array(),
            // Set this to relative path of js file if module must load a js on all pages
            'js' => array(),
            // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
            'hooks' => array(
                'data' => array(
                    'mainloginpage',
                ),
                'entity' => '0',
            ),
            // Set this to 1 if features of module are opened to external users
            'moduleforexternal' => 0,
        );
        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/googlerecaptcha/temp","/googlerecaptcha/subdir");
        $this->dirs = array("/googlerecaptcha/temp");
        // Config pages. Put here list of php page, stored into googlerecaptcha/admin directory, to use to setup module.
        $this->config_page_url = array("setup.php@googlerecaptcha");
        // Dependencies
        // A condition to hide module
        $this->hidden = false;
        // List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
        $this->depends = array();
        // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
        $this->requiredby = array();
        // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)
        $this->conflictwith = array();
        $this->langfiles = array("googlerecaptcha@googlerecaptcha");
        // Minimum version of PHP required by module
        $this->phpmin = array(5, 5);
        $this->need_dolibarr_version = array(8, -3); // Minimum version of Dolibarr required by module
        // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation = array();
        // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array();
        // $this->automatic_activation = array('FR'=>'GoogleRecaptchaWasAutomaticallyActivatedBecauseOfYourCountryChoice');
        // $this->always_enabled = true;     // If true, can't be disabled

        // Constants
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example: $this->const=array(
        //     1 => array('GOOGLERECAPTCHA_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
        //     2 => array('GOOGLERECAPTCHA_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
        // );
        $this->const = array(
            // 1 => array('GOOGLERECAPTCHA_MYCONSTANT', 'chaine', 'avalue', 'This is a constant to add', 1, 'allentities', 1)
        );

        if (!isset($conf->googlerecaptcha) || !isset($conf->googlerecaptcha->enabled)) {
            $conf->googlerecaptcha = new stdClass();
            $conf->googlerecaptcha->enabled = 0;
        }

        // Array to add new pages in new tabs
        $this->tabs = array();

        // Dictionaries
        $this->dictionaries = array();

        // Boxes/Widgets
        // Add here list of php file(s) stored in googlerecaptcha/core/boxes that contains a class to show a widget.
        $this->boxes = array();

        // Cronjobs (List of cron jobs entries to add when module is enabled)
        // unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
        $this->cronjobs = array();

        // Permissions provided by this module
        $this->rights = array();

        // Main menu entries to add
        $this->menu = array();
        $r = 0;
    }

    /**
     *  Function called when module is enabled.
     *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *  It also creates data directories
     *
     *  @param      string  $options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        $result = $this->_load_tables('/googlerecaptcha/sql/');
        if ($result < 0) return -1; // Do not activate module if error 'not allowed' returned when loading module SQL queries (the _load_table run sql with run_sql with the error allowed parameter set to 'default')

        // Permissions
        $this->remove($options);

        $sql = array();

        return $this->_init($sql, $options);
    }

    /**
     *  Function called when module is disabled.
     *  Remove from database constants, boxes and permissions from Dolibarr database.
     *  Data directories are not deleted
     *
     *  @param      string	$options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }
}
