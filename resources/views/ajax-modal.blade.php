<iframe src="/storage/models/{{$iframe}}" frameborder="0" width="100%" height="640px" id="threed-model"></iframe>
<?php if ($text) { ?>
    <h3><?= $text ?></h3>
<?php } ?>
<?php if ($link) { ?>
    <p><a href="<?= $link ?>" target="_blank"><?= $link ?></a></p>
<?php } ?>

<style>
    body {
        color: #fff;
    }
</style>
