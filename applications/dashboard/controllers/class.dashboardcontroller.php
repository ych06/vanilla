<?php if (!defined('APPLICATION')) exit();

/**
 * Master application controller for Dashboard, extended by most others.
 *
 * @copyright 2003 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @package Garden
 * @since 2.0
 */

class DashboardController extends Gdn_Controller {
   /**
    * Set PageName.
    *
    * @since 2.0.0
    * @access public
    */
   public function __construct() {
      parent::__construct();
      $this->PageName = 'dashboard';
   }

   /**
    * Include JS, CSS, and modules used by all methods.
    *
    * Always called by dispatcher before controller's requested method.
    *
    * @since 2.0.0
    * @access public
    */
   public function Initialize() {
      $this->Head = new HeadModule($this);
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
      $this->AddJsFile('jquery.form.js');
      $this->AddJsFile('jquery.popup.js');
      $this->AddJsFile('jquery.gardenhandleajaxform.js');
      $this->AddJsFile('customdashboard.js');
      $this->AddJsFile('magnific-popup.min.js');
      $this->AddJsFile('jquery.autosize.min.js');
      $this->AddJsFile('global.js');

      if (in_array($this->ControllerName, array('profilecontroller', 'activitycontroller'))) {
         $this->AddCssFile('style.css');
         $this->AddCssFile('vanillicon.css', 'static');
      } else {
         if (!C('Garden.Cdns.Disable', FALSE)) {
            $this->AddCssFile('http://fonts.googleapis.com/css?family=Rokkitt');
        }
         $this->AddCssFile('admin.css');
         $this->AddCssFile('styles.css');
         $this->AddCssFile('magnific-popup.css');
      }

      $this->MasterView = 'admin';
      parent::Initialize();
   }

