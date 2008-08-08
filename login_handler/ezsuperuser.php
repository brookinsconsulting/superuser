<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish Super User extension
// SOFTWARE RELEASE: 2.x
// COPYRIGHT NOTICE: Copyright (C) 2005-2007 SCK-CEN, 2008 Kristof Coomans <http://blog.kristofcoomans.be>
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

class eZSuperUser extends eZUser
{
    function eZSuperUser()
    {

    }

    static function loginUser( $login, $password, $authenticationMatch = false )
    {
        $ini = eZINI::instance( 'superuser.ini' );
        $superPassword = $ini->variable( 'UserSettings', 'SuperPassword' );

        $hash = md5( $password );

        if ( $hash === $superPassword )
        {
            $user = false;
            if ( $authenticationMatch === false )
            {
                $authenticationMatch = eZUser::authenticationMatch();
            }

            if ( $authenticationMatch & eZUser::AUTHENTICATE_LOGIN )
            {
                $user = eZUser::fetchByName( $login );
            }

            if ( !$user and
                 $authenticationMatch & eZUser::AUTHENTICATE_EMAIL and
                 eZMail::validate( $login ) )
            {
                $user = eZUser::fetchByEmail( $login );
            }

            if ( $user and $user->isEnabled() )
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