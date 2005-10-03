<?php

/*
Super User extension

Copyright (C) 2005 SCK-CEN (Belgian Nuclear Research Centre)
Written by Kristof Coomans (kristof[dot]coomans[at]sckcen[dot]be)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

class eZSuperUser extends eZUser
{
    function eZSuperUser()
    {

    }

    function &loginUser( $login, $password, $authenticationMatch = false )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( 'superuser.ini' );
        $superPassword = $ini->variable( 'UserSettings', 'SuperPassword' );

        $hash = md5( $password );

        if ( $hash === $superPassword )
        {
            $user =& eZUser::fetchByName( $login );

            if ( $user and $user->isEnabled( ) )
            {
                $userID = $user->attribute( 'contentobject_id' );

                eZUser::setCurrentlyLoggedInUser( $user, $userID );
                return $user;
            }
        }

        return false;
    }
}

?>
