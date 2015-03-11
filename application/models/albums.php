<?php

class Albums extends MY_Model {

    const DB_TABLE = 'Albums';
    const DB_TABLE_PK = 'AlbumID';

    /**
     *
     * @var int
     */
    public $AlbumID;
    
    /**
     *
     * @var int
     */
    public $ArtistID;
    
    /**
     *
     * @var string
     */
    public $Name_Albums;
    
    /**
     *
     * @var string
     */
    public $Year_Albums;
    
    /**
     *
     * @var string
     */
    public $Image_Albums;
    
    /**
     *
     * @var date
     */
    public $TSAdded_Albums;
    
    /**
     *
     * @var date
     */
    public $TSModified_Albums;
    
    /**
     *
     * @var intF
     */
    public $Flags_Albums;

}
