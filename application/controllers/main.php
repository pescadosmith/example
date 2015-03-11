<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author Andrew Smith
 * @version 1.0.0
 */
class Main extends CI_Controller {

    public $userID = '';
    public $userName = '';
    public $userDisplayName = '';
    public $userPic = 'guest.jpg';
    public $memArray = array();
    public $setMemArray = array();
    public $dataArray = array('screenName' => 'Grooveshark - Created by Andrew Smith', 'title' => 'Grooveshark - Home', 'description' => 'Grooveshark is free music, online radio, and so much more.  Enjoy unlimited free music streaming with a worldwide community of artists and music lovers.', 'keywords' => 'radio, free music, free songs, music, musica, free mp3, online radio, music songs, internet radio, music videos', 'mainImage' => 'pickles.jpg', 'showSignIn' => 'yes');

    public function __construct() {

        parent::__construct();
    }

    public function index() {
//Initial Data for View
        $this->contents();
    }

    public function contents($view = '', $p = '') {
        $html = '';

        $mem = new Memcache;
        $mem->connect('andrew.grooveshark.com');
        mysql_connect("andrew.grooveshark.com", "root", "root") or die(mysql_error());
        mysql_select_db("grooveshark") or die(mysql_error());
//Array of fields to Cache
        $this->setMemArray = array('tmp_userName');

        if ($view == 'logout') {
            $mem->flush();
        }
        $this->signInForm();
        $this->set_memcache($mem);
        $this->get_memcache($mem);
        $this->verifyUser($mem);

//printr($this->dataArray);
        $this->load->view('templates/header', $this->dataArray);

        switch ($view) {
            case 'playlist':
                method_exists($this, 'playlist') ? $this->playlist($p) : '';
                break;
            case 'createPlaylist': //be sure to make it editable
                method_exists($this, 'createPlaylist') ? $this->createPlaylist() : '';
                break;
            case 'editPlaylist': //be sure to make it editable
                method_exists($this, 'createPlaylist') ? $this->createPlaylist($p) : '';
                break;
            case 'otherPlaylist':
                method_exists($this, 'playlist') ? $this->playlist() : '';
                break;
            case 'subscriptions':
                $html .= method_exists($this, 'subscription') ? $this->playlist($p) : '';
                break;
            default:
                if ($view == '' && !empty($this->userID)) {
                    $this->dataArray['mainImage'] = 'Welcome.png';
                }
                $this->load->view('main_view', $this->dataArray);
                break;
        }

        $this->load->view('templates/footer', $this->dataArray);
    }

    public function set_memcache($mem) {
        foreach ($this->setMemArray as $field) {
            if (!$mem->get($field)) {
                $mem->set($field, $this->input->post($field), 0, 1800);
            }
        }
    }

    public function get_memcache($mem) {
        foreach ($this->setMemArray as $field) {
            if ($mem->get($field)) {
                $this->memArray[$field] = $mem->get($field);
            }
        }
//printr($this->memArray, 'memcache');
    }

    public function signInForm() {
        $html = '';
        $formAttr = array('class' => '', 'id' => 'getUserForm');
        $hidden = array();
        $html .= form_open('main', $formAttr, $hidden);
        $data = array(
            'name' => 'tmp_userName',
            'id' => 'tmp_userName',
            'value' => '',
            'maxlength' => '50',
            'size' => '50',
            'style' => '',
            'tabindex' => '1',
        );
        $labelAttr = array('style' => 'display:inline');
        $html .= form_label('User:', 'tmp_userName', $labelAttr);
        $html .= form_input($data);
        $html .= form_submit('login', 'Login');
        $html .= form_close();
        $this->dataArray['signIn'] = $html;
    }

