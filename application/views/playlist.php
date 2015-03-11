<?php //printr($myplaylist);                                                                                                                                                                                                                                                                                                     ?>
<div id='playlist' class="container-fluid">
    <div id='pageName' class="row-fluid"><?php echo isset($pageName) ? $pageName : ''; ?></div>
    <ul>
        <?php
        if (!empty($playlist)) {
            //printr($playlist);
            foreach ($playlist as $pid => $dataArray) {
                $img_playlist = !empty($dataArray['PAS'][0]->Image_Playlist) ? $dataArray['PAS'][0]->Image_Playlist : 'no_cover.jpg';
                $nm_playlist = !empty($dataArray['PAS'][0]->Name_Playlist) ? $dataArray['PAS'][0]->Name_Playlist . "</br>" : 'Unknown </br>';
                $desc_playlist = !empty($dataArray['PAS'][0]->Description_Playlist) ? "<i>{$dataArray['PAS'][0]->Description_Playlist}</i></br>" : 'Unknown </br>';
                $subscripton = !empty($dataArray['PAS'][0]->SubscriptionID) ? $dataArray['PAS'][0]->SubscriptionID : '';
                $followers = isset($dataArray['Followers']) ? $dataArray['Followers'] : 'No Followers';
                $songCount = isset($dataArray['SongCount']) ? $dataArray['SongCount'] : 'No Songs';
                $creator = isset($dataArray['Creator']) ? "Created by: <i>{$dataArray['Creator']}</i></br>" : '';
                ?>
                <li>
                    <div id='<?php echo "$pid"; ?>' class='row-fluid'>
                        <?php
                        if ($href_start == 'default') {
                            echo "<a title='Click to View Playlist' href='" . base_url("index.php/main/contents/playlist/$pid") . "'>";
                        } elseif ($pageType == 'other' || $pageType == 'subs') {
                            echo "";
                        } else {
                            echo $href_start;
                        }
                        ?>
                        <img id='img_playlist' src='<?php echo base_url("img/playlist/") . "/" . $img_playlist; ?>' />
                        <?php
                        echo $nm_playlist;
                        echo $desc_playlist;
                        echo "<i>$followers / $songCount</i></br>";
                        echo $creator;
                        if ($pageType == 'other') {
                            echo form_open(NULL, array('id' => 'form_pluginSubscription', 'class' => 'playlist_form'));
                            echo form_hidden('pluginPlaylistID', $pid);
                            echo form_submit('plugin', 'Plugin');
                            echo form_close();
                        }
                        if ($pageType == 'subs') {
                            echo form_open(NULL, array('id' => 'form_unplugSubscription', 'class' => 'playlist_form'));
                            echo form_hidden('unplugPlaylistID', $pid);
                            echo form_submit('unplug', 'Unplug');
                            echo form_close();
                        }
                        echo $href_end
                        ?>
                    </div><!--end div container -->
                </li>
                <?php
            } //end foreach
        } else {
            ?>
            <div><!--//WHEN NO RESULTS, SHOW THIS EMPTY IMAGE -->
                <img src="<?php echo base_url('css/images/Empty.png'); ?>" style="padding-left: 75px;"/>
            </div>
        <?php } ?>
    </ul>
</div>

