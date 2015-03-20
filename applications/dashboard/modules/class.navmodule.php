<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a nav.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */

/**
 * A module for a list of links.
 */
class NavModule extends SortableModule {

    /// Methods ///

    public function __construct($id, $class = '', $useCssPrefix = false, $stacked = false, $pills = false, $tabs = false, $justified = false) {
        parent::__construct('nav', false, $useCssPrefix);

        // Set parent attributes
        $this->id = $id;

        if ($useCssPrefix) {
            $this->headerCssClassPrefix = 'nav-header';
            $this->linkCssClassPrefix = 'nav-link';
            $this->dividerCssClassPrefix = 'divider';
        }

        $classes = array();
        $classes[] = trim($class);
        if ($stacked) {
            $classes[] = 'nav-stacked';
        }
        if ($pills) {
            $classes[] = 'nav-pills';
        }
        if ($tabs) {
            $classes[] = 'nav-tabs';
        }
        if ($justified) {
            $classes[] = 'nav-justified';
        }

        $this->class = implode(' ', $classes);
    }

    public function addDropdown($dropdown, $key = '', $sort = '') {
        $dropdownItem = array();
        if ($key) {
            $dropdownItem['key'] = $key;
        }
        if ($sort) {
            $dropdownItem['sort'] = $sort;
        }
        $dropdown->tag = 'li';
        $dropdown->prepare();
        $dropdownItem['dropdownmenu'] = $dropdown;
        $this->addItem('dropdown', $dropdownItem);
        return $this;
    }
}
