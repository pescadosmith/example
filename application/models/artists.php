<?php

class Artists extends MY_Model {

    const DB_TABLE = 'Artists';
    const DB_TABLE_PK = 'ArtistID';

    /**
     *
     * @var int
     */
    public $ArtistID;
    
    /**
     *
     * @var string
     */
    public $Name_Artist;
    
    /**
     *
     * @var string
     */
    public $Image_Artist;
    
    /**
     *
     * @var date
     */
    public $TSAdded_Artist;
    
    /**
     *
     * @var date
     */
    public $TSModified_Artist;
    
    /**
     *
     * @var int
     */
    public $Flags_Artist;

}
