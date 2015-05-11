<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a media list.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
class MediaListModule extends MustacheModule {

    public $mediaItems;
    public $mediaListTableColumns;

    public $mediaListDescription;
    public $mediaListHeading;
    public $mediaListEmptyMessage;

    // For styling and the dom
    public $mediaListId;
    public $mediaListHeadingTag;

    public $mediaListCssClass;
    public $mediaListDescriptionCssClass;
    public $mediaListHeadingCssClass;
    public $mediaListEmptyMessageCssClass;

    public $moreLink;
    public $moreUrl;
    public $moreIcon;
    public $moreBadge;
    public $moreCssClass;

    // Instantiated in prepare
    public $hasMediaItems;
    public $hasMediaListContainer;
    public $hasMoreLink;
    public $hasEmptyMessage;

    private $view = 'medialist';

    function __construct($heading = '', $description = '', $emptyMessage = '', $id = 'medialist', $headingTag = 'h2') {
        parent::__construct($this->view);
        $this->mediaListHeading = $heading;
        $this->mediaListDescription = $description;
        $this->mediaListEmptyMessage = $emptyMessage;
        $this->mediaListId = $id;
        $this->mediaListHeadingTag = $headingTag;
        return $this;
    }

    function addMediaItems($mediaItems) {
        foreach ($mediaItems as $mediaItem) {
            if (is_a($mediaItem, 'MediaItemModule')) {
                $this->mediaItems[] = $mediaItem;
            }
        }
        return $this;
    }

    function addTableColumn($label, $cssClass = '') {
        $this->mediaListTableColumns[] = array(
            'columnLabel' => $label,
            'columnCssClass' => $cssClass
        );
        return $this;
    }

    function addMoreLink($link, $url, $icon = '', $badge = '', $cssClass = '') {
        $this->moreLink = $link;
        $this->moreUrl = $url;
        $this->moreIcon = $icon;
        $this->moreBadge = $badge;
        $this->moreCssClass = $cssClass;
        return $this;
    }

    function addMediaItem($mediaItem) {
        if (is_a($mediaItem, 'MediaItemModule')) {
            $this->mediaItems[] = $mediaItem;
        }

        return $this;
    }

    public function addCssClass($var, $class) {
        switch($var) {
            case 'main':
                $this->mediaListCssClass = $class;
                break;
            case 'description':
                $this->mediaListDescriptionCssClass = $class;
                break;
            case 'heading':
                $this->mediaListHeadingCssClass = $class;
                break;
            case 'emptyMessage':
                $this->mediaListEmptyMessageCssClass = $class;
                break;
        }
        return $this;
    }

    public function prepare() {
        $this->hasMediaItems = !empty($this->mediaItems);
        $this->hasEmptyMessage = empty($this->mediaItems);
        $this->hasMoreLink = !empty($this->moreUrl);
        $this->hasMediaListContainer = $this->mediaListDescription || $this->mediaListHeading || $this->moreUrl;

        foreach ($this->mediaItems as $mediaItem) {
            $mediaItem->prepare();
        }

        return true;
    }
}
