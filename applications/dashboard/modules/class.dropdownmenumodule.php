<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a dropdown.
 *
 * @author Becky Van Bussel <todd@vanillaforums.com>
 * @copyright 2003 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */

/**
 * A module for a dropdown.
 *
 * The dropdown menu is built with Twitter Bootstrap classes and more specific
 * classes to allow for better targetting. It relies on the Twitter Bootstrap js file.
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
 *  $dropdown = new DropDownMenuModule($this, 'my-dropdown', 'Trigger Name', 'button', 'btn-default', 'caret', 'right');
 *  $dropdown->addLink(array('text'=>'Link 1', 'url'=>'#'), 'string'); // Automatically creates key: item1
 *  $dropdown->addDivider(''); // Automatically creates key: item2
 *  $dropdown->addHeader('Header 1'); // Automatically creates key: item3
 *  $dropdown->addLink(array('text'=>'Link 2', 'url'=>'#', 'key'=>'link2')); // Creates item with key: link2
 *  $dropdown->addLinks(array(
 *                          array('text'=>'Link 3', 'url'=>'#'), // Automatically creates key: item4
 *                          array('text'=>'Link 4', 'url'=>'#')
 *                      ));
 *  $dropdown->addGroup(array('key'=>'group1')); // Creates group with no header
 *  $dropdown->addGroup(array('text'=>'Group 2', 'key'=>'group2')); // Creates group with header: 'Group 2'
 *  $dropdown->addLink(array('text'=>'Link 5', 'url'=>'#', 'sort'=>array('before', 'link2'))); // Inserts before Link 2
 *  $dropdown->addLinks(array(
 *                          array('text'=>'Link 6', 'url'=>'#'),
 *                          array('text'=>'Link 7', 'url'=>'#')
 *                      ));
 *  $dropdown->addLink(array('text'=>'Link 8', 'url'=>'#', 'disabled'=>true, 'key'=>'group2.link8')); // Adds to Group 2
 *  $dropdown->addLink(array('text'=>'Link 9', 'url'=>'#', 'disabled'=>true, 'key'=>'group1.link9')); // Adds to Group 1
 *  $dropdown->addLink(array('text'=>'Link 10', 'url'=>'#', 'key'=>'group1.link10')); // Adds to Group 1
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
 *
 */
class DropDownMenuModule extends Gdn_Module {

    /**
     *
     * @var string The css class of the menulist, if any.
     */
    public $listCssClass = '';

    /**
     *
     * @var string The id of the trigger.
     */
    public $id = null;

    /**
     * @var array An array of items in the menu.
     */
    protected $items = array();

    /**
     * @var int Number to generate key strings from.
     */
    protected $keyNumber = 1;

    /**
     * @var array Allowed trigger types.
     */
    protected $triggerTypes = array('button', 'anchor');

    /**
     * @var string Text to appear on trigger button or anchor
     */
    protected $triggerText = '';

    /**
     * @var string Trigger CSS class.
     */
    protected $triggerCssClass = '';

    /**
     * @var string Trigger type: either button or anchor.
     */
    protected $triggerType = '';

    /**
     * @var string Icon to appear on trigger.
     */
    protected $triggerIcon = '';

    /// Methods ///

    public function __construct($Sender, $id, $triggerText = '', $triggerType = 'button', $triggerCssClass = 'btn-default', $triggerIcon = 'caret', $listCssClass = '') {
        parent::__construct($Sender);

        $this->_ApplicationFolder = 'dashboard';

        $this->id = $id;

        // Trigger styles
        $this->triggerType = in_array($triggerType, $this->triggerTypes) ? $triggerType : 'button';
        $this->triggerText = $triggerText;
        $this->triggerIcon = $triggerIcon;
        $this->triggerCssClass = $triggerCssClass;

        $this->listCssClass = $listCssClass; // Bootstrap supports 'right' to align the dropdown box to the right of its container
    }

    /**
     * Add a divider to the items array.
     *
     * @param string $key The key of the divider.
     * @param array $options Options for the divider.
     */
    public function addDivider($options = array()) {
        $this->addItem('divider', $options);
    }

    /**
     * Add a group to the items array.
     *
     * @param array $group The group with the following key(s):
     * - **text**: The text of the group heading. Html is allowed.
     * - **icon**: The html of the icon to appear by header.
     * - **badge**: The header badge such as a count or alert. Html is allowed.
     * - **sort**: Specify a custom sort order for the item.
     *    This can be either a number or an array in the form ('before|after', 'key').
     * - **key**: Group key.
     * - **class**: Header CSS class.
     */
    public function addGroup($group) {
        $this->addItem('group', $group);
    }

    /**
     * Add a simple header to a dropdown.
     *
     * @param $name
     */
    public function addHeader($name) {
         $this->addGroup(array('text'=>$name));
    }


