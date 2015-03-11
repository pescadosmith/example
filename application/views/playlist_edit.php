<div id='playlist' style="margin-top: 20px;color: white;" class="container-fluid">
    <?php
    //printr($playlist);
    foreach ($playlist as $pid => $obj) {
        $playlistId = isset($pid) ? $pid : '';
        ?>
        <div style="font-size: 2em; font-weight:bold;margin-bottom:20px;">
            <?php echo isset($pageName) ? "$pageName - $obj->Name" : ''; ?>
        </div>
        <div id='<?php echo "$playlistId_{$obj->Name}"; ?>' class="row-fluid" style="margin:10px 0px 20px 20px; ">

            <?php echo $this->upload->display_errors('<div class="alert alert-error">', '</div>'); ?>

            <?php echo form_open_multipart(NULL, $attributes = array('id' => 'form_createPlaylist', 'class' => 'playlist_form')); ?>
            <div id='playlist_Name'>
                <?php $name = !empty($obj->Name) ? $obj->Name : ''; ?>
                <?php echo form_input($data = array('id' => 'playlist_Name', 'name' => 'playlist_Name', 'style' => 'display:block;'), $value = "$name", $extra = "", $label = 'Playlist Name:'); ?>
            </div>

            <div id='playlist_Description'>
                <?php $desc = !empty($obj->Description) ? $obj->Description : ''; ?>
                <?php echo form_label('Playlist Description:', $id = 'playlist_Description') ?>
                <?php echo form_textarea($data = array('id' => 'playlist_Description', 'name' => 'playlist_Description', 'rows' => '3'), $value = "$desc", $extra = "") . "</br>"; ?>
            </div>

            <div id = 'playlist_userName'>
                <?php echo form_hidden($name = 'userName', $value = $userID);
                ?>
            </div>

            <div id='playlist_cover'>
                <img src='<?php echo base_url("img/playlist/{$obj->ImageFilename}"); ?>' style='height:75px;width:75px;border-radius:10px;float:left;margin:0px 15px 10px 0px; '/>
                <?php echo form_upload('playlist_cover'); ?>
            </div>

            <div style="margin: 10px;" id='playlist_save'>
                <?php echo form_submit('save', 'Save'); ?>
            </div>

            <?php // echo form_button('createPlaylistbtn', 'Create Playlist', "id='createPlaylistbtn' class='createPlaylist'") . "  ";    ?>
            <?php echo form_close(); ?>
        </div>
    <?php } ?>

    <ul>
        <?php
        //printr($aslist, 'songs & albums');
        $j = 0;
        foreach ($aslist as $sRow => $songData) {

            foreach ($songData as $songNumber => $songObj) {
                foreach ($songObj as $songID => $songMeta) {
                    ?>
                    <li id='<?php echo "$j"; ?>'>
                        <strong>
                            <?php
                            printr($songMeta);
                            echo "Song: $songMeta->SongName";
                            ?>
                        </strong>
                    </li>
                    <?php
                    $j++;
                } //end songObj
            } //end songData
        }//end songlist
        ?>
    </ul>
</div>






