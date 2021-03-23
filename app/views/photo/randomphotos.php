<div class="randomPhotos">
    <?php foreach ($result['photos'] as $value) {?>
        <a href="\photo\<?php echo $value['id'];?>"><img class="randomPhoto" src="<?php echo htmlspecialchars($value['photo']);?>" alt="" ></a>
    <?php } ?>
</div>
