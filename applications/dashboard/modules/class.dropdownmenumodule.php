<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a dropdown.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */

/**
 * A flawlessly configurable module for a dropdown menu.
 *
 * The dropdown menu is built with Twitter Bootstrap classes and more specific
 * classes to allow for better targeting. It relies on the Twitter Bootstrap js file.
 *
 * The module includes a dropdown trigger and menu items. Menu items can be
 *
 * **link**    - An link item.
 * **group**   - A group item to create a logical grouping of menu items
 *               for sorting purposes, and/or to create a heading.
 * **divider** - A dividing line.
 *
 * Each item must have a unique key. If not supplied, the class will generate
 * one in the format: 'item*', where * is an auto incrementing number.
 * Keys can be used for sorting purposes and for adding links to a group.
 * For example, you could add a property to an item of 'sort'=>array('before'=>'key1')
 * and it would place the item before another item with the key of 'key1'.
 * If you have a group with the key of 'key2', you can add to this group by
 * adding a new item with the property of 'key'=>'key2.newItemKey'
 *
 *
 * Here is an example menu creation:
 *
 *  $dropdown = new DropDownMenuModule($this, 'my-dropdown', 'Trigger Name', '', 'dropdown-menu-right');
 *  $dropdown->setTrigger('A New Name', 'button', 'btn-default', 'caret');
 *  $dropdown->addLink(array('text' => 'Link 1', 'url' => '#')); // Automatically creates key: item1
 *  $dropdown->addDivider(''); // Automatically creates key: item2
 *  $dropdown->addHeader('Header 1'); // Automatically creates key: item3
 *  $dropdown->addLink(array('text' => 'Link 2', 'url' => '#', 'key' => 'link2', 'class' => 'bg-danger')); // Creates item with key: link2
 *  $dropdown->addLinks(array(
 *     array('text' => 'Link 3', 'url' => '#'), // Automatically creates key: item4
 *     array('text' => 'Link 4', 'url' => '#')
 *  ));
 *  $dropdown->addGroup(array('key' => 'group1')); // Creates group with no header
 *  $dropdown->addGroup(array('text' => 'Group 2', 'key' => 'group2')); // Creates group with header: 'Group 2'
 *  $dropdown->addLink(array('text' => 'Link 5', 'url' => '#', 'sort'=>array('before', 'link2'), 'badge' => 4)); // Inserts before Link 2
 *  $dropdown->addLinks(array(
 *     array('text' => 'Link 6', 'url' => '#'),
 *     array('text' => 'Link 7', 'url' => '#')
 *  ));
 *  $dropdown->addLink(array('text' => 'Link 8', 'url' => '#', 'disabled'=>true, 'key' => 'group2.link8', 'icon' => 'icon-flame')); // Adds to Group 2
 *  $dropdown->addLink(array('text' => 'Link 9', 'url' => '#', 'disabled'=>true, 'key' => 'group1.link9')); // Adds to Group 1
 *  $dropdown->addLink(array('text' => 'Link 10', 'url' => '#', 'key' => 'group1.link10')); // Adds to Group 1
 *  echo $dropdown->toString();
 *
 * Which results in a menu:
 *
 *  Trigger Name
 *
 *  Link 1
 *  ------------
 *  Header 1
 *  Link 5
 *  Link 2
 *  Link 3
 *  Link 4
 *  Link 9
 *  Link 10
 *  Group 2
 *  Link 8
 *  Link 6
 *  Link 7
 *
 * The view is currently a mustache template, which requires the Mustache rendering plugin to be enabled.
 *
 */
class DropDownMenuModule extends Gdn_Module {

    /**
     * @var string The css class of the menulist, if any.
     */
    public $listCssClass = '';

    /**
     * @var string The id of the trigger.
     */
    public $id = null;

    /**
     * @var array An array of items in the menu.
     */
    public $items = array();

