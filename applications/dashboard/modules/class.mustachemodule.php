<?php if (!defined('APPLICATION')) exit();

/**
 * A module for all mustache-rendered classes.
 *
 * @author Becky Van Bussel <becky@vanillaforums.com>
 * @copyright 2015 Vanilla Forums, Inc
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @since 2.3
 */
abstract class MustacheModule extends Gdn_Module {

    private $view = '';
    public $helpers;

    function __construct($view) {
        $this->view = $view;

        $this->helpers = array(
            'icon' => function($icon) {
                return '<span class="icon '.$icon.'"></span>';
            },
            't' => function($string) {
                return T($string);
            }
        );

    }

    public function setView($view) {
        $this->view = $view;
    }

    /**
     * Renders menu view
     *
     * @return string
     */
    public function toString() {
        $this->prepare();
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(PATH_APPLICATIONS.'/dashboard/views/modules'),
            'helpers' => $this->helpers
        ));
        return $m->render($this->view, $this);
    }

    abstract function prepare();

}