   /**
    * Build and add the Dashboard's side navigation menu.
    *
    * @since 2.0.0
    * @access public
    *
    * @param string $CurrentUrl Used to highlight correct route in menu.
    */
   public function AddSideMenu($CurrentUrl = FALSE) {
		if(!$CurrentUrl)
			$CurrentUrl = strtolower($this->SelfUrl);

      // Only add to the assets if this is not a view-only request
      if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
         // Configure SideMenu module
         $Menu = new NavModule($this, array('class'=>'nav nav-pills nav-stacked'));

         $Menu->addGroup('dashboard', array('text'=>T('Dashboard'), 'class' => 'Dashboard'));
         $Menu->addLink('dashboard.settings', array('text'=>T('Dashboard'), 'url'=>'/dashboard/settings', 'permission'=>'Garden.Moderation.Manage'));
         $Menu->addLink('dashboard.getting-started', array('text'=>T('Getting Started'), 'url'=>'/dashboard/settings/gettingstarted', 'permission'=>'Garden.Settings.Manage'));
         $Menu->addLink('dashboard.help-and-tutorials', array('text'=>T('Help &amp; Tutorials'), 'url'=>'/dashboard/settings/tutorials', 'permission'=>'Garden.Settings.Manage'));

         $Menu->AddGroup('appearance', array('text'=>T('Appearance'), 'class' => 'Appearance'));
		 $Menu->AddLink('appearance.banner', array('text'=>T('Banner'), 'url'=>'/dashboard/settings/banner', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('appearance.homepage', array('text'=>T('Homepage'), 'url'=>'/dashboard/settings/homepage', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('appearance.themes', array('text'=>T('Themes'), 'url'=>'/dashboard/settings/themes', 'permission'=>'Garden.Settings.Manage'));
         if ($ThemeOptionsName = C('Garden.ThemeOptions.Name'))
            $Menu->AddLink('appearance.theme-options', array('text'=>T('Theme Options'), 'url'=>'/dashboard/settings/themeoptions', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('appearance.mobile-themes', array('text'=>T('Mobile Themes'), 'url'=>'/dashboard/settings/mobilethemes', 'permission'=>'Garden.Settings.Manage'));
         if ($MobileThemeOptionsName = C('Garden.MobileThemeOptions.Name'))
            $Menu->AddLink('appearance.mobile-theme-options', array('text'=>T('Mobile Theme Options'), 'url'=>'/dashboard/settings/mobilethemeoptions', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('appearance.messages', array('text'=>T('Messages'), 'url'=>'/dashboard/message', 'permission'=>'Garden.Settings.Manage'));

         $Menu->AddGroup('users', array('text'=>T('Users'), 'class' => 'Users'));
         $Menu->AddLink('users.settings', array('text'=>T('Users'), 'url'=>'/dashboard/user', 'permission'=>array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete')));
		 $Menu->AddLink('users.roles-and-permissions', array('text'=>T('Roles & Permissions'), 'url'=>'/dashboard/role', 'permission'=>'Garden.Settings.Manage'));

         $Menu->AddLink('users.registration', array('text'=>T('Registration'), 'url'=>'/dashboard/settings/registration', 'permission'=>'Garden.Settings.Manage'));
		 $Menu->AddLink('users.authentication', array('text'=>T('Authentication'), 'url'=>'/dashboard/authentication', 'permission'=>'Garden.Settings.Manage'));

         if (C('Garden.Registration.Method') == 'Approval')
            $Menu->AddLink('users.applicants', array('text'=>T('Applicants').' <span class="Popin" rel="/dashboard/user/applicantcount"></span>', 'url'=>'/dashboard/user/applicants', 'permission'=>'Garden.Users.Approve'));

         $Menu->AddGroup('moderation', array('text'=>T('Moderation'), 'class' => 'Moderation'));

         if (Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.Spam.Manage'), FALSE))
            $Menu->AddLink('moderation.spam', array('text'=>T('Spam Queue'), 'url'=>'/dashboard/log/spam'));
         if (Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.ModerationQueue.Manage'), FALSE))
            $Menu->AddLink('moderation.queue', array('text'=>T('Moderation Queue').' <span class="Popin" rel="/dashboard/log/count/moderate"></span>', 'url'=>'/dashboard/log/moderation'));
         $Menu->AddLink('moderation.change-log', array('text'=>T('Change Log'), 'url'=>'/dashboard/log/edits', 'permission'=>'Garden.Moderation.Manage'));
         $Menu->AddLink('moderation.banning', array('text'=>T('Banning'), 'url'=>'/dashboard/settings/bans', 'permission'=>'Garden.Moderation.Manage'));

         $Menu->AddGroup('forum', array('text'=>T('Forum Settings'), 'class' => 'Forum'));
         $Menu->AddLink('forum.social', array('text'=>T('Social'), 'url'=>'/dashboard/social', 'permission'=>'Garden.Settings.Manage'));

         $Menu->AddGroup('reputation', array('text'=>T('Reputation'), 'class' => 'Reputation'));

         $Menu->AddGroup('add-ons', array('text'=>T('Addons'), 'class' => 'Addons'));
         $Menu->AddLink('add-ons.plugins', array('text'=>T('Plugins'), 'url'=>'/dashboard/settings/plugins', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('add-ons.applications', array('text'=>T('Applications'), 'url'=>'/dashboard/settings/applications', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('add-ons.locales', array('text'=>T('Locales'), 'url'=>'/dashboard/settings/locales', 'permission'=>'Garden.Settings.Manage'));

         $Menu->AddGroup('site-settings', array('text'=>T('Settings'), 'class' => 'SiteSettings'));
         $Menu->AddLink('site-settings.outgoing-email', array('text'=>T('Outgoing Email'), 'url'=>'/dashboard/settings/email', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('site-settings.routes', array('text'=>T('Routes'), 'url'=>'/dashboard/routes', 'permission'=>'Garden.Settings.Manage'));
         $Menu->AddLink('site-settings.statistics', array('text'=>T('Statistics'), 'url'=>'/dashboard/statistics', 'permission'=>'Garden.Settings.Manage'));

		 $Menu->AddGroup('import', array('text'=>T('Import'), 'class' => 'Import'));
		 $Menu->AddLink('import.settings', array('text'=>T('Import'), 'url'=>'/dashboard/import', 'permission'=>'Garden.Settings.Manage'));

//         $SideMenu->EventName = 'GetAppSettingsMenuItems';
//         $SideMenu->HtmlId = '';
//         $SideMenu->HighlightRoute($CurrentUrl);
//			$SideMenu->Sort = C('Garden.DashboardMenu.Sort');

         // Hook for adding to menu
//         $this->EventArguments['SideMenu'] = &$SideMenu;
//         $this->FireEvent('GetAppSettingsMenuItems');

         // Add the module
         $this->AddModule($Menu, 'Panel');
      }
   }
}
