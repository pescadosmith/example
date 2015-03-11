<?php

class AlbumsSongs extends MY_Model{

    const DB_TABLE = 'AlbumsSongs';
    const DB_TABLE_PK = 'AlbumSongID';

    /**
     *
     * @var int
     */
    public $AlbumSongID;

    /**
     *
     * @var int
     */
    public $AlbumID;

    /**
     *
     * @var int
     */
    public $SongID;

    /**
     *
     * @var int
     */
    public $TrackNum;

    /**
     *
     * @var string
     */
    public $SongFilename;

    /**
     *
     * @var int
     */
    public $Flags_AS;

    /**
     *
     * @var int
     */
    public $IsPrimary;

    /**
     *
     * @var date
     */
    public $TSAdded_AS;

    /**
     *
     * @var date
     */
    public $TSModified_AS;

}
