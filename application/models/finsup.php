<?php

/**
 * @author smith
 */
class Finsup extends My_Model {

    public $table = '';
    public $content = '';
    public $date = '';

    function __construct() {
        parent::__construct();
    }

    function get_playlist($where = array(), $limit = FALSE, $offset = FALSE) {
        if (!empty($where)) {
            $this->db->where($where);
        } else {
            $this->db->where(array('UserID' => $this->userID));
        }

        return $this->playlist->get();
    }

    function get_PlaylistAlbumsSongs($where = array(), $limit = FALSE, $offset = FALSE) {
        $this->db->from('Playlist');
        $this->db->join('PlaylistAlbumsSongs', "PlaylistAlbumsSongs.PlaylistID = Playlist.PlaylistID", 'left');
        $this->db->join('AlbumsSongs', "AlbumsSongs.AlbumSongID = PlaylistAlbumsSongs.AlbumSongID", 'left');
        $this->db->join('Albums', "Albums.AlbumID = AlbumsSongs.AlbumID", 'left');
        $this->db->join('Songs', "Songs.SongID = AlbumsSongs.SongID", 'left');
        $this->db->join('Artists',"Artists.ArtistID = Albums.ArtistID",'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function get_subscription($where = array()) {
        if (!empty($where)) {
            $sql = "select * from Subscriptions where Subscriptions.UserID != $this->userID and Subscriptions.PlaylistID not in (select PlaylistID from Subscriptions where UserID = $this->userID  and Flags_Sub = 1)";
        } else {
            $sql = "select * from Subscriptions where Subscriptions.UserID = $this->userID and Flags_Sub = 1";
        }
        $query = $this->db->query($sql);

        foreach ($query->result() as $row) {
            $model = new Subscriptions();
            $model->populate($row);

            $ret_val[$row->PlaylistID] = $model;
        }
        if (!empty($ret_val)) {
            return $ret_val;
        }
    }

    function get_Songs($pid, $limit = FALSE, $offset = FALSE) {
        $limit = (!$limit) ? '' : ' LIMIT ' . $limit;
        if ($offset > 0) {
            $limit .= " OFFSET " . $offset;
        }
        $sql = "select
                s.SongID,
                a.AlbumSongID,
                case when p.PlaylistID = $pid then p.PlaylistID
                else NULL end as PlaylistID,
                s.Name_Song, 
                a.SongFilename, 
                case when p.PlaylistID = $pid then p.Sequence
                else NULL end as Sequence,
                case when p.PlaylistID = $pid then p.Flags_PAS
                else NULL end as Flags_PAS,
                m.Name_Albums,
                m.Year_Albums,
                m.Image_Albums,
                r.Name_Artist
                from Songs s
                left join AlbumsSongs a ON s.SongID = a.AlbumSongID and a.AlbumSongID is not null
                left join PlaylistAlbumsSongs p ON p.AlbumSongID = a.AlbumSongID and (p.PlaylistID != $pid or p.PlaylistID is null)
                left join Albums m on a.AlbumID = m.AlbumID
                left join Artists r on r.ArtistID = m.ArtistID
                where s.Flags_Song = 1 and a.Flags_AS = 1 $limit ";
        $query = $this->db->query($sql);

        foreach ($query->result() as $row) {
            $model = new Songs();
            $model->populate($row);

            $ret_val[$row->SongID] = $model;
        }
        if (!empty($ret_val)) {
            return $ret_val;
        }
    }

}
