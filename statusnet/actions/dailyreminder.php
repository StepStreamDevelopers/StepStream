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

class DailyreminderAction extends Action
{
    protected $user        = null;
    protected $error       = null;
    protected $complete    = null;
    protected $step_count  = null;
    protected $step_date    = null;
    //protected $step_time    = null;
    protected $description = null;
  

    /**
     * Returns the title of the action
     *
     * @return string Action title
     */
    function title()
    {
        // TRANS: Title for new event form.
        return _m('TITLE','Daily Reminder');
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
		$ids = array();
         $ids[] = 1;
        $phone_num_str = "";
		$users_list = Memcached_DataObject::_query("SELECT * from user where dailyreminder=1");


while ($row = mysql_fetch_assoc($users_list)) {
  
              $phone_num_str = $phone_num_str . $row['phone_num'];
}
     
        header("Location: http://salute.cc.gt.atl.ga.us/statusnet/Twilio/senddailyreminders.php?phone_num=$phone_num_str");
        exit();
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
