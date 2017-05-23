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
        if (isset($_POST["timer"]))
            $this->timer->countdown_end_time = $_POST["timer"];

        $this->timer->save();

        header("Location: admin.php?page=countdown&task=view_timers");
    }

    /**
     * override to render the main page
     */
    public function renderMainContent() {

        $view = new View($this->lifeCycle, "admin/timer_edit");

        $view->setTemplateVar("task", $this->task->getSlug());

        $title = "";
        $countdownTimer = "";
        $id = "";

        if(isset($this->timer)) {
            $title = $this->timer->title;
            $countdownTimer = $this->timer->countdown_end_time;
            $countdownTimer = str_replace(' ','T', $countdownTimer);
            $id = "&id=" . $this->timer->id;
        }

        if(isset($_POST["title"]))
            $title = $_POST["title"];
        if(isset($_POST["countdown_end_time"])){
            $countdownTimer = $_POST["countdown_end_time"];
            $countdownTimer = str_replace(' ','T', $countdownTimer);
        }

        $view->setTemplateVar("title", $title);
        $view->setTemplateVar("countdown_end_time", $countdownTimer);

        $view->setTemplateVar("id", $id);

        $view->setTemplateVar("action", $this->action);

        return $view->export();

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