    /**
     * @var int Number to generate key strings from.
     */
    protected $keyNumber = 1;

    /**
     * @var boolean Use item prefix when applying new css classes.
     */
    protected $useCssPrefix = false;

    /**
     * @var array Collection of trigger attributes.
     */
    public $trigger = array('type' => 'button',
                            'button' => true,
                            'class' => 'btn-default',
                            'icon' => 'caret');

    /**
     * @var string Dropdown top-level div CSS class.
     */
    public $class;

    /**
     * @var array Allowed trigger types.
     */
    protected $triggerTypes = array('button', 'anchor');

    /// Methods ///

    public function __construct($sender, $id, $triggerText = '', $class = '', $listCssClass = '', $useCssPrefix = false) {
        parent::__construct($sender);
        $this->_ApplicationFolder = 'dashboard';
        $this->id = $id;
        $this->trigger['text'] = $triggerText;
        $this->class = $class;
        $this->listCssClass = $listCssClass; // Bootstrap supports 'right' to align the dropdown box to the right of its container
        $this->useCssPrefix = $useCssPrefix;
    }

    /**
     * Configure the trigger.
     *
     * @param string $text Text on the button or anchor.
     * @param string $type Trigger type - currently supports 'anchor' or 'button'.
     * @param string $class CSS class on button or anchor tag.
     * @param string $icon Icon span CSS class.
     */
    public function setTrigger($text, $type = 'button', $class = 'btn-default', $icon = 'caret') {
        $this->trigger['text'] = $text;
        $this->trigger['type'] = in_array($type, $this->triggerTypes) ? $type : 'button';
        $this->trigger['icon'] = $icon;
        $this->trigger['class'] = $class;

        //for mustache logic
        $this->trigger['button'] = $this->trigger['type'] === 'button';
        $this->trigger['anchor'] = $this->trigger['type'] === 'anchor';
    }

    /**
     * Add a divider to the items array.
     *
     * @param string $key The key of the divider.
     * @param array $options Options for the divider.
     */
    public function addDivider($divider = array()) {
        $divider['class'] = 'divider '.$this->buildCssClass('divider', $divider);
        $this->addItem('divider', $divider);
    }

    /**
     * Add a group to the items array.
     *
     * @param array $group The group with the following key(s):
     * - **text**: The text of the group heading.
     * - **icon**: The icon class to appear by header.
     * - **badge**: The header badge such as a count or alert.
     * - **sort**: Specify a custom sort order for the item.
     *    This can be either a number or an array in the form ('before|after', 'key').
     * - **key**: Group key.
     * - **class**: Header CSS class.
     */
    public function addGroup($group) {
        if (val('text', $group)) {
            $group['class'] = $this->buildCssClass('dropdown-header', $group);
        }
        $this->addItem('group', $group);
    }

    /**
     * Add a simple header to a dropdown.
     *
     * @param $name
     */
    public function addHeader($name) {
        $this->addGroup(array('text' => $name));
    }

    /**
     * Add a link to the menu.
     *
     * @param string|array $key The key of the link. You can nest links in a group by using dot syntax to specify its key.
     * @param array $link The link with the following keys:
     * - **url**: The url of the link.
     * - **text**: The text of the link.
     * - **icon**: The icon class to appear by link.
     * - **badge**: The link contain a badge. such as a count or alert.
     * - **sort**: Specify a custom sort order for the item.
     *    This can be either a number or an array in the form ('before|after', 'key').
     * - **disabled**: Set to true to add disabled style to link.
     * - **key**: Item key.
     * - **class**: CSS class.
     */
    public function addLink($link) {
        $link['class'] = $this->buildCssClass('dropdown-menu-link', $link);
        if (val('disabled', $link)) {
            $link['listItemCssClass'] = 'disabled';
        }
        $this->addItem('link', $link);
    }