    public function verifyUser($mem) {
        $tmp_userName = isset($this->memArray['tmp_userName'][0]) ? implode($this->memArray['tmp_userName']) : '';
        $users = new Users();
        $usersArray = $users->get();

        foreach ($usersArray as $row => $user) {
            if ($user->Username == $tmp_userName) {
                $this->userName = isset($user->Username) ? $user->Username : '';
                $this->userDisplayName = isset($user->DisplayName) ? $user->DisplayName : '';
                $this->userID = isset($user->UserID) ? $user->UserID : '';
                $this->userPic = isset($user->Image_User) ? $user->Image_User : '';
            }
        }
//printr('', "$this->userName == $tuName");
        if (!empty($this->userName) || !empty($tmp_userName)) {
            unset($this->dataArray['title']);
            unset($this->dataArray['pic']);
            unset($this->dataArray['mainImage']);
            unset($this->dataArray['showSignIn']);
            if ($this->userName == $tmp_userName) {
                $this->dataArray['title'] = "Grooveshark - Hello $this->userDisplayName";
                $this->dataArray['pic'] = $this->userPic;
                $this->dataArray['showSignIn'] = 'no';
            } else {
                $this->dataArray['title'] = "Grooveshark - Invalid Login";
                $this->dataArray['pic'] = 'guest.jpg';
                $this->dataArray['showSignIn'] = 'yes';
                $this->dataArray['mainImage'] = 'unknown.jpg';
                $mem->flush();
            }
        } else {
            $mem->flush();
        }
    }

    public function playlist($pageType = '') {
        $datetime = date('Y-m-d H:i:s', time());
        $plugin = is_array($this->input->post('plugin')) ? TRUE : FALSE;
        $pluginPID = $this->input->post('pluginPlaylistID');
        $unplug = is_array($this->input->post('unplug')) ? TRUE : FALSE;
        $unplugPID = $this->input->post('unplugPlaylistID');

        if (!empty($this->memArray['tmp_userName'])) {
            $href_start = "default";
            $href_end = "</a>";
            if ($pageType != '') {
                $where = array('PlaylistID' => $pageType);
                $pageName = "Edit Songs on Playlist </br><i style='font-size:.45em'>(note:click playlist icon to edit name and description)</i>";
                $href_start = "<a title='Click to Edit Playlist' href='" . base_url("index.php/main/contents/editPlaylist/$pageType") . "'>";
            } else {
                $pageName = "My Playlist View";
                $where = array();
            }

            switch (true) {
                case ($pageType == 'other'):
                    $where = array('Subscriptions.UserID !=' => $this->userID); //, 'Subscriptions.PlaylistID' => $alreadyPlugged);
                    $pageName = "Other Playlist";
                    $href_start = "<a title='Click to Subscribe to this Playlist' href='" . base_url("index.php/main/contents/playlist/subs") . "'>";
                    $href_end = "";
                    break;
                case ($pageType == 'subs'):
                    $where = array();
                    $pageName = "Subscriptions";
                    $href_start = "<a title='Click to Edit Subscriptions' href='" . base_url("index.php/main/contents/playlist/subs") . "'>";
                    $href_end = "";
                    break;
            }

            if ($pageType == 'subs' || $pageType == 'other') {
                $mypl = $this->finsup->get_subscription($where);
            } else {
                $mypl = $this->finsup->get_playlist($where);
            }

            if (!empty($mypl)) {
                foreach ($mypl as $pid => $obj) {
                    $this->addSongsToPlaylist($pid);
                }
            }

            $playlistArray = array();
            $availableSongs = array();
            if (!empty($mypl)) {
                foreach ($mypl as $pid => $obj) {
                    //printr($mypl);
                    $playlistArray[$pid]['Followers'] = $this->followers($pid);
                    $playlistArray[$pid]['SongCount'] = $this->songCount($pid);
                    //SongList
                    $pasWArray = array('Playlist.PlaylistID' => $pid, 'Flags_Playlist' => 1);
                    $playlistArray[$pid]['PAS'] = $this->finsup->get_PlaylistAlbumsSongs($pasWArray);
                }
                //AVAILABLE SONGS FOR PLAYLISTS
                $availableSongs = $this->getSongs($pageType, $pid);

                //PLUG SUBSCRIPTION
                if ($pageType == 'other' && !empty($pluginPID)) {
                    $this->db->where(array('UserID' => $this->userID, 'PlaylistID' => $pluginPID, 'Flags_Sub' => 0));
                    $checkPlug = $this->subscriptions->get();
                    //'TSAdded_Sub' => date('Y-m-d H:i:s', time()),
                    if (!empty($checkPlug)) {
                        $this->db->where(array('PlaylistID' => $pluginPID, 'UserID' => $this->userID));
                        $this->db->update('Subscriptions', array('Flags_Sub' => 1));
                    } else {
                        $this->db->insert('Subscriptions', array('PlaylistID' => $pluginPID, 'UserID' => $this->userID, 'Flags_Sub' => 1));
                    }
                }
                if ($plugin) {
                    redirect(base_url("index.php/main/contents/playlist/other"));
                }
                //UNPLUG SUBSCRIPTION
                if ($pageType == 'subs') {
                    $this->db->where(array('UserID' => $this->userID, 'PlaylistID' => $unplugPID));
                    $this->db->update('Subscriptions', array('Flags_Sub' => 0));
                }
                if ($unplug) {
                    redirect(base_url("index.php/main/contents/playlist/subs"));
                }
                //REMOVE SONG
                $playlistalbumsongid = $this->input->post('playlistalbumsongid');
                if (!empty($playlistalbumsongid)) {
                    foreach ($playlistalbumsongid as $k => $where) {
                        $this->db->where(array('PlaylistAlbumsSongs_ID' => $where));
                        $this->db->delete('PlaylistAlbumsSongs');
                    }
                    redirect(base_url("index.php/main/contents/playlist/$pid"));
                }
            } //end of $mypl

            $this->load->view('playlist', array(
                'playlist' => $playlistArray,
                'pageName' => $pageName,
                'href_start' => $href_start,
                'href_end' => $href_end,
                'pageType' => $pageType,
                'availableSongs' => $availableSongs,
            ));
        }//end memcache check
    }

