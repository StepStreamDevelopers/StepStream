<?php
/**
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2010,2011, StatusNet, Inc.
 *
 * Settings menu
 *
 * PHP version 5
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Widget
 * @package   StatusNet
 * @author    Evan Prodromou <evan@status.net>
 * @copyright 2010,2011 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET')) {
    // This check helps protect against security problems;
    // your code file can't be executed directly from the web.
    exit(1);
}

/**
 * A widget for showing the settings group local nav menu
 *
 * @category Widget
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link     http://status.net/
 *
 * @see      HTMLOutputter
 */
class SettingsNav extends Menu
{
    /**
     * Show the menu
     *
     * @return void
     */
    function show()
    {
        $actionName = $this->action->trimmed('action');
        $user = common_current_user();
        $nickname = $user->nickname;
        $name = $user->getProfile()->getBestName();

        // Stub section w/ home link
        $this->action->elementStart('ul');
        $this->action->elementStart('li');
        // TRANS: Header in settings navigation panel.
//        $this->action->element('h3', null, _m('HEADER','Home'));
/*        $this->action->elementStart('ul', 'nav');
               $this->out->menuItem(common_local_url('public'), _m('MENU','Stream'), 
               // TRANS: Menu item title in search group navigation panel.
                _('Public timeline'), $this->actionName == 'public', 'nav_left');
       
            $this->out->menuItem(common_local_url('replies', array('nickname' =>
                                                                   $nickname)),
                                 // TRANS: Menu item in personal group navigation menu.
                                 _m('MENU','Tips'),
                                 // TRANS: Menu item title in personal group navigation menu.
                                 // TRANS: %s is a username.
                                 sprintf(_('Replies to %s'), $name),
                                 $mine && $action =='replies', 'nav_middle');
      

            $cur = common_current_user();

            if ($cur && $cur->id == $user->id &&
                !common_config('singleuser', 'enabled')) {

         

   $this->out->menuItem(common_local_url('myprofile', array('nickname' =>
                                                                     $nickname)),
                                     // TRANS: Menu item in personal group navigation menu.
                                     _m('MENU','Me'),
                                     // TRANS: Menu item title in personal group navigation menu.
                                     _('Your profile information'),
                                     $mine && $action =='myprofile', 'nav_right');
            }


        $this->action->elementEnd('ul');
*/
        $this->action->elementEnd('li');
        $this->action->elementEnd('ul');

    }
}

class SettingsSubNav extends Menu
{
    /**
     * Show the menu
     *
     * @return void
     */
    function show()
    {
        $actionName = $this->action->trimmed('action');
        $user = common_current_user();
        $nickname = $user->nickname;
        $name = $user->getProfile()->getBestName();


	$this->action->elementStart('div', array('class' => 'settingsnav'));
        $this->action->elementStart('ul');
        $this->action->elementStart('li');
        // TRANS: Header in settings navigation panel.
//        $this->action->element('h3', null, _m('HEADER','Settings'));
        $this->action->elementStart('ul', array('class' => 'settingsnav'));

        if (Event::handle('StartAccountSettingsNav', array(&$this->action))) {
            $this->action->menuItem(common_local_url('profilesettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','Your Name'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Change your profile settings'),
                                    $actionName == 'profilesettings');
            $this->action->menuItem(common_local_url('passwordsettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','Password'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Change your password'),
                                    $actionName == 'passwordsettings');
            $this->action->menuItem(common_local_url('emailsettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','Email'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Change email handling'),
                                    $actionName == 'emailsettings');
 
            $this->action->menuItem(common_local_url('smssettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','SMS'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Change SMS handling'),
                                    $actionName == 'smssettings');
            $this->action->menuItem(common_local_url('avatarsettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','Your Picture'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Upload a picture of you'),
                                    $actionName == 'avatarsettings');



/*            $this->action->menuItem(common_local_url('urlsettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','URL'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('URL shorteners'),
                                    $actionName == 'urlsettings');
*/
            Event::handle('EndAccountSettingsNav', array(&$this->action));

            if (common_config('xmpp', 'enabled')) {
                $this->action->menuItem(common_local_url('imsettings'),
                                        // TRANS: Menu item in settings navigation panel.
                                        _m('MENU','IM'),
                                        // TRANS: Menu item title in settings navigation panel.
                                        _('Updates by instant messenger (IM)'),
                                        $actionName == 'imsettings');
            }

            if (common_config('sms', 'enabled')) {
                $this->action->menuItem(common_local_url('smssettings'),
                                        // TRANS: Menu item in settings navigation panel.
                                        _m('MENU','SMS'),
                                        // TRANS: Menu item title in settings navigation panel.
                                        _('Updates by SMS'),
                                        $actionName == 'smssettings');
            }

 /*           $this->action->menuItem(common_local_url('oauthconnectionssettings'),
                                    // TRANS: Menu item in settings navigation panel.
                                    _m('MENU','Connections'),
                                    // TRANS: Menu item title in settings navigation panel.
                                    _('Authorized connected applications'),
                                    $actionName == 'oauthconnectionsettings');
*/
            Event::handle('EndConnectSettingsNav', array(&$this->action));
        }

        $this->action->elementEnd('ul');
        $this->action->elementEnd('li');
        $this->action->elementEnd('ul');
    $this->action->elementEnd('div');
    }
}