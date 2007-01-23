<?php

class SuperUserInstallScript extends eZInstallScriptPackageInstaller
{
    function SuperUserInstallScript( &$package, $type, $installItem )
    {
        $steps = array();
        $steps[] = array( 'id' => 'SuperUser_settings',
                          'name' => 'SuperUser  settings installation',
                          'methods' => array( 'initialize' => 'initializeSettingsStep',
                                              'validate' => 'validateSettingsStep',
                                              'commit' => 'commitSettingsStep' ),
                          'template' => 'superusersettings.tpl' );
        $this->eZPackageInstallationHandler( $package,
                                             $type,
                                             $installItem,
                                             'SuperUser installation',
                                             $steps );
    }

    function initializeSettingsStep( &$package, &$http, $step, &$persistentData, &$tpl, &$module )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();

        // sometimes admin is listed twice in site access list, therefor we strip non-unique items from the array
        $siteAccessList = array_unique( $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) );
        $selectedSiteAccessList = array();
        if ( isset( $persistentData['selected_siteaccess_list'] ) )
        {
            $selectedSiteAccessList = $persistentData['selected_siteaccess_list'];
        }

        $password = '';
        if ( array_key_exists( 'password', $persistentData ) )
        {
            $password = $persistentData['password'];
        }

        $tpl->setVariable( 'password', $password );
        $tpl->setVariable( 'siteaccess_list', $siteAccessList );
        $tpl->setVariable( 'selected_siteaccess_list', $selectedSiteAccessList );

        return true;
    }

    function validateSettingsStep( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $password = '';
        if ( $http->hasPostVariable( 'Password' ) )
        {
            $password = trim( $http->postVariable( 'Password' ) );
        }
        $persistentData['password'] = $password;

        if ( $password == '' || $password == 'publish' )
        {
            $errorList[] = array( 'field' => 'password', 'description' => 'you must fill in a value' );
        }

        $selectedSiteAccessList = array();
        if ( $http->hasPostVariable( 'SelectedSiteAccessList' ) )
        {
            $selectedSiteAccessList = $http->postVariable( 'SelectedSiteAccessList' );
        }
        $persistentData['selected_siteaccess_list'] = $selectedSiteAccessList;

        if ( count( $errorList ) > 0 )
        {
            return false;
        }

        return true;
        return false;
    }


    function commitSettingsStep( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $selectedSiteAccessList = $persistentData['selected_siteaccess_list'];
        $password = $persistentData['password'];

        foreach ( $selectedSiteAccessList as $siteAccess )
        {
            eZDebug::writeDebug( $siteAccess, 'site access name' );
            $path = "settings/siteaccess/$siteAccess";

            $siteAccessReadIni =& eZINI::instance( 'site.ini', 'settings', null, null, false );
            $siteAccessReadIni->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $siteAccessReadIni->loadCache();

            // last param true is required for adding an array definition element
            // it can't be used for reading, when it finds an array definition it will threat it as a regular item in the array
            $siteAccessIni =& eZINI::instance( 'site.ini.append', $path, null, null, null, true, true );

            if ( $siteAccessReadIni->hasVariable( 'UserSettings', 'LoginHandler' ) )
            {
                $loginHandlers = $siteAccessReadIni->variable( 'UserSettings', 'LoginHandler' );
            }
            else
            {
                $loginHandlers = array();
            }

            //eZDebug::writeDebug( $loginHandlers, 'login handler array' );
            if ( count( $loginHandlers ) == 0 || $loginHandlers[0] != 'super' )
            {
                array_unshift( $loginHandlers, 'super' );
                // add a null element at the start of the login handler array, so it gets reset
                array_unshift( $loginHandlers, null );
                $siteAccessIni->setVariable( 'UserSettings', 'LoginHandler', $loginHandlers );
                $writeOk = $siteAccessIni->save(); // false, false, false, false, true, true );
                eZDebug::writeDebug( $writeOk, 'write ok' );
            }

            $siteAccessSuperUserIni =& eZINI::instance( 'superuser.ini.append', $path, null, null, null, true, true );
            $siteAccessSuperUserIni->setVariable( 'UserSettings', 'SuperPassword', md5( $password ) );

            $writeOk = $siteAccessSuperUserIni->save();
        }

        return true;
    }
}
?>