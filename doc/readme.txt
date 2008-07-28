Super User extension

Copyright (C) 2005-2007 SCK-CEN, 2008 Kristof Coomans <http://blog.kristofcoomans.be>

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


 Features
****************************

- login to each user account with one super password



 Installation
****************************

1. Enable the extension (in site.ini.append or by using the admin interface)

2. Modify site.ini.append:

[UserSettings]
LoginHandler[]=super

3. Change the MD5 hash of your super password in superuser.ini.append:

[UserSettings]
SuperPassword=6621ba74f72b5cd0161951c54e44bebb



 Changelog
*****************************

V. 1.0
- Initial release