    /**
     * Add a list of links to the menu.
     *
     * @param array $links List of links
     */
    public function addLinks($links) {
        foreach($links as $link) {
            $this->addLink($link);
        }
    }

    /**
     * Add an item to the items array.
     *
     * @param string $type The type of item (link, group, or divider).
     * @param string $key The item key. Dot syntax is allowed to nest items into groups.
     * @param array $item The item to add.
     */
    protected function addItem($type, $item) {
        if (!val('key', $item)) {
            $item['key'] = 'item'.$this->keyNumber;
            $this->keyNumber = $this->keyNumber+1;
        }
        if (!is_array(val('key', $item))) {
            $item['key'] = explode('.', val('key', $item));
        }
        else {
            $item['key'] = array_values(val('key', $item));
        }

        $item = (array)$item;

        //set type boolean for mustache logic
        $item[$type] = true;

        // Make sure the link has its type.
        $item['type'] = $type;

        // Walk into the items list to set the item.
        $items =& $this->items;
        foreach (val('key', $item) as $i => $key_part) {
            if ($i === count(val('key', $item)) - 1) {
                // Add the item here.
                if (array_key_exists($key_part, $items)) {
                    // The item is already here so merge this one on top of it.
                    if ($items[$key_part]['type'] !== $type)
                        throw new \Exception(val('key', $item)." of type $type does not match existing type {$items[$key_part]['type']}.", 500);

                    $items[$key_part] = array_merge($items[$key_part], $item);
                } else {
                    // The item is new so just add it here.
                    touchValue('_sort', $item, count($items));
                    $items[$key_part] = $item;
                }
            } else {
                // This is a group.
                if (!array_key_exists($key_part, $items)) {
                    // The group doesn't exist so lazy-create it.
                    $items[$key_part] = array('type' => 'group', 'text' => '', 'items' => array(), '_sort' => count($items));
                } elseif ($items[$key_part]['type'] !== 'group') {
                    throw new \Exception("$key_part is not a group", 500);
                } elseif (!array_key_exists('items', $items[$key_part])) {
                    // Lazy create the items array.
                    $items[$key_part]['items'] = array();
                }

                $items =& $items[$key_part]['items'];
            }
        }
    }

    /**
     * Adds CSS class[es] to an item, based on 'class' property of an item
     * and also the 'key' property of an item. Prepends prefix to class names.
     *
     * @param string $prefix Prefix to add to class name.
     * @param array $item Item to add CSS class to.
     * @return string
     */
    protected function buildCssClass($prefix, $item) {
        $result = '';
        if ($prefix) {
            $prefix .= '-';
        }
        if (!$this->useCssPrefix) {
            $prefix = '';
        }
        if (val('key', $item)) {
            if (is_array(val('key', $item))) {
                $result .= $prefix.implode('-', val('key', $item));
            }
            else {
                $result .= $prefix.str_replace('.', '-', val('key', $item));
            }
        }
        if (val('class', $item)) {
            $result .= ' '.$prefix.val('class', $item);
        }
        return trim($result);
    }

    /**
     * Renders dropdown menu view
     *
     * @return string
     */
    public function toString() {
        NavModule::sortItems($this->items);
        $this->items = $this->flattenArray($this->items);
        $m = new Mustache_Engine();
        return $m->render($this->FetchView('dropdownmenu'), $this);
    }

    /**
     * Creates flattened array of dropdown menu items.
     *
     * @param array $items
     * @return array
     */
    public function flattenArray($items) {
        $newitems = array();
        foreach($items as $item) {
            $subitems = false;
            if (val('items', $item)) {
                $subitems = $item['items'];
                unset($item['items']);
            }
            if ((val('type', $item) != 'group') || val('text', $item)) {
                unset($item['_sort'], $item['key']);
                $newitems[] = $item;
            }
            if ($subitems) {
                $newitems = array_merge($newitems, $this->flattenArray($subitems));
            }
        }
        return $newitems;

    }
}
