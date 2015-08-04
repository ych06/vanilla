<?php if (!defined('APPLICATION')) { exit(); 
} ?>
<div class="Profile">
    <?php
    require $this->FetchViewLocation('user');
    // include($this->FetchViewLocation('tabs'));
    echo Gdn_Theme::Module('ProfileFilterModule');
    require $this->FetchViewLocation($this->_TabView, $this->_TabController, $this->_TabApplication);
    ?>
</div>