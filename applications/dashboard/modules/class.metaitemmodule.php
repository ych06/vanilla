<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a button.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
class MetaItemModule extends ComponentModule {

    public $metaText;
    public $metaUrl;
    public $metaLinkText;
    public $metaIcon;
    public $metaBadge;
    public $metaItemCssClass;

    public $metaIsLink = false;
    public $metaHasLink = false;

    private $isAllowed;

    function __construct($text, $url = '', $isAllowed = true, $linkText = '', $icon = '', $badge = '', $cssClass = '') {
        parent::__construct('metaitem');

        $this->metaText = $text;
        $this->metaUrl = $url;
        $this->isAllowed = $isAllowed;
        $this->metaLinkText = $linkText;
        $this->metaIcon = $icon;
        $this->metaBadge = $badge;
        $this->metaItemCssClass = $cssClass;
    }

    public function isAllowed() {
        return $this->isAllowed;
    }

    public function prepare() {
        $this->metaHasLink = !empty($this->metaUrl) && ($this->metaLinkText && $this->metaText);
        $this->metaIsLink = !($this->metaHasLink) && !empty($this->metaUrl);

        return $this->isAllowed;
    }
}

