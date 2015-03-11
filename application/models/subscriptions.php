<?php

class Subscriptions extends MY_Model{

    const DB_TABLE = 'Subscriptions';
    const DB_TABLE_PK = 'SubscriptionID';

    /**
     *
     * @var int
     */
    public $SubscriptionID;
    
    /**
     *
     * @var int
     */
    public $PlaylistID;
    
    /**
     *
     * @var int
     */
    public $UserID;
    
    /**
     *
     * @var date
     */
    public $TSAdded_Sub;
    
    /**
     *
     * @var date
     */
    public $TSModified_Sub;
    
    /**
     *
     * @var int
     */
    public $Flags_Sub;

}