<!-------------------------------------------------------------------------------------------------------------------------->
<?php
if ($pageType != 'other' && $pageType != 'subs' && $pageType != '') {
    echo form_open(NULL, array('id' => 'formAddToPlaylist', 'class' => 'playlist_form'));
    ?>
    <div id='availableSongs' class='container-fluid'>
        <div id='0' class='row-fluid selectableDiv'>
            <div class='selectableHeader'>
                Available Songs
            </div>
            <ul id='sortable1' class='connectedSortable'>
                <?php
                $removeSongsArray = array();
                foreach ($availableSongs as $sid => $sObj) {
                    //printr($sObj);
                    ?>
                    <li id="<?php echo $sObj->AlbumSongID; ?>" class='ui-state-default' style='margin-left:5px;'> 
                        <div style='float:left;'>
                        </div>
                        <div style='float:left;margin: 5px 15px 0px 5px;'>
                            <img src='<?php echo base_url("img/albums/$sObj->Image_Albums") ?>' style='width:50px;height:50px'/>
                        </div>
                        <div>
                            <?php echo "Artist: $sObj->Name_Artist"; ?>
                        </div>
                        <div>
                            <?php echo "Song: $sObj->Name_Song"; ?>
                        </div>
                        <div>
                            <?php echo "Album: $sObj->Name_Albums"; ?>
                        </div>
                    </li>
                    <?php
                }//end foreach  
                ?>
            </ul><!-- end first ul -->
        </div><!-- end div -->

        <?php ////////// get songs in the current playlist  ////////////     ?>

        <?php
        unset($dataArray['Followers']);
        unset($dataArray['SongCount']);
        foreach ($dataArray as $objArray => $obj) {
            ?>
            <div id='1' class='row-fluid selectableDiv'>
                <div class='selectableHeader' style="float:left;">
                    Songs in Playlist
                </div>
                <div class='btn_addsongstoplay' style="float:right;margin: 7px 0px 0px 0px;width: 44%;">
                    <?php
                    if (empty($obj[0]->PlaylistID)) {
                        echo form_button(array('id' => 'serialize', 'name' => 'serialize'), "Add To Playlist");
                    } else {
                        echo form_button(array('id' => 'serialize', 'name' => 'serialize'), "Update Playlist");
                        ?>
                    </div>
                    <ul id='sortable2' class='connectedSortable'>
                        <?php
                        echo form_input(array('id' => "songPID", 'name' => "pid", 'type' => 'hidden', 'value' => $pid));
                        foreach ($obj as $k => $v) {
                            foreach ($v as $a => $b) {
                                if ($a == 'Sequence') {
                                    $seqKsortArray[$b] = $v;
                                }
                            }
                        }
                        ksort($seqKsortArray);

                        foreach ($seqKsortArray as $seq => $sKObj) {
                            //printr($sKObj);
                            $pasID = !empty($sKObj->PlaylistAlbumsSongs_ID) ? $sKObj->PlaylistAlbumsSongs_ID : '';
                            $albumssongid = !empty($sKObj->AlbumSongID) ? $sKObj->AlbumSongID : '';
                            $img_album = !empty($sKObj->Image_Albums) ? $sKObj->Image_Albums : 'no_cover.jpg';
                            $nm_song = !empty($sKObj->Name_Song) ? $sKObj->Name_Song : 'Unknown';
                            $nm_artist = !empty($sKObj->Name_Artist) ? $sKObj->Name_Artist : 'Unknown';
                            $nm_album = !empty($sKObj->Name_Albums) ? $sKObj->Name_Albums : 'Unknown';
                            $sequence = !empty($sKObj->Sequence) ? $sKObj->Sequence : 0;
                            $flags = !empty($sKObj->Flags_PAS) ? $sKObj->Flags_PAS : 0;
                            if (!empty($sKObj->Name_Song) && $flags != 0) {
                                ?>
                                <li id='<?php echo $albumssongid; ?>' class='ui-state-default' style='margin-left:5px;'>
                                    <div style='float:left;'>
                                    </div>

                                    <div id='removePAS' style='float: right;'>
                                        <?php echo form_open(NULL, array('id' => 'form_rmSong', 'class' => 'playlist_form')); ?>
                                        <?php // if ($pageType != '') { ?>
                                        <?php
                                        $rmdata = array(
                                            'name' => 'rmPAS_btn',
                                            'id' => "rmPAS_btn$pasID",
                                            'class' => 'rmPAS_btn',
                                            'value' => $pasID,
                                            'type' => 'button',
                                            'content' => 'Remove'
                                        );
                                        echo form_button($rmdata);
                                        ?>
                                        <?php // }   ?>
                                        <?php echo form_close(); ?>
                                    </div>

                                    <div style='float:left;margin: 5px 15px 0px 5px;'>
                                        <img src = '<?php echo base_url("img/albums/$img_album") ?>' style = 'width:50px;height:50px'/>
                                    </div>
                                    <div>
                                        <?php echo "Artist: $nm_artist";
                                        ?>
                                    </div>
                                    <div>
                                        <?php echo "Song: $nm_song"; ?> 
                                    </div>
                                    <div>
                                        <?php echo "Album: $nm_album"; ?>
                                    </div>
                                </li>
                                <?php
                            } //if
                        } //foreach
                    } //array not empty
                    ?>
                </ul><!-- end second ul -->
            </div><!-- end div -->
        </div><!-- end div container -->
        <?php
    }//end foreach dataArray

    echo form_close();
    ?>
<?php }//end pageType 
?>