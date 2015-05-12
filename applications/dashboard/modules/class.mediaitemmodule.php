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

    public $tag;
    public $metaTag = 'li';

    public $cssClass;
    public $bodyCssClass;
    public $boxCssClass; //media-left, media-right, media-middle (for vertical alignment)
    public $textCssClass;
    public $buttonsCssClass;
    public $headingCssClass;
    public $metaCssClass;
    public $imageCssClass;

    public $imageSource;
    public $imageUrl;
    public $imageAlt;

    public $meta = null;
    public $attachments = null;
    public $buttons = null;
    public $mediaList = null; //mediaList (children)

    public $hasImage = false;
    public $hasMeta = false;
    public $hasAttachments = false; //i.e., file uploads
    public $hasButtons = false; //i.e., flair
    public $hasMediaList = false;

    public $view = 'mediaitem';

    function __construct($heading = '', $headingUrl = '', $text = '', $type = '', $id = 'media-item', $tag = 'li') {
        parent::__construct($this->view);
        $this->_ApplicationFolder = 'dashboard';

        $this->text = $text;
        $this->heading = $heading;
        $this->headingUrl = $headingUrl;
        $this->type = $type;
        $this->id = $id;
        $this->tag = $tag;

        return $this;
    }

    public function addCssClass($var, $class) {
        switch($var) {
            case 'main':
                $this->cssClass = $class;
                break;
            case 'body':
                $this->bodyCssClass = $class;
                break;
            case 'text':
                $this->textCssClass = $class;
                break;
            case 'heading':
                $this->headingCssClass = $class;
                break;
            case 'meta':
                $this->metaCssClass = $class;
                break;
            case 'box':
                $this->boxCssClass = $class;
                break;
            case 'buttons':
                $this->buttonsCssClass = $class;
                break;
            case 'image':
                $this->imageCssClass = $class;
                break;
        }
        return $this;
    }

    public function prepare(){
        for ($i = 0; $i < count($this->buttons); ++$i) {
            if (!$this->buttons[$i]->prepare()) {
                unset($this->buttons[$i]);
            }
        }

        for ($i = 0; $i < count($this->meta); ++$i) {
            if (!$this->meta[$i]->prepare()) {
                unset($this->meta[$i]);
            }
        }

        if (!empty($this->options)) {
            if (!$this->options->prepare()) {
                unset($this->options);
            }
        }

        $this->hasImage = !empty($this->imageSource);
        $this->hasAttachments = !empty($this->attachments);
        $this->hasButtons = !empty($this->buttons);
        $this->hasMediaList = !empty($this->mediaList);
        $this->hasMeta = !empty($this->meta);

        return true;
    }

    function addImage($source = '', $url = '', $isAllowed = true, $cssClass = '', $alt = '') {
        if (!$isAllowed) {
            return $this;
        }

        $this->imageSource = $source;
        $this->imageUrl = $url;
        $this->imageCssClass = $cssClass;
        $this->imageAlt = $alt;

        return $this;
    }

//    public function addMetaItem($metaLabel = '', $metaUrl = '', $metaLinkText = '', $isAllowed = true, $metaIcon = '', $metaBadge = '', $metaItemCssClass = '') {
//        if (!$isAllowed) {
//            return $this;
//        }
//        $metaHasLink = !empty($metalUrl) && ($metaLinkText && $metaLabel);
//        $metaIsLink = !($metaHasLink) && !empty($metaUrl);
//        $this->meta[] = array(
//            'metaItemCssClass' => $metaItemCssClass,
//            'metaIsLink' => $metaHasLink,
//            'metaHasLink' => $metaIsLink,
//            'metaLabel' => $metaLabel, // UserLink()
//            'metaUrl' => $metaUrl,
//            'metaLinkText' => $metaLinkText,
//            'metaIcon' => $metaIcon,
//            'metaBadge' => $metaBadge
//        );
//        return $this;
//    }

//    public function addButton($text, $url, $isAllowed = true, $icon = '', $badge = '', $disabled = false, $class = 'btn-default') {
//        if (!$isAllowed) {
//            return $this;
//        }
//        if ($disabled) {
//            $class .= " disabled";
//        }
//        $button = array(
//            'buttonText' => $text,
//            'buttonUrl' => $url,
//            'buttonIcon' => $icon,
//            'buttonBadge' => $badge,
//            'buttonCssClass' => $class
//        );
//        $this->buttons[] = $button;
//        return $this;
//    }

    public function addMetaItem($metaItem) {
        if (is_a($metaItem, 'MetaItemModule')) {
            $this->meta[] = $metaItem;
        }
        return $this;
    }

    public function addButton($button) {
        if (is_a($button, 'ButtonModule')) {
            $this->buttons[] = $button;
        }
        return $this;
    }

    public function addOptions($options) {
        if (is_a($options, 'DropdownModule') && $options->hasItems()) {
            $this->options = $options;
        }
        return $this;
    }

    public function addMediaList($mediaList) {
        if (is_a($mediaList, 'MediaListModule')) {
            $this->mediaList = $mediaList;
        }
        return $this;
    }

    public function setMetaTag($tag) {
        $this->metaTag = $tag;
    }
}
