<?php if (!defined('APPLICATION')) { exit(); 
} ?>
<div id="SignIn" class="AjaxForm">
    <?php require $this->FetchViewLocation('SignIn'); ?>
</div>
<div id="Password" class="AjaxForm">
    <?php require $this->FetchViewLocation('PasswordRequest'); ?>
</div>
<div id="Register" class="AjaxForm">
    <?php require $this->FetchViewLocation($this->_RegistrationView()); ?>
</div>