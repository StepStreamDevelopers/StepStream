<?php
/**
 * Data class for happenings
 *
 * PHP version 5
 *
 * @category Data
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl.html AGPLv3
 * @link     http://status.net/
 *
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2011, StatusNet, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.     See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('STATUSNET')) {
    exit(1);
}


/**
 * Data class for Weekly Points
 *
 * @category WeeklyPoints
 * @package  StatusNet
 * @author   Gayathri Premachandran
 * @see      Managed_DataObject
 */
class WeeklyPoints extends Managed_DataObject
{
    const OBJECT_TYPE = 'http://activitystrea.ms/schema/1.0/event';

    public $__table = 'weekly_points'; // table name
  
    public $profile_id;            // int(11)
    public $cumulative_points;     //  int(11)
    public $week_desc; //varchar(100)
    public $nickname; // varchar(64)

    /**
     * Get an instance by key
     *
     * @param string $k Key to use to lookup (usually 'id' for this class)
     * @param mixed  $v Value to lookup
     *
     */
    function staticGet($k, $v=null)
    {
        return Memcached_DataObject::staticGet('WeeklyPoints', $k, $v);
    }



  
    public static function schemaDef()
    {
         return array(
            'description' => 'A real-world happening',
            'fields' => array(
                'profile_id' => array('type' => 'int', 'not null' => true),
                'cumulative_points' => array('type' => 'int', 'not null' => true),
                'week_desc' => array('type' => 'varchar', 'length' => 100),
                'nickname' => array('type' => 'varchar', 'length' => 64)
            ),
            'primary key' => array('profile_id'),
          
           
        );
       
    }

  static function getPoints($profile_id)
    {
        return WeeklyPoints::staticGet('profile_id', $profile_id);
    }

  
}
