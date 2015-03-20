<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a media list.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
class MediaItemModule extends MustacheModule {

    public $text;
    public $heading;
    public $headingUrl;
    public $options = false; //dropdown
    public $date;
    public $username;

    public $tag;
    public $cssClass;
    public $bodyCssClass;
    public $boxCssClass; //media-left, media-right, media middle (for vertical alignment)
    public $textCssClass;
    public $headingCssClass;
    public $metaCssClass;

    public $image = array();
    public $meta = array();
    public $attachments = null;
    public $boxItems = null;
    public $mediaList = null; //mediaList

    public $hasMeta = false;
    public $hasImage = false;
    public $hasAttachments = false; //i.e., file uploads
    public $hasBoxItems = false; //i.e., flair
    public $hasMediaList = false;

    private $trucateTextLength;
    private $trucateTextLink;
    private $trucateTextUrl;

    private $view = 'mediaitem';

    function __construct($id, $text = '', $heading = '', $headingUrl = '', $options = false, $date = false, $username = false, $tag = 'li', $cssClass = '', $bodyCssClass = '', $boxCssClass = 'media-left', $textCssClass = '', $headingCssClass = '', $metaCssClass = '') {
        parent::__construct($this->view);
        $this->_ApplicationFolder = 'dashboard';

        $this->id = $id;
        $this->text = $text;
        $this->heading = $heading;
        $this->headingUrl = $headingUrl;
        $this->options = $options;
        $this->date = $date;
        $this->username = $username;

        $this->tag = $tag;
        $this->cssClass = $cssClass;
        $this->bodyCssClass = $bodyCssClass;
        $this->boxCssClass = $boxCssClass;
        $this->textCssClass = $textCssClass;
        $this->headingCssClass = $headingCssClass;
        $this->metaCssClass = $metaCssClass;

        if (is_a($options, 'DropdownModule')) {
            $this->options = $options;
        }
    }

    public function prepare(){}

    function addImage($imageSource = '', $imageUrl = '', $imageCssClass = '', $imageAlt = '') {
        $this->hasImage = true;
        $this->image = array('imageSource' => $imageSource,
            'imageUrl' => $imageUrl,
            'imageCssClass' => $imageCssClass,
            'imageAlt' => $imageAlt
        );
        return $this;
    }

    public function addMetaItem($metaLabel = '', $metaUrl = '', $metaLinkText = '', $metaIcon = '', $metaBadge = '', $metaItemCssClass = '') {
        $this->hasMeta = true;
        $metaHasLink = !empty($metalUrl) && ($metaLinkText && $metaLabel);
        $metaIsLink = !($metaHasLink) && !empty($metaUrl);
        $this->meta[] = array('metaItemCssClass' => $metaItemCssClass,
            'metaIsLink' => $metaHasLink,
            'metaHasLink' => $metaIsLink,
            'metaLabel' => $metaLabel, // UserLink()
            'metaUrl' => $metaUrl,
            'metaLinkText' => $metaLinkText,
            'metaIcon' => $metaIcon,
            'metaBadge' => $metaBadge
        );
    }
}
