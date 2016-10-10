<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/12/15
 * Time: 12:56 PM
 */

namespace countdown\Admin;


use Countdown\Admin\View\CountdownTimerContainer;
use Countdown\Model\Timer;
use WordWrap\Admin\TaskController;
use WordWrap\Assets\View\Editor;
use WordWrap\Assets\View\View;
use WP_Query;

class Edit extends TaskController{

    /**
     * @var Timer the timer that is currently being edited
     */
    private $timer;

    /**
     * @var string the action the user is attempting to carry out
     */
    protected $action = "edit";

    /**
     * override this to setup anything that needs to be done before
     * @param $action null|string the action the is attempting if any
     */
    public function processRequest($action =  null) {
        if(!isset($_GET["id"]) || $_GET["id"] == "")
            wp_redirect("admin.php?page=countdown&task=view");

        $this->timer = Timer::find_one($_GET["id"]);

        if($action)
            $this->handlePost();

    }

    /**
     * By default this will attempt to edit this post
     */
    protected function handlePost() {

        if(!$this->timer)
            $this->timer = Timer::create([]);

        if (isset($_POST["title"]))
            $this->timer->title = $_POST["title"];
        if (isset($_POST["subtitle"]))
            $this->timer->subtitle = $_POST["subtitle"];
        if (isset($_POST["above_timers"]))
            $this->timer->top_content = $_POST["above_timers"];
        if (isset($_POST["below_timers"]))
            $this->timer->bottom_content = $_POST["below_timers"];
        if (isset($_POST["template"]))
            $this->timer->setTemplate($_POST["template"]);
        if (isset($_POST["parent"]) && $_POST["parent"] != "")
            $this->timer->parent_id = $_POST["parent"];

        if (isset($_POST["using_post"]))
            $this->timer->using_post = $_POST["using_post"] == "on";
        else
            $this->timer->using_post = false;

        if (isset($_POST["post_id"]))
            $this->timer->post_id = $_POST["post_id"];

        $this->timer->save();

        if ($this->timer->getParent())
            header("Location: admin.php?page=countdown&task=edit_timer&id=" . $this->timer->getParent()->id);
        else
            header("Location: admin.php?page=countdown&task=view_timers");
    }

    /**
     * override to render the main page
     */
    public function renderMainContent() {

        $view = new View($this->lifeCycle, "admin/timer_edit");

        $view->setTemplateVar("task", $this->task->getSlug());

        $view->setTemplateVar("available_templates", $this->renderAvailableTemplates());
        $view->setTemplateVar("available_posts", $this->renderAvailablePosts());

        $title = "";
        $subtitle = "";
        $id = "";
        $aboveTimers = "";
        $belowTimers = "";
        $usingPost = false;
        $postSelectDisplay = "none";
        $contentEditingDisplay = "block";
        $usingPostDisplay = "block";

        $childrenTimers = [];

        $parent = null;

        if(isset($this->timer)) {
            $title = $this->timer->title;
            $subtitle = $this->timer->subtitle;
            $id = "&id=" . $this->timer->id;
            $aboveTimers = $this->timer->top_content;
            $belowTimers = $this->timer->bottom_content;
            $usingPost = $this->timer->using_post;

            $childrenTimers = $this->timer->getChildren();

            if($this->timer->getParent())
                $parent = $this->timer->getParent()->id;
        }

        if(isset($_POST["title"]))
            $title = $_POST["title"];
        if(isset($_POST["subtitle"]))
            $subtitle = $_POST["subtitle"];
        if(isset($_POST["above_timers"]))
            $aboveTimers = $_POST["above_timers"];
        if(isset($_POST["below_timers"]))
            $belowTimers = $_POST["below_timers"];
        if (isset($_POST["using_post"]))
            $usingPost = $_POST["using_post"] == "on";

        if ($usingPost) {
            $postSelectDisplay = "block";
            $contentEditingDisplay = "none";
        }

        if (isset($this->timer) && $this->timer->template == "nested") {
            $usingPostDisplay = "none";
            $postSelectDisplay = "none";
            $contentEditingDisplay = "none";
        }

        if(isset($_GET["parent_id"]) && $_GET["parent_id"])
            $parent = $_GET["parent_id"];

        $view->setTemplateVar("using_post", $usingPost ? "checked" : "");

        $view->setTemplateVar("post_select_display", $postSelectDisplay);
        $view->setTemplateVar("content_editing_visibility", $contentEditingDisplay);
        $view->setTemplateVar("using_post_visibility", $usingPostDisplay);

        $view->setTemplateVar("title", $title);
        $view->setTemplateVar("subtitle", $subtitle);
        $view->setTemplateVar("id", $id);

        $aboveEditor = new Editor($this->lifeCycle, "above_timers", $aboveTimers, "Above Children Timers");
        $aboveEditor->setHeight(200);
        $view->setTemplateVar("above_timers", $aboveEditor->export());

        $belowEditor = new Editor($this->lifeCycle, "below_timers", $belowTimers, "Below Children Timers");
        $belowEditor->setHeight(200);
        $view->setTemplateVar("below_timers", $belowEditor->export());

        $timersContainer = new CountdownTimerContainer($this->lifeCycle, $childrenTimers, $this->timer);
        $view->setTemplateVar("timers", $timersContainer->export());

        $view->setTemplateVar("action", $this->action);
        if($parent)
            $view->setTemplateVar("parent", $parent);

        return $view->export();

    }

    /**
     * creates the html for the select options of available templates
     * @return string options available
     */
    private function renderAvailableTemplates() {

        $availableTemplates = Timer::$availableTemplates;
        $selectedTemplate = "simple";
        $optionsContent = "";

        if (isset($this->timer))
            $selectedTemplate = $this->timer->getTemplate();
        if (isset($_POST["template"]))
            $selectedTemplate = $_POST["template"];

        if (!in_array($selectedTemplate, $availableTemplates))
            $selectedTemplate = "simple";

        foreach ($availableTemplates as $template) {

            $optionsContent.= "<option ". ($template == $selectedTemplate ? "selected" : "")
                ." value='" . $template ."'>" . $template . "</option>";
        }

        return $optionsContent;
    }

    /**
     * creates the html for the select options of available templates
     * @return string options available
     */
    private function renderAvailablePosts() {

        $args = [
            'post_type' => ['post', 'page'],
            'nopaging' => true,
            'orderby' => 'title',
            'order' => 'ASC',
        ];

        $query = new WP_Query($args);

        $posts = $query->get_posts();

        $availablePosts = [];

        foreach ($posts as $post) {
            $availablePosts[$post->ID] = $post->post_title;
        }

        $selectedPost = 0;
        $optionsContent = "";

        if (isset($this->timer))
            $selectedPost = $this->timer->post_id;
        if (isset($_POST["post_id"]))
            $selectedPost = $_POST["post_id"];


        foreach ($availablePosts as $id => $title) {

            $optionsContent.= "<option ". ($id == $selectedPost ? "selected" : "")
                ." value='" . $id ."'>" . $title . "</option>";
        }

        return $optionsContent;
    }

    /**
     * override to render the main page
     */
    public function renderSidebarContent() {
        // TODO: Implement renderSidebarContent() method.
    }

    /**
     * @return string sets the custom task name for editing this task
     */
    public function getTaskName() {
        $taskName = parent::getTaskName();

        if(isset($this->timer) && $this->timer->id)
            $taskName .= " #" . $this->timer->id;

        return $taskName;
    }
}