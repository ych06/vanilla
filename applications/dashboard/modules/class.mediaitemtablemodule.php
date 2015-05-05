<?php if (!defined('APPLICATION')) exit();

/**
 * A module for a media list.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
class MediaItemTableModule extends MediaItemModule {

    public $rows;
    public $type = 'default';

    // Context for firing events
    public $afterTitleOutput;
    public $beforeContentOutput;


    function __construct($heading = '', $headingUrl = '', $text = '', $id = 'media-item-table') {
        parent::__construct($heading, $headingUrl, $text, $id);
        $this->view = 'mediaitem-table-legacy';
        return $this;
    }

    function addUserCell($username, $userUrl, $postUrl, $time, $imageSource = '', $userFirstOrLast = '', $cssClass = '') {
        $cell = array(
            'isUserCell' => true,
            'userName' => $username,
            'userUrl' => $userUrl,
            'userPostUrl' => $postUrl,
            'userPostTime' => $time,
            'userImageUrl' => $imageSource,
            'userFirstOrLast' => $userFirstOrLast,
            'userCssClass' => $cssClass
            );
        $this->rows[] = $cell;
        return $this;
    }

    function addLastPostCell($title, $url, $username, $userUrl, $time, $imageSource = '', $categoryName = '', $categoryUrl = '', $cssClass = '') {
        $cell = array(
            'isLastPostCell' => true,
            'lastPostTitle' => $title,
            'lastPostUrl' => $url,
            'lastPostUserName' => $username,
            'lastPostUserUrl' => $userUrl,
            'lastPostTime' => $time,
            'lastPostImageUrl' => $imageSource,
            'lastPostCategoryName' => $categoryName,
            'lastPostCategoryUrl' => $categoryUrl,
            'lastPostCssClass' => $cssClass
        );
        $this->rows[] = $cell;
        return $this;
    }

    function addCountCell($count, $cssClass = '') {
        $cell = array(
            'isCountCell' => true,
            'countNumber' => $count,
            'countSectionCssClass' => $cssClass
        );
        $this->rows[] = $cell;
        return $this;
    }

    function addMainCell($headingTag, $cssClass = '') {
        $cell = array(
            'isMainCell' => true,
            'headingTag' => $headingTag,
            'mainCssClass' => $cssClass
        );
        $this->rows[] = $cell;
        return $this;
    }

    public function afterTitleCategoryEvent() {
        if ($this->type == 'Category') {
            Gdn::Controller()->EventArguments['Category'] = $this->row;
            Gdn::Controller()->FireEvent('AfterCategoryTitle');
        }
    }
}
