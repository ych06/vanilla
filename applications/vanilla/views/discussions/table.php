<?php if (!defined('APPLICATION')) exit();
if (!function_exists('WriteDiscussion'))
   include($this->FetchViewLocation('helper_functions', 'discussions', 'vanilla'));

$heading = DiscussionHeading();
$discussions = $this->DiscussionData->Result();
$announcements = array();
if (property_exists($this, 'AnnounceData') && is_object($this->AnnounceData)) {
   $announcements = $this->AnnounceData->Result();
}

writeDiscussionsTable($discussions, $heading, $announcements, $this);
