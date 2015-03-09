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
//      $this->AddJsFile('customdashboard.js');
        $this->AddJsFile('dropdown.js');
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
            $this->AddCssFile('magnific-popup.css');
            $this->AddCssFile('type.css');
            $this->AddCssFile('badges.css');
            $this->AddCssFile('buttons.css');
            $this->AddCssFile('dropdowns.css');
            $this->AddCssFile('navs.css');
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

            $dropdown = new DropdownModule($this, 'my-dropdown', 'Trigger Name', '', 'dropdown-menu-right');
            $dropdown->setTrigger('A New Name', 'button', 'btn-default', 'caret')
                ->addLink(array('text' => 'Link 1', 'url' => '#')) // Automatically creates key: item1
                ->addDivider() // Automatically creates key: item2
                ->addLink(array('text' => 'Link 2', 'url' => '#', 'key' => 'link2', 'class' => 'bg-danger')) // Creates item with key: link2
                ->addLinks(array(
                    array('text' => 'Link 3', 'url' => '#'), // Automatically creates key: item4
                    array('text' => 'Link 4', 'url' => '#')
                ))
                ->addGroup(array('key' => 'group1')) // Creates group with no header
                ->addGroup(array('text' => 'Group 3', 'key' => 'group3')) // Creates group with header: 'Group 2'
                ->addGroup(array('text' => 'Group 2', 'key' => 'group2')) // Creates group with header: 'Group 2'
                ->addLink(array('text' => 'Link 5', 'url' => '#', 'sort' => array('before', 'link2'), 'badge' => 4)) // Inserts before Link 2
                ->addLinks(array(
                    array('text' => 'Link 6', 'url' => '#'),
                    array('text' => 'Link 7', 'url' => '#')
                ))
                ->addLink(array('text' => 'Link 8', 'url' => '#', 'disabled' => true, 'key' => 'group2.link8', 'icon' => 'icon-flame')) // Adds to Group 2
                ->addLink(array('text' => 'Link 9', 'url' => '#', 'disabled' => true, 'key' => 'group1.link9')) // Adds to Group 1
                ->addLink(array('text' => 'Link 10', 'url' => '#', 'key' => 'group1.link10')); // Adds to Group 1

            $this->AddModule($dropdown, 'Panel');

            $menu = new NavModule($this, 'nav');
