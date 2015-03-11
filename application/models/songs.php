<?php

class Songs extends MY_Model{

    const DB_TABLE = 'Songs';
    const DB_TABLE_PK = 'SongID';

    /**
     *
     * @var int
     */
    public $SongID;
    
    /**
     *
     * @var string
     */
    public $Name_Song;
    
    /**
     *
     * @var int
     */
    public $ArtistID;
    
    /**
     *
     * @var date
     */
    public $TSAdded_Song;
    
    /**
     *
     * @var date
     */
    public $TSModified_Song;
    
    /**
     *
     * @var int
     */
    public $EstimateDuration;
    
    /**
     *
     * @var int
     */
    public $Flags_Song;

}
