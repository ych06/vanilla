<?php if (!defined('APPLICATION')) { exit(); 
}
// An individual discussion record for all panel modules to use when rendering a discussion list.
if (!isset($this->Prefix)) {
    $this->Prefix = 'Bookmark';
}

if (!function_exists('WriteModuleDiscussion')) {
    $DiscussionsModule = new DiscussionsModule();
    include_once $DiscussionsModule->FetchViewLocation('helper_functions');
    include_once Gdn::Controller()->FetchViewLocation('helper_functions', 'Discussions', 'Vanilla');
}

WriteModuleDiscussion($Discussion, $this->Prefix);