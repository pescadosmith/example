<div style="margin-top: 20px;color: white;"class="container-fluid">
    <div style="font-size: 2em; font-weight:bold;margin-bottom:20px; "><?php echo $pageName; ?></div>
    <?php echo $this->upload->display_errors('<div class="alert alert-error">', '</div>'); ?>
    <?php echo form_open_multipart(NULL, $attributes = array('id' => 'form_createPlaylist', 'class' => 'playlist_form')); ?>
    <div id='playlist_Name'>
        <?php $p = isset($pid) ? $pid : ''; ?>
        <?php echo $editName = isset($playEdit[$p]->Name_Playlist) ? $playEdit[$p]->Name_Playlist : ''; ?>
        <?php echo form_input($data = array('id' => 'Name_Playlist', 'name' => 'Name_Playlist', 'style' => 'display:block;'), $value = $editName, $extra = '', $label = 'Playlist Name:'); ?>
    </div>
    <div id='playlist_Description'>
        <?php echo $editDescription = isset($playEdit[$p]->Description_Playlist) ? $playEdit[$p]->Description_Playlist : ''; ?>
        <?php echo form_label('Playlist Description:', $id = 'Description_Playlist') ?>
        <?php echo form_textarea($data = array('id' => 'Description_Playlist', 'name' => 'Description_Playlist', 'rows' => '3'), $value = $editDescription, $extra = '') . "</br>"; ?>
    </div>
    <div id='playlist_userName'>
        <?php echo form_hidden($name = 'UserID', $value = $uid); ?>
    </div>
    <div id='playlist_cover'>
        <?php echo form_upload($data = array('id' => 'playlist_cover', 'name' => 'playlist_cover', 'style' => 'display:block;'), $value = '', $extra = '', $label = 'Playlist Image: '); ?>
    </div>
    <div style="margin: 10px;" id='playlist_save'>
        <?php echo form_submit('save', 'Save'); ?>
        <?php echo form_reset('reset', 'Reset'); ?>
        <?php echo form_submit('delPlaylist', 'Delete'); ?>
        <?php echo form_button('cancel', 'Cancel', "onclick=\"location.href = '" . base_url('index.php/main/contents/playlist') . "'\""); ?>

    </div>
    <?php echo form_close(); ?>
</div>
