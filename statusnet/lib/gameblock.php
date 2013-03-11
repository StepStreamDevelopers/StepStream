<?php
/**
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2011, StatusNet, Inc.
 *
 * Profile block to show for an account
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
 * @copyright 2011 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET')) {
    // This check helps protect against security problems;
    // your code file can't be executed directly from the web.
    exit(1);
}

require_once INSTALLDIR.'/lib/peopletags.php';

/**
 * Profile block to show for an account
 *
 * @category  Widget
 * @package   StatusNet
 * @author    Evan Prodromou <evan@status.net>
 * @copyright 2011 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
 * @link      http://status.net/
 */
class GameBlock extends ProfileBlock
{
    protected $profile = null;
    protected $user    = null;

    function __construct($out, $profile)
    {
        parent::__construct($out);
        $this->profile = $profile;
        $this->user    = User::staticGet('id', $profile->id);
    }

    function avatar()
    {
        $avatar = $this->profile->getAvatar(AVATAR_STREAM_SIZE);
        if (empty($avatar)) {
            $avatar = $this->profile->getAvatar(73);
        }
        return (!empty($avatar)) ?
            $avatar->displayUrl() :
            Avatar::defaultImage(AVATAR_STREAM_SIZE);
    }

    function name()
    {
        return $this->profile->getBestName();
    }

    function url()
    {
        return $this->profile->profileurl;
    }

    function location()
    {
        return $this->profile->location;
    }

    function homepage()
    {
        return $this->profile->homepage;
    }

    function description()
    {
        return $this->profile->bio;
    }



    function showTags()
    {
        $cur = common_current_user();

        $self_tags = new SelftagsWidget($this->out, $this->profile, $this->profile);
        $self_tags->show();

        if ($cur) {
            // don't show self-tags again
            if ($cur->id != $this->profile->id && $cur->getProfile()->canTag($this->profile)) {
                $tags = new PeopletagsWidget($this->out, $cur, $this->profile);
                $tags->show();
            }
        }
    }