    public function addSongsToPlaylist($pid) {
        $newPlaylistArray = array();
        $nplArray = array();
        $newPlaylistArray = $this->input->post('new_sequence');

        if (!empty($newPlaylistArray)) {
            foreach ($newPlaylistArray as $k => $where) {
                $nplArray[] = $where;
            }
            //printr($nplArray);
            foreach ($nplArray as $key => $value) {
                $asID = str_replace("AlbumSongID=>", '', $value);
                $asIDpos = strpos($asID, '~');
                $asID = substr($asID, 0, $asIDpos);
                $seqID = substr(str_replace("AlbumSongID=>", '', str_replace(" ,Sequence=>", '', $value)), -1);
                //'AlbumSongID'=>$asID ,'Sequence'=>$seqID
                $this->db->from('PlaylistAlbumsSongs');
                $this->db->where(array('PlaylistID' => $pid, 'AlbumSongID' => $asID));
                $checkPAS = $this->db->get();
                $result = $checkPAS->result();
                $datetime = date('Y-m-d H:i:s', time());
                //printr($result);
                if (!empty($result[0])) {
                    //printr('update');
                    $this->db->where(array('PlaylistID' => $pid, 'AlbumSongID' => $asID));
                    $this->db->update('PlaylistAlbumsSongs', array('Flags_PAS' => 1, 'Sequence' => $seqID, 'TSAdded_PAS' => $datetime));
                } else {
                    //printr('inserting');
                    $this->db->insert('PlaylistAlbumsSongs', array('PlaylistID' => $pid, 'AlbumSongID' => $asID, 'Sequence' => $seqID, 'Flags_PAS' => 1, 'TSAdded_PAS' => $datetime));
                }
            }//end foreach
            redirect(base_url("index.php/main/contents/playlist/$pid"));
        }
    }

