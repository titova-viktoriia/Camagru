<div class="mask">
    <?php for ($i = 0; $i <= 21; $i++) {?>
        <label for="mask_id_<?php echo $i;?>">
            <input type="checkbox" class="choose_mask" name="filter" value="/public/masks/<?php echo $i;?>.png" id="mask_id_<?php echo $i;?>" onchange="addMask('mask_id_<?php echo $i;?>')">
            <img class="img" src="/public/masks/<?php echo $i;?>.png" height="70%" alt=""/>
        </label>
    <?php } ?>
</div>