    /**
     * Add a link to the menu.
     *
     * @param string|array $key The key of the link. You can nest links in a group by using dot syntax to specify its key.
     * @param array $link The link with the following keys:
     * - **url**: The url of the link.
     * - **text**: The text of the link. Html is allowed.
     * - **icon**: The html of the icon.
     * - **badge**: The link contain a badge. such as a count or alert. Html is allowed.
     * - **sort**: Specify a custom sort order for the item.
     *    This can be either a number or an array in the form ('before|after', 'key').
     * - **disabled**: Set to true to add disabled style to link.
     * - **key**: Item key.
     * - **class**: CSS class.
     */
    public function addLink($link) {
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

    protected function getMenuAttributes() {
        $attributes = array('class' => 'dropdown-menu '.$this->listCssClass, 'role' => 'menu', 'aria-labelledby' => $this->id);

        return attribute($attributes);
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
        if (val('key', $item)) {
            $result .= $prefix.implode('-', val('key', $item));
        }
        if (val('class', $item)) {
            $result .= $prefix.val('class', $item);
        }
        return trim($result);
    }

    // Note to self: learn why we may need this
    protected function itemVisible($item) {
        $visible = val('visible', $item, true);
        $prop = 'show'.val('key', $item);

        if (property_exists($this, $prop)) {
            return $this->$prop;
        } else {
            return $visible;
        }
    }

    /**
     * Render the trigger and dropdown menu.
     */
    public function render() {
        echo '<div class="dropdown">'."\n";
        $this->renderTrigger();
        echo '<ul '.$this->getMenuAttributes().">\n";
        $this->renderItems($this->items);
        echo "</ul>\n";
        echo "</div>";
    }

    // TODO: Use icon function to render icon.
    protected function renderTrigger() {
        if ($this->triggerType === 'button') {
            echo '<button id="'.$this->id.'" class="btn dropdown-toggle '.$this->triggerCssClass.'" type="button" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">';
            echo "$this->triggerText\n";
            if ($this->triggerIcon) {
                echo '<span class="'.$this->triggerIcon.'"></span>';
            }
            echo '</button>';
        }
        else if ($this->triggerType === 'anchor') {
            echo '<a id="'.$this->id.'" class="'.$this->triggerCssClass.'" data-target="#" href="/" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">';
            echo "$this->triggerText\n";
            if ($this->triggerIcon) {
                echo '<span class="'.$this->triggerIcon.'"></span>';
            }
            echo '</a>';
        }
    }

    protected function renderItems($items, $level = 0) {
        // TODO: Move these helper functions to a new class.
        NavModule::sortItems($items);

        foreach ($items as $key => $item) {
            $visible = $this->itemVisible($key, $item);
            if (!$visible)
                continue;

            switch ($item['type']) {
                case 'link':
                    $this->renderLink($item);
                    break;
                case 'group':
                    $this->renderGroup($item, $level);
                    break;
                case 'divider':
                    $this->renderDivider($item);
                    break;
                default:
                    echo "\n<!-- Item $key has an unknown type {$item['type']}. -->\n";
            }
        }
    }

    // TODO: Use icon function to render icon.
    protected function renderLink($link) {
        $href = val('url', $link);
        $text = val('text', $link);
        $icon = val('icon', $link);
        $badge = val('badge', $link);
        $class = 'dropdown-menu-link '.$this->buildCssClass('dropdown-menu-link', $link);
        $listItemClass = '';
        $disabled = val('disabled', $link);
        if ($disabled) {
            $listItemClass = 'disabled';
        }

        if ($icon)
            $text = $icon.' <span class="text">'.$text.'</span>';

        if ($badge) {
            if (is_numeric($badge)) {
                $badge = Wrap(number_format($badge), 'span', array('class' => 'count'));
            }
            $text = '<span class="badge">'.$badge.'</span> '.$text;
        }

        unset($link['url'], $link['text'], $link['class'], $link['icon'], $link['badge'], $link['disabled'], $link['key'], $link['sort']);

        $link['role'] = 'menuitem';
        $link['tabindex'] = '-1';

        if ($listItemClass) {
             $listItemClass = ' class="'.$listItemClass.'"';
        }

        echo "<li role=\"presentation\" $listItemClass>";
        echo Anchor($text, $href, $class, $link, true)."\n";
        echo "</li>";
    }

    // TODO: Use icon function to render icon.
    protected function renderGroup($group, $level = 0) {
        $text = val('text', $group);
        $items = val('items', $group);
        $icon = val('icon', $group);
        $badge = val('badge', $group);

        // Don't render an empty group.
        if (!$text && empty($items))
            return;

        $class = 'dropdown-header '.$this->buildCssClass('dropdown-header', $group);

        if ($icon)
            $text = $icon.' <span class="text">'.$text.'</span>';

        if ($badge) {
            if (is_numeric($badge)) {
                $badge = Wrap(number_format($badge), 'span', array('class' => 'count'));
            }
            $text = '<span class="badge">'.$badge.'</span> '.$text;
        }

        // Write the heading.
        if ($text) {
            echo '<li class="'.$class.'">'.$text.'</li>'."\n";
        }

        unset($group['text'], $group['class'], $group['icon'], $group['badge'], $group['items'], $group['sort']);

        if (!empty($items)) {
            // Write the group items.
            $this->renderItems($items, $level + 1);
        }

    }

    protected function renderDivider($divider) {
        echo '<li role="presentation" class="divider '.$this->buildCssClass('divider', $divider).'"></li>'."\n";
    }

    public function toString() {
        ob_start();
        $this->render();
        $result = ob_get_clean();

        return $result;
    }
}
