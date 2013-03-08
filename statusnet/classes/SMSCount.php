<?php


if (!defined('STATUSNET')) {
    exit(1);
}

/**
 * Data class for SMSCount
 * @category  SMSCount
 * @package   StatusNet
 * @author    Gayathri Premachandran 
 *
 */
class SMSCount extends Managed_DataObject
{
    const OBJECT_TYPE = 'http://activitystrea.ms/schema/1.0/smscount';

    public $__table = 'smscount'; // table name
    public $profile_id;                   
    public $sms_count;                   
   

    /**
     * Get an instance by key
     *
     * @param string $k Key to use to lookup (usually 'id' for this class)
     * @param mixed  $v Value to lookup
     *
     * @return Happening object found, or null for no hits
     *
     */
    function staticGet($k, $v=null)
    {
        return Memcached_DataObject::staticGet('SMSCount', $k, $v);
    }

    /**
     * The One True Thingy that must be defined and declared.
     */
    public static function schemaDef()
    {
        return array(
            'description' => 'Store SMS count statistics per user',
            'fields' => array(
                'profile_id' => array('type' => 'int', 'not null' => true),
                'sms_count' => array('type' => 'int', 'not null' => true),
            ),
            'primary key' => array('profile_id')
            );          
        
    }

    static function saveNew($profile_id , $smsCount)
    {
        
      
        $ev = new SMSCount();

        $ev->profile_id  = $profile_id;
        $ev->sms_count = $smsCount;

        $ev->insert();

    }


}
