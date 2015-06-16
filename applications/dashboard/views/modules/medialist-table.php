<?php

$sender = $this->Data('sender');
$controller = new Gdn_Controller();

if ($sender->hasMediaListContainer) { ?>
    <div class="DataTableContainer">
<?php }
if ($sender->mediaListHeading) { ?><h2 class="Groups H <?php echo $sender->mediaListHeadingCssClass; ?>"><?php echo $sender->mediaListHeading; ?></h2><?php } ?>
    <div class="DataTableWrap">
        <table class="DataTable {{mediaListCssClass}}">
            <thead>
            <tr>
              <?php foreach($sender->mediaListTableColumns as $column) { ?>
                  <td class="<?php echo val('columnCssClass', $column); ?>"><div class="Wrap"><?php echo val('columnLabel', $column); ?></div></td>
              <?php } ?>
            </tr>
            </thead>
            <?php foreach($sender->mediaItems as $mediaItem) {
                $controller->SetData('sender', $mediaItem);
                echo $controller->FetchView('mediaitem-table-legacy', 'modules', 'dashboard');
            } ?>
        </table>
    </div>
 <?php if ($sender->hasEmptyMessage) { ?>
        <div class="ErrorMessage <?php echo $sender->mediaListEmptyMessageCssClass; ?>"><?php echo $sender->emptyMessage; ?></div>
 <?php } ?>
<?php if ($sender->hasMoreLink) { ?>
    <div class="MoreWrap">
        <a class="more <?php echo $sender->moreCssClass; ?>" href="<?php echo $sender->moreUrl; ?>"><?php if ($sender->moreIcon) { echo icon($sender->moreIcon); } echo $sender->moreLink; if($sender->moreBadge) { echo badge($sender->moreBadge); } ?></a>
    </div>
<?php } ?>
<?php if ($sender->hasMediaListContainer) { ?>
    </div>
<?php } ?>

