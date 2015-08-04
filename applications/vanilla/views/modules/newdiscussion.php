<?php if (!defined('APPLICATION')) { exit(); 
}
echo '<div class="BoxButtons BoxNewDiscussion">';

$Css = 'Button Primary Action NewDiscussion';
$Css .= strpos($this->CssClass, 'Big') !== false ? ' BigButton' : '';

echo ButtonGroup($this->Buttons, $Css, $this->DefaultButton);
Gdn::Controller()->FireEvent('AfterNewDiscussionButton');

echo '</div>';