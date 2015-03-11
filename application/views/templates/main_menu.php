
<div id='top'>
    <div id='gslogo'>
        <a href='http://andrew.grooveshark.com'>
            <img src="<?php echo base_url('css/images/grooveshark_logo_white.png'); ?>" alt="Grooveshark" /> 
        </a>
    </div><!-- /#gslogo -->
    <div id='gslogo_right'>
        <a href='http://andrew.grooveshark.com'>
            <img src="<?php echo base_url('css/images/grooveshark_logo_white.png'); ?>" alt="Grooveshark" />
        </a>
    </div><!-- /#gslogo_right -->
</div><!-- /#top -->
<br />
<div id="masternav" role="navigation">
    <?php
    if ($showSignIn == 'yes' || !isset($showSignIn)) {
        echo $signIn;
    } else {
        ?>
        <ul id = "topnav">
            <li><a href = "http://andrew.grooveshark.com/index.php/main/contents/playlist" >My Playlist</a>
            </li>
            <li><a href = "http://andrew.grooveshark.com/index.php/main/contents/createPlaylist" >Create Playlist</a>
            </li>
            <li><a href = "http://andrew.grooveshark.com/index.php/main/contents/playlist/other">Other's Playlist</a>
            </li>
            <li><a href = "http://andrew.grooveshark.com/index.php/main/contents/playlist/subs">Subscriptions</a>
            </li>
            <li><a href = "http://andrew.grooveshark.com/index.php/main/contents/logout">Logout</a>
            </li>
        </ul>
    <?php } ?>
</div><!--navigation -->