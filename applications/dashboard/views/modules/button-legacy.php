<?php
$sender = $this->Data('sender');

?>
<a class="Button <?php echo $sender->buttonCssClass; ?>" href="<?php echo $sender->buttonUrl; ?>" role="button"><?php echo $sender->buttonText; ?></a>