//
            $menu->addGroup(array('text'=>T('Dashboard'), 'class' => 'Dashboard', 'key' => 'dashboard'))
                ->addLink(array('text' => T('Dashboard'), 'url' => '/dashboard/settings', 'key' => 'dashboard.dashboard', 'check'=>Gdn::Session()->CheckPermission('Garden.Moderation.Manage')))
                ->addLink(array('key' => 'dashboard.getting-started', 'text'=>T('Getting Started'), 'url'=>'/dashboard/settings/gettingstarted', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->addLink(array('key' => 'dashboard.help-and-tutorials', 'text'=>T('Help &amp; Tutorials'), 'url'=>'/dashboard/settings/tutorials', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))

                ->AddGroup(array('key' => 'appearance', 'text'=>T('Appearance'), 'class' => 'Appearance'))
                ->AddLink(array('key' => 'appearance.banner', 'text'=>T('Banner'), 'url'=>'/dashboard/settings/banner', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'appearance.banner', 'text'=>T('Banner'), 'url'=>'/dashboard/settings/banner', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'appearance.homepage', 'text'=>T('Homepage'), 'url'=>'/dashboard/settings/homepage', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'appearance.themes', 'text'=>T('Themes'), 'url'=>'/dashboard/settings/themes', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'appearance.theme-options', 'text'=>T('Theme Options'), 'url'=>'/dashboard/settings/themeoptions', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage') && C('Garden.ThemeOptions.Name')))
                ->AddLink(array('key' => 'appearance.mobile-themes', 'text'=>T('Mobile Themes'), 'url'=>'/dashboard/settings/mobilethemes', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'appearance.mobile-theme-options', 'text'=>T('Mobile Theme Options'), 'url'=>'/dashboard/settings/mobilethemeoptions', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage') && C('Garden.MobileThemeOptions.Name')))
                ->AddLink(array('key' => 'appearance.messages', 'text'=>T('Messages'), 'url'=>'/dashboard/message', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))

                ->AddGroup(array('key' => 'users', 'text'=>T('Users'), 'class' => 'Users'))
                ->AddLink(array('key' => 'users.settings', 'text'=>T('Users'), 'url'=>'/dashboard/user', 'check'=>Gdn::Session()->CheckPermission(array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'))))
                ->AddLink(array('key' => 'users.roles-and-permissions', 'text'=>T('Roles & Permissions'), 'url'=>'/dashboard/role', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.registration', 'text'=>T('Registration'), 'url'=>'/dashboard/settings/registration', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.authentication', 'text'=>T('Authentication'), 'url'=>'/dashboard/authentication', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.roles-and-permissions', 'text'=>T('Roles & Permissions'), 'url'=>'/dashboard/role', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.registration', 'text'=>T('Registration'), 'url'=>'/dashboard/settings/registration', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.authentication', 'text'=>T('Authentication'), 'url'=>'/dashboard/authentication', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'users.applicants', 'text'=>T('Applicants'), 'url'=>'/dashboard/user/applicants', 'check'=>Gdn::Session()->CheckPermission('Garden.Users.Approve') && (C('Garden.Registration.Method') == 'Approval')))

                ->AddGroup(array('key' => 'moderation', 'text'=>T('Moderation'), 'class' => 'Moderation'))
                ->AddLink(array('key' => 'moderation.spam', 'text'=>T('Spam Queue'), 'url'=>'/dashboard/log/spam', 'check'=>Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.Spam.Manage'))))
                ->AddLink(array('key' => 'moderation.queue', 'text'=>T('Moderation Queue'), 'url'=>'/dashboard/log/moderation', 'check'=>Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.ModerationQueue.Manage'))))
                ->AddLink(array('key' => 'moderation.change-log', 'text'=>T('Change Log'), 'url'=>'/dashboard/log/edits', 'check'=>Gdn::Session()->CheckPermission('Garden.Moderation.Manage')))
                ->AddLink(array('key' => 'moderation.banning', 'text'=>T('Banning'), 'url'=>'/dashboard/settings/bans', 'check'=>Gdn::Session()->CheckPermission('Garden.Moderation.Manage')))

                ->AddGroup(array('key' => 'forum', 'text'=>T('Forum Settings'), 'class' => 'Forum'))
                ->AddLink(array('key' => 'forum.social', 'text'=>T('Social'), 'url'=>'/dashboard/social', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))

                ->AddGroup(array('key' => 'reputation', 'text'=>T('Reputation'), 'class' => 'Reputation'))

                ->AddGroup(array('key' => 'add-ons', 'text'=>T('Addons'), 'class' => 'Addons'))
                ->AddLink(array('key' => 'add-ons.plugins', 'text'=>T('Plugins'), 'url'=>'/dashboard/settings/plugins', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'add-ons.applications', 'text'=>T('Applications'), 'url'=>'/dashboard/settings/applications', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'add-ons.locales', 'text'=>T('Locales'), 'url'=>'/dashboard/settings/locales', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))

                ->AddGroup(array('key' => 'site-settings', 'text'=>T('Settings'), 'class' => 'SiteSettings'))
                ->AddLink(array('key' => 'site-settings.outgoing-email', 'text'=>T('Outgoing Email'), 'url'=>'/dashboard/settings/email', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'site-settings.routes', 'text'=>T('Routes'), 'url'=>'/dashboard/routes', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddLink(array('key' => 'site-settings.statistics', 'text'=>T('Statistics'), 'url'=>'/dashboard/statistics', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))

                ->AddGroup(array('key' => 'import', 'text'=>T('Import'), 'class' => 'Import'))
                ->AddLink(array('key' => 'import.settings', 'text'=>T('Import'), 'url'=>'/dashboard/import', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')))
                ->AddGroup(array('key' => 'import', 'text'=>T('Import'), 'class' => 'Import'))
                ->AddLink(array('key' => 'import.settings', 'text'=>T('Import'), 'url'=>'/dashboard/import', 'check'=>Gdn::Session()->CheckPermission('Garden.Settings.Manage')));

            $this->AddModule($menu, 'Panel');

        }
    }
}
