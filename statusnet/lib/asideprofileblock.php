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
class AsideProfileBlock extends Widget
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


//				$url = str_replace ("index.php", "", common_local_url());
				$url = local_url();
				$cur = common_current_user();

                    if ($cur->id == $this->profile->id) { // your own page

// beginning of profile block
//        $this->element('h1', null, $this->title());

        $this->elementStart('div', array('id' => 'aside-profile-section',
                                            'class' => 'aside-profile-section'));
        $this->elementStart('div', array('id' => 'asideprofile',
                                            'class' => 'asideprofile'));		

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
                $this->out->element('span',array('class' => 'aside_name'),
                            $this->profile->getBestName());
                                    $this->element('br');          

		$this->out->element('a', array( 'class' => 'aside_edit_profile', 'href' => common_local_url('profilesettings'), 'title' => _('Edit profile settings.')),
                                       // TRANS: Link text for link on user profile.
                                       'Edit your profile & picture');
        $this->element('br');          
        $this->element('br');
		$this->out->element('a', array( 'class' => 'stats', 'href' => common_local_url('myprofile', array('nickname' =>$cur->nickname)), 'title' => _('See your steps.')),
                                       // TRANS: Link text for link on user profile.
                                       'See all your steps!');
        $this->element('br');
        $this->element('br');

        $points_obj = UserPoints::getPoints($this->profile->id); 

/*		if($points_obj == null) {       
			$this->element('span', array('class' => 'statnum'), 'No steps yet... ');
		} else 
		{ */
        $this->element('span', array('class' => 'stats'), 'Total points:');
        $this->element('span', array('class' => 'statnum'), $points_obj->cumulative_points);          
        $this->element('span', array('class' => 'stats'), 'Available points:');
        $this->element('span', array('class' => 'statnum'), $points_obj->available_points);
        $this->element('span', array('class' => 'stats'), 'Personal goal:');
        $this->element('span', array('class' => 'statnum'), $points_obj->points_index, " steps per day");
/*    	}  */
        $this->element('br');

        $this->out->elementEnd('div');
        }
		$this->out->elementEnd('ul');
        $this->out->elementEnd('div');
        
        if (common_config('site', 'social')) {
        	$this->elementStart('div', array('id' => 'socialflag',
                                            'class' => 'socialflag'));
	        $this->element('span', array('class' => 'stats'), 'Social v2');    
	        $this->out->elementEnd('div');
        }
        else {
        	$this->elementStart('div', array('id' => 'socialflag',
                                            'class' => 'socialflag'));
	        $this->element('span', array('class' => 'stats'), 'v2');    
	        $this->out->elementEnd('div');
        }
        
        if (common_config('site', 'social') && ($this->title()=='Home')) {
        	$this->elementStart('div', array('id' => 'top3',
                                            'class' => 'asdf'));
	        $this->element('span', array('class' => 'stats'), 'Your Top 3');    
	        $this->out->elementEnd('div');
        	$this->elementStart('div', array('id' => 'top3main',
                                            'class' => 'asdf'));
	        $this->element('span', array('class' => 'stats'), 'Asdf');    
	        $this->out->elementEnd('div');

        	$this->elementStart('div', array('id' => 'gamepromo',
                                            'class' => 'asdf'));

            $this->elementStart('a', array('class' => 'stats',
                                           'href' => common_local_url('game', array('nickname' =>$cur->nickname))));
                $this->element('img', array('class' => 'promo',
                                            'src' => Theme::path('promo.png'),
                                            'alt' => common_config('site', 'name'))); 
            $this->out->elementEnd('a');
    
	        $this->out->elementEnd('div');
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

       $this->showActions();

        $this->out->elementStart('div', 'profile_block account_profile_block section');
        if (Event::handle('StartShowAsideProfileBlock', array($this->out, $this->profile))) {


            Event::handle('EndShowAsideProfileBlock', array($this->out, $this->profile));
        }
        $this->out->elementEnd('div');
    }
}

