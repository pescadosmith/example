<?php

class PlaylistAlbumsSongs extends MY_Model{

    const DB_TABLE = 'PlaylistAlbumsSongs';
    const DB_TABLE_PK = 'PlaylistAlbumsSongs_ID';

    /**
     *
     * @var int
     */
    public $PlaylistAlbumsSongs_ID;

    /**
     *
     * @var int
     */
    public $PlaylistID;

    /**
     *
     * @var int
     */
    public $AlbumSongID;

    /**
     *
     * @var int
     */
    public $Sequence;

    /**
     *
     * @var date
     */
    public $TSAdded_PAS;

    /**
     *
     * @var date
     */
    public $TSModified_PAS;

    /**
     *
     * @var int
     */
    public $Flags_PAS;

}
