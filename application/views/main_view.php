<?php if (!empty($mainImage)) { ?>
    <center>
        <a href='/'>
            <img src='<?php echo base_url("css/images/$mainImage") ?>' style='margin-top:30px;height:30em;border-radius:10px;'>
        </a>
    </center>
<?php } ?>
<?php
echo isset($html) ? $html : "";
?>
     