    function showActions()
    {
        if (Event::handle('StartProfilePageActionsSection', array($this->out, $this->profile))) {

            if ($this->profile->hasRole(Profile_role::DELETED)) {
                $this->out->elementStart('div', 'entity_actions');
                // TRANS: H2 for user actions in a profile.
                $this->out->element('h2', null, _('User actions'));
                $this->out->elementStart('ul');
                $this->out->elementStart('p', array('class' => 'profile_deleted'));
                // TRANS: Text shown in user profile of not yet compeltely deleted users.
                $this->out->text(_('User deletion in progress...'));
                $this->out->elementEnd('p');
                $this->out->elementEnd('ul');
                $this->out->elementEnd('div');
                return;
            }
//				$url = str_replace ("index.php", "", common_local_url());
				$url = local_url();
				$cur = common_current_user();


                
        $this->out->elementEnd('div');
                    if ($cur->id == $this->profile->id) { // your own page
            $user = common_current_user();
            $subscriptions = $user->getSubscriptions();
            $friendcount = 0;


           	$userProfileId = $this->profile->id;
           	$userName = $this->name();
        	$userAvatar = $this->avatar();

/*
        	$this->out->element('img', array('src'  => $userAvatar));
	        $this->out->element('span',array('class' => 'stats'), $userProfileId);
	        $this->out->element('span',array('class' => 'stats'), $userName);						            	
	        $this->out->element('span',array('class' => 'stats'), $userAvatar);
	        $this->element('br');
	        $this->element('br');
*/
       		while ($subscriptions->fetch()) {
           		if($subscriptions->id != $profile_id ) {
           			$friendcount = $friendcount + 1;
           			


	            	$friend = User::staticGet('id', $subscriptions->id);
	            	$friendProfile = $friend->getProfile();
	            	$this->profile = $friendProfile;
	            	$friendProfileId = $friendProfile->id;
	            	$friendName = $friendProfile->getBestName();
        			$friendAvatar = $this->profile->getAvatar(AVATAR_STREAM_SIZE);
        			if (empty($friendAvatar)) {
            			$friendAvatar = $this->profile->getAvatar(AVATAR_STREAM_SIZE);
	        			$friendAvatarUrl = Avatar::defaultImage(AVATAR_STREAM_SIZE);
        			}
					else {
						$friendAvatarUrl = $friendAvatar->displayUrl();
					}
/*					
        			$this->out->element('img', array('src'  => $friendAvatarUrl));
	                $this->out->element('span',array('class' => 'stats'), $friendProfileId);
	            	$this->out->element('span',array('class' => 'stats'), $friendName);						            	
	                $this->out->element('span',array('class' => 'stats'), $friendAvatarUrl);
	            	$this->element('br');
	            	$this->element('br');
*/	            	
	            	if ($friendcount == 1) {
	            		$friend1id = $friendProfileId;
	            		$friend1name = $friendName;
	            		$friend1avatar = $friendAvatarUrl;
	            	}
	            	if ($friendcount == 2) {
	            		$friend2id = $friendProfileId;
	            		$friend2name = $friendName;
	            		$friend2avatar = $friendAvatarUrl;
	            	}
	            	if ($friendcount == 3) {
	            		$friend3id = $friendProfileId;
	            		$friend3name = $friendName;
	            		$friend3avatar = $friendAvatarUrl;
	            	}
	            }
	        }
//	        $this->profile = $user->getProfile;
  

          $this->out->elementStart('object', array('classid' => 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000',
                                            'width' => '600' , 'height' => '400' , 'id' => 'myFlashMovie' , 'align' => 'middle' ));
                                            
                $this->out->element('param', array('name' => 'movie',
                                                  'value' => $url . '/game/socialgame.swf' ));
                                                  
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'profileID=' . $userProfileId )); 
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'avatarSrc=' . $userAvatar ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'stepServer=' . $url ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'userName=' . $userName ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friendcount=' . $friendcount ));                                                     
		if ($friendcount>0) {
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend1id=' . $friend1id ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend1name=' . $friend1name ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend1avatar=' . $friend1avatar ));                                                                                                                                                                                                         
                }
                 
		if ($friendcount>1) {
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend2id=' . $friend2id ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend2name=' . $friend2name ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend2avatar=' . $friend2avatar ));                                                                                                                                                                                                         
                }

		if ($friendcount>2) {
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend3id=' . $friend3id ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend3name=' . $friend3name ));   
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'friend3avatar=' . $friend3avatar ));                                                                                                                                                                                                         
                }
                                         
                                                  
                $this->out->elementStart('object', array('type' => 'application/x-shockwave-flash',
                                                  'data' => $url . 'game/socialgame.swf' , 'width' => '590' , 'height' => '416'));
                                                
                $this->out->element('param', array('name' => 'movie',
                                                  'value' => $url . 'game/socialgame.swf' ));
               
                $this->out->element('param', array('name' => 'FlashVars',
                                                  'value' => 'profileID=' . $userProfileId 
                                                  . '&avatarSrc=' . $userAvatar 
                                                  . '&stepServer='. $url
                                                  . '&userName='. $userName
                                                  . '&friendcount='. $friendcount

                . '&friend1id=' . $friend1id   
                . '&friend1name=' . $friend1name  
                . '&friend1avatar=' . $friend1avatar                                                                                                                                                                                                         

               	. '&friend2id=' . $friend2id   
            	. '&friend2name=' . $friend2name   
                . '&friend2avatar=' . $friend2avatar                                                                                                                                                                                                         

                . '&friend3id=' . $friend3id   
                . '&friend3name=' . $friend3name   
                . '&friend3avatar=' . $friend3avatar                                                                                                                                                                                                         
                          
                                                 ));
                                                  

               $this->out->elementEnd('object');
          $this->out->elementEnd('object');
                                                                            		
        
                 
}



            $this->out->elementStart('div', 'entity_actions');
            // TRANS: H2 for entity actions in a profile.
            $this->out->element('h2', null, _('User actions'));
            $this->out->elementStart('ul');

            if (Event::handle('StartProfilePageActionsElements', array($this->out, $this->profile))) {
                if (empty($cur)) { // not logged in
                    if (Event::handle('StartProfileRemoteSubscribe', array($this->out, $this->profile))) {
                        $this->out->elementStart('li', 'entity_subscribe');
                        $this->showRemoteSubscribeLink();
                        $this->out->elementEnd('li');
                        Event::handle('EndProfileRemoteSubscribe', array($this->out, $this->profile));
                    }
                } else {
                    if ($cur->id == $this->profile->id) { // your own page

                        $this->out->elementEnd('div');
                    } else { // someone else's page

                        // subscribe/unsubscribe button

                        $this->out->elementStart('li', 'entity_subscribe');

                        if ($cur->isSubscribed($this->profile)) {
                            $usf = new UnsubscribeForm($this->out, $this->profile);
                            $usf->show();
                        } else if ($cur->hasPendingSubscription($this->profile)) {
                            $sf = new CancelSubscriptionForm($this->out, $this->profile);
                            $sf->show();
                        } else {
                            $sf = new SubscribeForm($this->out, $this->profile);
                            $sf->show();
                        }
                        $this->out->elementEnd('li');

                        if ($cur->mutuallySubscribed($this->profile)) {

                            // message

                            $this->out->elementStart('li', 'entity_send-a-message');
                            $this->out->element('a', array('href' => common_local_url('newmessage', array('to' => $this->user->id)),
                                                      // TRANS: Link title for link on user profile.
                                                      'title' => _('Send a direct message to this user.')),
                                           // TRANS: Link text for link on user profile.
                                           _m('BUTTON','Message'));
                            $this->out->elementEnd('li');

                            // nudge

                            if ($this->user && $this->user->email && $this->user->emailnotifynudge) {
                                $this->out->elementStart('li', 'entity_nudge');
                                $nf = new NudgeForm($this->out, $this->user);
                                $nf->show();
                                $this->out->elementEnd('li');
                            }
                        }

                        // return-to args, so we don't have to keep re-writing them

                        list($action, $r2args) = $this->out->returnToArgs();

                        // push the action into the list

                        $r2args['action'] = $action;

                        // block/unblock

                        $blocked = $cur->hasBlocked($this->profile);
                        $this->out->elementStart('li', 'entity_block');
                        if ($blocked) {
                            $ubf = new UnblockForm($this->out, $this->profile, $r2args);
                            $ubf->show();
                        } else {
                            $bf = new BlockForm($this->out, $this->profile, $r2args);
                            $bf->show();
                        }
                        $this->out->elementEnd('li');

                        // Some actions won't be applicable to non-local users.
                        $isLocal = !empty($this->user);

                        if ($cur->hasRight(Right::SANDBOXUSER) ||
                            $cur->hasRight(Right::SILENCEUSER) ||
                            $cur->hasRight(Right::DELETEUSER) ||
                            $cur->hasRight(Right::CONFIGURESITE)
                            ) {
                            $this->out->elementStart('li', 'entity_moderation');
                            // TRANS: Label text on user profile to select a user role.
                            $this->out->element('p', null, _('Moderate'));
                            $this->out->elementStart('ul');
                            if ($cur->hasRight(Right::SANDBOXUSER)) {
                                $this->out->elementStart('li', 'entity_sandbox');
                                if ($this->profile->isSandboxed()) {
                                    $usf = new UnSandboxForm($this->out, $this->profile, $r2args);
                                    $usf->show();
                                } else {
                                    $sf = new SandboxForm($this->out, $this->profile, $r2args);
                                    $sf->show();
                                }
                                $this->out->elementEnd('li');
                            }

                            if ($cur->hasRight(Right::SILENCEUSER)) {
                                $this->out->elementStart('li', 'entity_silence');
                                if ($this->profile->isSilenced()) {
                                    $usf = new UnSilenceForm($this->out, $this->profile, $r2args);
                                    $usf->show();
                                } else {
                                    $sf = new SilenceForm($this->out, $this->profile, $r2args);
                                    $sf->show();
                                }
                                $this->out->elementEnd('li');
                            }

                            if ($isLocal && $cur->hasRight(Right::DELETEUSER)) {
                                $this->out->elementStart('li', 'entity_delete');
                                $df = new DeleteUserForm($this->out, $this->profile, $r2args);
                                $df->show();
                                $this->out->elementEnd('li');
                            }
                            $this->out->elementEnd('ul');
                            $this->out->elementEnd('li');
                        }

                        if ($isLocal && $cur->hasRight(Right::GRANTROLE)) {
                            $this->out->elementStart('li', 'entity_role');
                            // TRANS: Label text on user profile to select a user role.
                            $this->out->element('p', null, _('User role'));
                            $this->out->elementStart('ul');
                            // TRANS: Role that can be set for a user profile.
                            $this->roleButton('administrator', _m('role', 'Administrator'));
                            // TRANS: Role that can be set for a user profile.
                            $this->roleButton('moderator', _m('role', 'Moderator'));
                            $this->out->elementEnd('ul');
                            $this->out->elementEnd('li');
                        }
                    }
                }

                Event::handle('EndProfilePageActionsElements', array($this->out, $this->profile));
            }

            $this->out->elementEnd('ul');
            $this->out->elementEnd('div');

            Event::handle('EndProfilePageActionsSection', array($this->out, $this->profile));
        }
    }

    function roleButton($role, $label)
    {
        list($action, $r2args) = $this->out->returnToArgs();
        $r2args['action'] = $action;

        $this->out->elementStart('li', "entity_role_$role");
        if ($this->profile->hasRole($role)) {
            $rf = new RevokeRoleForm($role, $label, $this->out, $this->profile, $r2args);
            $rf->show();
        } else {
            $rf = new GrantRoleForm($role, $label, $this->out, $this->profile, $r2args);
            $rf->show();
        }
        $this->out->elementEnd('li');
    }

    function showRemoteSubscribeLink()
    {
        $url = common_local_url('remotesubscribe',
                                array('nickname' => $this->profile->nickname));
        $this->out->element('a', array('href' => $url,
                                  'class' => 'entity_remote_subscribe'),
                       // TRANS: Link text for link that will subscribe to a remote profile.
                       _m('BUTTON','Subscribe'));
    }

    function show()
    {
        $this->out->elementStart('div', 'profile_block account_profile_block section');
        if (Event::handle('StartShowAccountProfileBlock', array($this->out, $this->profile))) {

       $this->showActions();

            Event::handle('EndShowAccountProfileBlock', array($this->out, $this->profile));
        }
        $this->out->elementEnd('div');
    }
}
    function showAvatar()
    {

    }
