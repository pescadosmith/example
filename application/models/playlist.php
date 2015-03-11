<?php

class Playlist extends MY_Model{

    const DB_TABLE = 'Playlist';
    const DB_TABLE_PK = 'PlaylistID';

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
     * @var string
     */
    public $Name_Playlist;

    /**
     *
     * @var string
     */
    public $Description_Playlist;

    /**
     *
     * @var string
     */
    public $Image_Playlist;

    /**
     *
     * @var string
     */
    public $Image_Bck_Playlist;

    /**
     *
     * @var date
     */
    public $TSAdded_Playlist;

    /**
     *
     * @var date
     */
    public $TSModified_Playlist;

    /**
     *
     * @var int
     */
    public $Flags_Playlist;

}
