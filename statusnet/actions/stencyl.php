<?php
/*
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

if (!defined('STATUSNET') && !defined('LACONICA')) { exit(1); }

// You have 24 hours to claim your password

//define('MAX_RECOVERY_TIME', 24 * 60 * 60);

class StencylAction extends Action
{
    protected $user        = null;
    protected $error       = null;
    protected $complete    = null;
    protected $step_count  = null;
    protected $step_date    = null;

    protected $description = null;
    protected $OBJECT_TYPE = 'http://activitystrea.ms/schema/1.0/game';

    /**
     * Returns the title of the action
     *
     * @return string Action title
     */
    function title()
    {
        
        return _m('TITLE','Stencyl Action');
    }

    /**
     * For initializing members of the class.
     *
     * @param array $argarray misc. arguments
     *
     * @return boolean true
     */
    function prepare($argarray)
    {
        parent::prepare($argarray);

     
        return true;
    }

    /**
     * Handler method
     *
     * @param array $argarray is ignored since it's now passed in in prepare()
     *
     * @return void
     */
    function handle($argarray=null)
    {
        parent::handle($argarray);
   

        
        $profile_id = $_REQUEST['profileId'];
        
        $tokensEarned =  $_REQUEST['tokensEarned']; 
/*	      $points_obj = UserPoints::getPoints($profile_id);
	      $newPointsObj = $points_obj;
	      $points_obj->available_points = $tokensEarned;
	      $points_obj->delete();
        $newPointsObj->insert();
*/       
        $options=array();
        $options = array_merge(array('object_type' => $this->OBJECT_TYPE),
                               $options);

        if (!array_key_exists('uri', $options)) {
            $options['uri'] = $ev->uri;
        }

        $user = User::staticGet('id', $profile_id);
        $playerProfile = $user->getProfile();
        $subscriptions = $user->getSubscriptions();
        
        $hasfriends=0;
        $i=0;
        while ($subscriptions->fetch()) {
           if($subscriptions->id != $profile_id )
             {
              $hasfriends=1;
              $friend = User::staticGet('id', $subscriptions->id);
              $friendProfile = $friend->getProfile();
              $friendProfileId = $friendProfile->id;
              
              $smsCount = SMSCount::staticGet('profile_id' , $friendProfileId);
              $prevCount = $smsCount->sms_count;
              
              if($prevCount < 2)
              {
                  $body = $playerProfile->fullname . " found " . $tokensEarned . " stepping stones for you on ". common_config('site', 'name');

                  $client   = new HTTPClient();
                  $response = $client->get(common_config('sms', 'url') . "?phone_number=" . $friend->phone_num ."&messageBody=" . urlencode($body));
                  
                  if ($response->getStatus() == 200) {
                        if(!empty($smsCount))
                            $smsCount->delete();
                        SMSCount::saveNew($friendProfile->id , $prevCount + 1);
                  }
              }
              $friends[$i++] = $friendProfile->fullname;
              }
        }
        if ($hasfriends==0)
        {
         	$steppingstoneswith = " stepping stones";
        }
        else {
        	$steppingstoneswith = " stepping stones with ";
        }
        
        $content=$playerProfile->fullname . " found " . $tokensEarned . $steppingstoneswith .implode(",", $friends) . "!";

        $saved = Notice::saveNew($profile_id,
                                 $content,
                                 array_key_exists('source', $options) ?
                                 $options['source'] : 'Game',
                                 $options);
        }

        

        
    


    function showContent()

    {
       
        return;
    }

    /**
     * Return true if read only.
     *
     * MAY override
     *
     * @param array $args other arguments
     *
     * @return boolean is read only action?
     */
     function showNotice($notice)
    {
        $nli = new NoticeListItem($notice, $this);
        $nli->show();
    }

}
?>
