<?php
$sender = $this->Data('sender');
//decho($sender);
$controller = new Gdn_Controller();
?>

<tr id="<?php echo $sender->id; ?>" class="<?php echo $sender->cssClass; ?>">
  <?php foreach ($sender->rows as $row) { ?>
    <?php if (val('isMainCell', $row)) { ?>
        <td class="Name <?php echo val('mainCssClass', $row); ?>">
          <?php echo $sender->beforeContentOutput; ?>
        <div class="Wrap">
            <span class="Options">
              <?php
                  $controller->SetData('sender', $sender->options);
                  echo $controller->FetchView('dropdown-legacy', 'modules', 'dashboard');
              ?>
              <?php if ($sender->hasButtons) { ?>
                  <div class="Buttons <?php echo $sender->buttonsCssClass; ?>">
                    <?php foreach ($sender->buttons as $button) {
                        $controller->SetData('sender', $button);
                        echo $controller->FetchView('button-legacy', 'modules', 'dashboard'); } ?>
                  </div>
              <?php } ?>
            </span>
          <?php if ($sender->hasImage) { ?>
            <?php if ($sender->imageUrl) { ?>
            <a href="<?php echo $sender->imageUrl; ?>" class="Item-Icon PhotoWrap">
            <?php } ?>
              <img class="ProfilePhoto ProfilePhotoMedium <?php echo $sender->imageCssClass; ?>" src="<?php echo $sender->imageSource; ?>" alt="<?php echo $sender->imageAlt; ?>">
            <?php if ($sender->imageUrl) { ?>
            </a>
            <?php } ?>
          <?php } ?>
          <?php if ($sender->heading) { ?>
            <?php if ($sender->headingUrl) { ?>
            <a class="Title <?php echo $sender->headingCssClass; ?>" href="<?php echo $sender->headingUrl; ?>">
            <?php } ?>
            <?php echo $sender->heading; ?>
            <?php if ($sender->headingUrl) { ?>
            </a>
            <?php } ?>
          <?php } ?>
          <?php echo $sender->afterTitleOutput; ?>
            <div class="Description <?php echo $sender->textCssClass; ?>"><?php echo $sender->text; ?></div>
          <?php  // TODO: child categories, tags ?>
          <?php if ($sender->hasMeta) { ?>
              <ul class="<?php echo $sender->metaCssClass; ?>">
                <?php foreach($sender->meta as $metaItem) {
                    $controller->SetData('metaItem', $metaItem);
                    echo $controller->FetchView('metaitem', 'modules', 'dashboard'); } ?>
              </ul>
          <?php } ?>
            </div>
        </td>
    <?php } ?>
    <?php if (val('isCountCell', $row)) { ?>
        <td class="BigCount <?php echo val('countSectionCssClass', $row); ?>">
            <div class="Wrap">
                <span class="Number"><?php echo val('countNumber', $row); ?></span>
            </div>
        </td>
    <?php } ?>
      <?php if (val('isDefaultCell', $row)) { ?>
        <td class="BlockColumn <?php echo val('defaultCssClass', $row); ?>">
            <div class="Wrap">
                <span class="Text"><?php echo val('defaultText', $row); ?></span>
            </div>
        </td>
    <?php } ?>
      <?php if (val('isUserCell', $row)) { ?>
        <td class="BlockColumn BlockColumn-User <?php echo val('userFirstOrLast', $row); ?>User">
            <div class="Block Wrap">
              <?php if (val('userImageUrl', $row)) { ?>
                  <a class="PhotoWrap PhotoWrapSmall" href="<?php echo val('userUrl', $row); ?>">
                      <img class="ProfilePhoto ProfilePhotoSmall" src="<?php echo val('userImageUrl', $row); ?>">
                  </a>
              <?php } ?>
                <a class="UserLink BlockTitle" href="<?php echo val('userUrl', $row); ?>"><?php echo val('userName', $row); ?></a>
                <div class="Meta">
                    <a class="CommentDate MItem" href="<?php echo val('userPostUrl', $row); ?>"><?php echo val('userPostTime', $row); ?></a>
                </div>
            </div>
        </td>
    <?php } ?>
      <?php if (val('isLastPostCell', $row)) { ?>
        <td class="BlockColumn LatestPost">
          <?php if (val('lastPostTitle', $row)) { ?>
              <div class="Block Wrap">
                <?php if (val('lastPostImageUrl', $row)) { ?>
                    <a class="PhotoWrap PhotoWrapSmall" href="<?php echo val('lastPostUserUrl', $row); ?>">
                        <img class="ProfilePhoto ProfilePhotoSmall" src="<?php echo val('lastPostImageUrl', $row); ?>">
                    </a>
                <?php } ?>
                  <a class="BlockTitle LatestPostTitle" href="<?php echo val('lastPostUrl', $row); ?>"><?php echo val('lastPostTitle', $row); ?></a>
                  <div class="Meta">
                      <a class="UserLink MItem" href="<?php echo val('lastPostUserUrl', $row); ?>"><?php echo val('lastPostUserName', $row); ?></a>
                      <span class="Bullet">•</span>
                      <a class="CommentDate MItem" href="<?php echo val('lastPostUrl', $row); ?>"><?php echo val('lastPostTime', $row); ?></a>
                    <?php if (val('lastPostCategoryName', $row)) { ?>
                        <span>
                    <?php echo T('in'); ?> <a href="<?php echo val('lastPostCategoryUrl', $row); ?>"><?php echo val('lastPostCategoryName', $row); ?></a>
                  </span>
                    <?php } ?>
                  </div>
              </div>
          <?php } ?>
        </td>
    <?php } ?>
  <?php } ?>
</tr>
