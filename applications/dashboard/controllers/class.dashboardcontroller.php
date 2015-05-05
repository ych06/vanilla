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
            $this->AddCssFile('media.css');
            $this->AddCssFile('vanillicon.css');
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

            $dropdown = new DropdownModule('my-dropdown', 'Trigger Name', '', 'dropdown-menu-right');
            $dropdown->setTrigger('A New Name', 'button', 'btn-default', 'caret')
                ->addLink('Link 1', '#') // Automatically creates key: item1
//                ->addDivider() // Automatically creates key: item2
                ->addLink('Link 2', '#', true, 'link2', false, '', '', false, 'bg-danger') // Creates item with key: link2
                ->addLink('Link 3', '#') // Automatically creates key: item3
                ->addLink('Link 4', '#') // Automatically creates key: item4
                ->addGroup('', true, 'group1') // Creates group with no header
                ->addGroup('Group 3', true, 'group3') // Creates group with header: 'Group 3', empty so will not display
                ->addGroup('Group 2', true, 'group2') // Creates group with header: 'Group 2'
                ->addLink('Link 5', '#', true, false, array('before', 'link2'), '', '4') // Inserts before Link 2
                ->addLink('Link 6', '#') // Automatically creates key: item3
                ->addLink('Link 7', '#') // Automatically creates key: item4
                ->addLink('Link 8', '#', true, 'group2.link8', false, 'flame', '', true) // Adds to Group 2
                ->addLink('Link 9', '#', true, 'group1.link9') // Adds to Group 1
                ->addLink('Link 10', '#', true, 'group1.link10'); // Adds to Group 1

//            echo $dropdown->toString();

            $menu = new NavModule('nav');

            // Permissions
            $gdnSettingsManage = Gdn::Session()->CheckPermission('Garden.Settings.Manage');
            $gdnSettingsView = Gdn::Session()->CheckPermission('Garden.Settings.View');
            $gdnCommunityManage = Gdn::Session()->CheckPermission('Garden.Community.Manage');

            $menu->addGroup(T('Dashboard'), true, 'dashboard')
                ->addDropdown($dropdown, 'dashboard.dropdown')
                ->addLink(T('Dashboard'), '/dashboard/settings', $gdnSettingsView, 'dashboard.dashboard')
                ->addLink(T('Getting Started'), '/dashboard/settings/gettingstarted', $gdnSettingsManage, 'dashboard.getting-started')
                ->addLink(T('Help &amp; Tutorials'), '/dashboard/settings/tutorials', $gdnSettingsView, 'dashboard.help-and-tutorials')

                ->addGroup(T('Appearance'), true, 'appearance')
                ->addLink(T('Banner'), '/dashboard/settings/banner', $gdnCommunityManage, 'appearance.banner')
                ->addLink(T('Homepage'), '/dashboard/settings/homepage', $gdnSettingsManage, 'appearance.homepage')
                ->addLink(T('Themes'), '/dashboard/settings/themes', $gdnSettingsManage, 'appearance.themes')
                ->addLink(T('Theme Options'), '/dashboard/settings/themeoptions', $gdnSettingsManage && C('Garden.ThemeOptions.Name'), 'appearance.theme-options')
                ->addLink(T('Mobile Themes'), '/dashboard/settings/mobilethemes', $gdnSettingsManage, 'appearance.mobile-themes')
                ->addLink(T('Mobile Theme Options'), 'dashboard/settings/mobilethemeoptions', $gdnSettingsManage && C('Garden.MobileThemeOptions.Name'), 'appearance.mobile-theme-options')
                ->addLink(T('Messages'), '/dashboard/message', $gdnCommunityManage, 'appearance.messages')

                ->addGroup(T('Users'), true, 'users')
                ->addLink(T('Users'), '/dashboard/user', Gdn::Session()->CheckPermission(array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete')), 'users.settings')
                ->addLink(T('Roles & Permissions'), '/dashboard/role', Gdn::Session()->CheckPermission(array('Garden.Settings.Manage', 'Garden.Roles.Manage'), FALSE), 'users.roles-and-permissions')
                ->addLink(T('Registration'), '/dashboard/settings/registration', $gdnSettingsManage, 'users.registration')
                ->addLink(T('Authentication'), '/dashboard/authentication', $gdnSettingsManage, 'users.authentication')
                ->addLink(T('Applicants'), '/dashboard/user/applicants', Gdn::Session()->CheckPermission('Garden.Users.Approve') && (C('Garden.Registration.Method') == 'Approval'), 'users.applicants')

                ->addGroup(T('Moderation'), true, 'moderation')
                ->addLink(T('Spam Queue'), '/dashboard/log/spam', Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.Spam.Manage'), FALSE), 'moderation.spam', false, '', '', '/dashboard/user/applicantcount')
                ->addLink(T('Moderation Queue'), '/dashboard/log/moderation', Gdn::Session()->CheckPermission(array('Garden.Moderation.Manage', 'Moderation.ModerationQueue.Manage'), FALSE), 'moderation.queue', false, '', '', '/dashboard/log/count/moderate')
                ->addLink(T('Change Log'), '/dashboard/log/edits', Gdn::Session()->CheckPermission('Garden.Moderation.Manage'), 'moderation.change-log')
                ->addLink(T('Banning'), '/dashboard/settings/bans', $gdnCommunityManage, 'moderation.banning')

                ->addGroup(T('Forum Settings'), true, 'forum')
                ->addLink(T('Social'), '/dashboard/social', $gdnSettingsManage, 'forum.social')

                ->addGroup(T('Reputation'), true, 'reputation')

                ->addGroup(T('Addons'), true, 'add-ons')
                ->addLink(T('Plugins'), '/dashboard/settings/plugins', $gdnSettingsManage, 'add-ons.plugins')
                ->addLink(T('Applications'), '/dashboard/settings/applications', $gdnSettingsManage, 'add-ons.applications')
                ->addLink(T('Locales'), '/dashboard/settings/locales', $gdnSettingsManage, 'add-ons.locales')

                ->addGroup(T('Settings'), true, 'site-settings')
                ->addLink(T('Outgoing Email'), '/dashboard/settings/email', $gdnSettingsManage, 'site-settings.outgoing-email')
                ->addLink(T('Routes'), '/dashboard/routes', $gdnSettingsManage, 'site-settings.routes')
                ->addLink(T('Statistics'), '/dashboard/statistics', $gdnSettingsManage, 'site-settings.statistics')

                ->addGroup(T('Import'), true, 'import')
                ->addLink(T('Import'), '/dashboard/import', $gdnSettingsManage, 'import.settings');

            $this->AddModule($menu, 'Panel');

        }
    }
}
