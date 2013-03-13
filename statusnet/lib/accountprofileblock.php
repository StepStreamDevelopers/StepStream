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
class AccountProfileBlock extends ProfileBlock
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
        $avatar = $this->profile->getAvatar(AVATAR_PROFILE_SIZE);
        if (empty($avatar)) {
            $avatar = $this->profile->getAvatar(73);
        }
        return (!empty($avatar)) ?
            $avatar->displayUrl() :
            Avatar::defaultImage(AVATAR_PROFILE_SIZE);
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

                    if ($cur->id == $this->profile->id) { // your own page

// beginning of profile block
        $this->element('h1', null, $this->title());

        $this->elementStart('div', array('id' => 'profile-section',
                                            'class' => 'profile-section'));
                                                                if (common_config('site','safemode')){
                    	$this->out->elementStart('div','safewelcome');
            			$this->out->elementStart('span', 'welcometitle');
            			$this->out->raw("Welcome to StepStream!");
            			$this->out->elementEnd('span');
            			$this->element('br');

            			$this->out->elementStart('span', 'welcometext');
            			$this->out->raw("You're almost set. Just set up your account and in a week you'll be able to see your steps! We're keeping the site in 'safe mode' for this first week so we get an idea of how much you walk regularly. Then we'll open the site up next week and you can see how you're doing!");
            			$this->out->elementEnd('span');
            			$this->element('br');

             			$this->out->elementStart('span', 'welcometext');
            			$this->out->raw("Please enter your:");
            			$this->element('br');
            			$this->element('br');
            			$this->out->elementEnd('span');
            			
            			$this->out->elementStart('span','welcomechoice');
            			
            			$this->out->element('a', array('href' => common_local_url('profilesettings')),'Name');
            			$this->element('br');
            			$this->element('br');
            			
            			$this->out->element('a', array('href' => common_local_url('passwordsettings')),'Password');
            			$this->element('br');
            			$this->element('br');
            			
            			$this->out->element('a', array('href' => common_local_url('emailsettings')),'Email');
            			$this->element('br');
            			$this->element('br');
            			
            			$this->out->element('a', array('href' => common_local_url('smssettings')),'SMS');
            			$this->element('br');
            			$this->element('br');            			

            			$this->out->element('a', array('href' => common_local_url('avatarsettings')),'Avatar');
            			$this->element('br');
            			$this->element('br');
            			
            			$this->out->elementEnd('span');
            			           			
                    	$this->out->elementEnd('div');
                    }
        if (!common_config('site', 'safemode')&&$cur->id == $this->profile->id) {

                $this->elementStart('div', array('id' => 'stepgraph',
                                            'class' => 'stepgraph'));		
              $this->out->elementStart('iframe', array('id' => 'graph_progress' , 'width' => '760px' , 'height' => '480px', 'frameborder' => '0px', 'border' => '0px', 'cellspacing' => '0px', 'src' => $url . 'googlegraph.php?profile_id=' . $this->profile->id));




                $this->out->elementEnd('iframe');
                $this->out->elementEnd('div'); 
                
		}
                                                                            		
        
                 
}
					else {

					}


            $this->out->elementStart('div', 'entity_actions');
            
if ($cur->id != $this->profile->id) {
            // TRANS: H2 for entity actions in a profile.
					        $this->element('h4', null, $this->profile->getBestName());
        $size = $this->avatarSize();
        $this->out->element(
            'img',
            array(
                'src'  => $this->avatar(),
                'class'  => 'ur_face',
                'alt'    => $this->name(),
                'width'  => $size,
                'height' => $size
            )
        );

                                    $this->element('br');      
        $points_obj = UserPoints::getPoints($this->profile->id); 

        $this->element('span', array('class' => 'stats'), 'Available points:');
        $this->element('span', array('class' => 'statnum'), $points_obj->available_points);

        $this->element('span', array('class' => 'stats'), 'All-time points earned:');
        $this->element('span', array('class' => 'statnum'), $points_obj->cumulative_points); 
        $this->element('br'); 

}

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
                    if ($cur->id == $this->profile->id && !common_config('site','safemode')) { // your own page
                                    $this->elementStart('a', array('class' => 'stats',
                                           'href' => common_local_url('game', array('nickname' =>$cur->nickname))));
                $this->element('img', array('class' => 'promo',
                                            'src' => Theme::path('/images/playgame.png'),
                                            'alt' => common_config('site', 'name'))); 
            $this->elementEnd('a');
                        $this->out->elementEnd('div');
                    } else if (!common_config('site','safemode')){ // someone else's page

                        // subscribe/unsubscribe button

                        $this->out->elementStart('li', 'entity_subscribe');

                        if ($cur->isSubscribed($this->profile) && !($cur==$this->profile)) {
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