    public function createPlaylist($p = '') {
        $datetime = date('Y-m-d H:i:s', time());
        $save = is_array($this->input->post('save')) ? TRUE : FALSE;
        $del = is_array($this->input->post('delPlaylist')) ? TRUE : FALSE;
        $playEdit = array();
        if ($p != '') {
// Populate Form to Edit Playlist
            $this->db->where(array('PlaylistID' => $p));
            $playEdit = $this->playlist->get();
        }

        $name_playlist = is_array($this->input->post('Name_Playlist')) ? implode($this->input->post('Name_Playlist')) : '';
        $description_playlist = is_array($this->input->post('Description_Playlist')) ? implode($this->input->post('Description_Playlist')) : '';

        $check_file_upload = FALSE;
        if (isset($_FILES['playlist_cover']['error'][0]) && ($_FILES['playlist_cover']['error'][0] != 4)) {
            $check_file_upload = TRUE;
        }
        $config['upload_path'] = 'img/playlist';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '10000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);

        if (!empty($name_playlist) && $check_file_upload && $this->upload->do_upload('playlist_cover') || $p != '' && !empty($name_playlist)) {
            $upload_data = $this->upload->data();

            if ($p != '' && $save && empty($upload_data['file_name'])) {
                $playlist_cover = isset($playEdit[$p]->Image_Playlist) ? $playEdit[$p]->Image_Playlist : 'no_cover.jpg';
            } else {
                $playlist_cover = (isset($upload_data['file_name'])) ? $upload_data['file_name'] : 'no_cover.jpg';
            }

            $upsertArray = array(
                'UserID' => $this->userID,
                'Name_Playlist' => $name_playlist,
                'Description_Playlist' => $description_playlist,
                'Image_Playlist' => $playlist_cover,
                'TSAdded_Playlist' => date('Y-m-d H:i:s', time()),
            );
            $this->db->where(array('Name_Playlist' => $name_playlist));
            $check = $this->playlist->get();

            if ($p != '') {
                unset($upsertArray['UserID']);
                $this->db->where(array('PlaylistID' => $p));
                $this->db->update('Playlist', $upsertArray);
            } elseif (!empty($check)) {
                unset($upsertArray['UserID']);
                $this->db->where(array('Name_Playlist' => $name_playlist));
                $this->db->update('Playlist', $upsertArray);
            } else {
                $this->db->insert('Playlist', $upsertArray);
                $getNewPID = $this->playlist->get();
                foreach($getNewPID as $col){
                    $lastValue = $col->PlaylistID;
                }
                $this->db->insert('Subscriptions', array('PlaylistID' => $lastValue, 'UserID' => $this->userID));
                
            }
            if ($p != '' && $del) {
                $this->db->delete('PlaylistAlbumsSongs', array('PlaylistID' => $p));
                $this->db->delete('Subscriptions', array('PlaylistID' => $p));
                $this->db->delete('Playlist', array('PlaylistID' => $p));
            }

            if ($save || $del) {
                redirect(base_url("index.php/main/contents/playlist/"));
            }
        }

        $pageName = ($p != '') ? 'Edit Playlist' : "Create Playlist </br><i style='font-size:.45em;'>(Note: Image is required!)</i>";
        $this->load->view('playlist_form', array(
            'pageName' => $pageName,
            'uid' => $this->userID,
            'playEdit' => $playEdit,
            'pid' => $p,
        ));
    }

    public function getSongs($pageType, $pid) {
        $songsArray = array();
        if ($pageType != '') {
            $songsArray = $this->finsup->get_Songs($pid);
        }
        return $songsArray;
    }

    /**
     * Playlist #Followers
     * @param type $pid
     * @return type
     */
    public function followers($pid) {
        $followCount = 0;
        $this->db->where(array('PlaylistID' => $pid, 'Flags_Sub' => 1));
        $this->db->from('Subscriptions');
        $followCount = $this->db->count_all_results();
        $followers = ($followCount == 1) ? "follower" : "followers";
        return "$followCount $followers";
    }

    /**
     * Playlist #Songs
     * @param type $pid
     * @return type
     */
    public function songCount($pid) {
        $songCount = 0;
        $this->db->where(array('PlaylistID' => $pid, 'Flags_PAS' => 1));
        $this->db->from('PlaylistAlbumsSongs');
        $songCount = $this->db->count_all_results();
        $songs = ($songCount == 1) ? "song" : "songs";
        return "$songCount $songs";
    }

}

?>