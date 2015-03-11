<?php

class Users extends MY_Model{

    const DB_TABLE = 'Users';
    const DB_TABLE_PK = 'UserID';

    /**
     *
     * @var int
     */
    public $UserID;

    /**
     *
     * @var string
     */
    public $Username;

    /**
     *
     * @var string
     */
    public $DisplayName;

    /**
     *
     * @var string
     */
    public $Email;

    /**
     *
     * @var int
     */
    public $Flags_User;

    /**
     *
     * @var date
     */
    public $TSAdded_User;

    /**
     *
     * @var date
     */
    public $TSModified_User;

    /**
     *
     * @var string
     */
    public $Image_User;

}
