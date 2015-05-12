<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a button.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
class ButtonModule extends MustacheModule {

    public $buttonText;
    public $buttonUrl;
    public $buttonIcon;
    public $buttonBadge;
    public $buttonCssClass;

    private $isAllowed;

    function __construct($text, $url, $isAllowed = true, $icon = '', $badge = '', $disabled = false, $cssClass = '') {
        parent::__construct('button');

        $this->buttonText = $text;
        $this->buttonUrl = $url;
        $this->isAllowed = $isAllowed;
        $this->buttonIcon = $icon;
        $this->buttonBadge = $badge;
        $this->buttonCssClass = $cssClass;

        if ($disabled) {
            $this->buttonCssClass .= ' disabled';
        }

        return $this;
    }

    public function isAllowed() {
        return $this->isAllowed;
    }

    public function prepare() {
        return $this->isAllowed;
    }
}
