<?php
/**
 * Favor action.
 *
 * PHP version 5
 *
 * @category Action
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @author   Robin Millette <millette@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl.html AGPLv3
 * @link     http://status.net/
 *
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2008, 2009, StatusNet, Inc.
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
 */

if (!defined('STATUSNET') && !defined('LACONICA')) {
    exit(1);
}

require_once INSTALLDIR.'/lib/mail.php';
require_once INSTALLDIR.'/lib/disfavorform.php';

/**
 * Favor class.
 *
 * @category Action
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @author   Robin Millette <millette@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl.html AGPLv3
 * @link     http://status.net/
 */
class FavorAction extends Action
{
    /**
     * Class handler.
     *
     * @param array $args query arguments
     *
     * @return void
     */
     
    protected $OBJECT_TYPE = 'http://activitystrea.ms/schema/1.0/favorite';
    function handle($args)
    {
        parent::handle($args);
        if (!common_logged_in()) {
            // TRANS: Error message displayed when trying to perform an action that requires a logged in user.
            $this->clientError(_('Not logged in.'));
            return;
        }
        $user = common_current_user();
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            common_redirect(common_local_url('showfavorites',
                array('nickname' => $user->nickname)));
            return;
        }
        $id     = $this->trimmed('notice');
        $notice = Notice::staticGet($id);
        $token  = $this->trimmed('token-'.$notice->id);
        if (!$token || $token != common_session_token()) {
            // TRANS: Client error displayed when the session token does not match or is not given.
            $this->clientError(_('There was a problem with your session token. Try again, please.'));
            return;
        }
        if ($user->hasFave($notice)) {
            // TRANS: Client error displayed when trying to mark a notice as favorite that already is a favorite.
            $this->clientError(_('This notice is already a favorite!'));
            return;
        }
        $fave = Fave::addNew($user->getProfile(), $notice);
        if (!$fave) {
            // TRANS: Server error displayed when trying to mark a notice as favorite fails in the database.
            $this->serverError(_('Could not create favorite.'));
            return;
        }
        $this->notify($notice, $user);
        $user->blowFavesCache();
        if ($this->boolean('ajax')) {
            $this->startHTML('text/xml;charset=utf-8');
            $this->elementStart('head');
            // TRANS: Page title for page on which favorite notices can be unfavourited.
            $this->element('title', null, _('Disfavor favorite.'));
            $this->elementEnd('head');
            $this->elementStart('body');
            $disfavor = new DisFavorForm($this, $notice);
            $disfavor->show();
            $this->elementEnd('body');
            $this->elementEnd('html');
        } else {
            common_redirect(common_local_url('showfavorites',
                                             array('nickname' => $user->nickname)),
                            303);
        }
    }

    /**
     * Notifies a user when their notice is favorited.
     *
     * @param class $notice favorited notice
     * @param class $user   user declaring a favorite
     *
     * @return void
     */
    function notify($notice, $user)
    {
        $smsCount = SMSCount::staticGet('profile_id' , $notice->profile_id);
        $prevCount = $smsCount->sms_count;
        if(empty($smsCount) || $prevCount < 2)
        {
        $other = User::staticGet('id', $notice->profile_id);
        // Count the number of notices liked until now
        
        $faveNotices = $user->favoriteNotices(false, 0, PHP_INT_MAX);
        $numFavNotices = $faveNotices->_count;
        $profile = $user->getProfile();
        if ($other->hasBlocked($profile)) {
            // If the author has blocked us, don't spam them with a notification.
            return;
        }

        $bestname = $profile->getBestName();
        
        $body = $bestname . " hearts your post on " . common_config('site', 'name') ." " . $numFavNotices;

        $client   = new HTTPClient();
        $response = $client->get(common_config('sms', 'url') . "?phone_number=" . $other->phone_num ."&messageBody=" . urlencode($body));
        
        if ($response->getStatus() == 200) {
              if(!empty($smsCount))
                  $smsCount->delete();
              SMSCount::saveNew($notice->profile_id , $prevCount + 1);
        } 
        
        $generateNoticeSMS = false;
        if($numFavNotices == 5)
         {
             $body = "Congratulations " . $bestname . ",you have liked 5 posts on " . common_config('site', 'name') . "!";
             $generateNoticeSMS = true;
         }
        else if($numFavNotices == 10)
        {
            $body = "Congratulations " . $bestname . ",you have liked 10 posts on ". common_config('site', 'name') . "!";
            $generateNoticeSMS = true;
        }
        
        else if($numFavNotices == 20)
        {
            $body = "Congratulations " . $bestname . ",you have liked 20 posts on ". common_config('site', 'name') . "!";
            $generateNoticeSMS = true;
        } 
        
        if($generateNoticeSMS)
        {
        $options=array();
        $options = array_merge(array('object_type' => $this->OBJECT_TYPE),
                               $options);
        $saved = Notice::saveNew(1,
                                 $body,
                                 array_key_exists('source', $options) ?
                                 $options['source'] : 'Favorites',
                                 $options);
                                 
        $response = $client->get(common_config('sms', 'url') . "?phone_number=" . $other->phone_num ."&messageBody=" . urlencode($body));
        
        if ($response->getStatus() == 200) {
              if(!empty($smsCount))
                  $smsCount->delete();
              SMSCount::saveNew($notice->profile_id , $prevCount + 1);
        } 
        }
        }
        
        if ($other && $other->id != $user->id) {
            if ($other->email && $other->emailnotifyfav) {
                mail_notify_fave($other, $user, $notice);
            }
            // XXX: notify by IM
            // XXX: notify by SMS
        }
    }
}
