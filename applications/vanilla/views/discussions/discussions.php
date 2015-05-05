<?php if (!defined('APPLICATION')) exit();
if (!function_exists('WriteDiscussion'))
   include($this->FetchViewLocation('helper_functions', 'discussions', 'vanilla'));

$session = Gdn::Session();

$mediaList = new MediaListModule();

   $mediaList->setView('medialist-table');
   $mediaList->addTableColumn(DiscussionHeading(), 'DiscussionName')
      ->addTableColumn(T('Started By'), 'BlockColumn BlockColumn-User FirstUser')
      ->addTableColumn(T('Replies'), 'BigCount CountReplies')
      ->addTableColumn(T('Views'), 'BigCount CountViews')
      ->addTableColumn(T('Most Recent Comment', 'Most Recent'), 'BlockColumn BlockColumn-User LastUser');


if (property_exists($this, 'AnnounceData') && is_object($this->AnnounceData)) {
   foreach ($this->AnnounceData->Result() as $discussion) {
      $mediaList->addMediaItem(buildDiscussionMediaItem($discussion, $this, $session));
   }
}

foreach ($this->DiscussionData->Result() as $discussion) {
   $mediaList->addMediaItem(buildDiscussionMediaItem($discussion, $this, $session));
}

echo $mediaList->toString();
