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

class TwilioAction extends Action
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
        return _m('TITLE','New event');
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
   

        $ev = new Happening();
        $ev->id          = UUID::gen();
        $phone_num = $_REQUEST['From'];
        $user_tw = User::staticGet('phone_num',$phone_num);
        $phone_num_invalid = false;
        if($user_tw != null)
        {   
        $ev->profile_id = $user_tw->id;
        $sms_body = explode(" ", $_REQUEST['Body']);
        $input_count = count($sms_body);
        $step_count =  $sms_body[$input_count - 1];
	$ev->step_count = $step_count;
        $error_flag = false;

        //$ev->description = $description;
        $points_obj = UserPoints::getPoints($ev->profile_id);
        if($points_obj != null)
        $points_index = pow(10,($points_obj->points_index - 1));
        else
        $points_index = 1;
        $points_earned = ($step_count / $points_index ) + ($step_count % $points_index);
        $ev->points_earned = $points_earned; 

        $base_time_init = "200000";
        $base_time   =  strtotime($base_time_init);
        $cur_time   =   strtotime(now);
        $step_date="null";
        if($input_count == 1)
        {
        if($cur_time < $base_time)
          $step_date    = date("m/d/Y" ,strtotime("yesterday"));

        else
          $step_date    = date("m/d/Y");
         }

         else
         {
 		if (date("m/d/Y", strtotime($sms_body[0])) == $sms_body[0]) {
	        $step_date = $sms_body[0];
    	          } else {
        	$error_flag = true;
                 }
	  }         
          
       if($error_flag == false)
       {
        $ev->step_date = $step_date;
        $ev->created = common_sql_now();
        $ev->uri = common_local_url('showevent',
                                        array('id' => $ev->id));

        $step_prev =  Happening::pkeyGet(array('profile_id' =>$ev->profile_id,'step_date' => $ev->step_date));
        if(empty($step_prev))
	   $ev->insert();
        else
         {
           $step_prev->delete();
           $ev->insert();
         }

    // XXX: does this get truncated?

        // TRANS: Event description. %1$s is a title, %2$s is start time, %3$s is end time,
	// TRANS: %4$s is location, %5$s is a description.
        $content = sprintf(_m('"%1$s" %2$s %3$s'),$ev->step_date, $step_count,$points_earned);

        // TRANS: Rendered event description. %1$s is a title, %2$s is start time, %3$s is start time,
	// TRANS: %4$s is end time, %5$s is end time, %6$s is location, %7$s is description.
	// TRANS: Class names should not be translated.
        /* $rendered = sprintf(_m('<span class="vevent">'),
                            htmlspecialchars($description)
                            ); */
        
        $options=array();
        $options = array_merge(array('object_type' => Happening::OBJECT_TYPE),
                               $options);

        if (!array_key_exists('uri', $options)) {
            $options['uri'] = $ev->uri;
        }



        $saved = Notice::saveNew($user_tw->id,
                                 $content,
                                 array_key_exists('source', $options) ?
                                 $options['source'] : 'twilio',
                                 $options);
        }

        }

        else
           $phone_num_invalid = true;

        $parameter_string="";
        if($saved != null && $error_flag == false)
         	$parameter_string = "error_flag=$error_flag&step_count=$step_count&step_date=$step_date&phone_number=$phone_num&phone_num_invalid=$phone_num_invalid";
	
        else
		$parameter_string = "error_flag=$error_flag&phone_number=$phone_num&phone_num_invalid=$phone_num_invalid";
	
        ob_start();
        header("Location: http://salute.cc.gt.atl.ga.us/statusnet/Twilio/sendnotifications.php?$parameter_string");
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
