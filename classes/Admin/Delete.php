<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/17/15
 * Time: 2:18 AM
 */

namespace Countdown\Admin;


use Countdown\Model\Timer;
use WordWrap\Admin\TaskController;

class Delete extends TaskController {

    /**
     * override this to setup anything that needs to be done before
     * @param $action string the action the user is trying to complete
     */
    public function processRequest($action = null) {
        $id = isset($_GET["id"]) ? $_GET["id"] : false;

        if(!$id)
            header("Location: admin.php?page=countdown&task=view_timers");

        $timer = Timer::find_one($id);

        $timer->delete();

        if($timer->getParent())
            header("Location: admin.php?page=countdown&task=edit_timer&id=" . $timer->getParent()->id);
        else
            header("Location: admin.php?page=countdown&task=view_timers");
    }

    /**
     * override to render the main page
     */
    protected function renderMainContent() { }

    /**
     * override to render the main page
     */
    protected function renderSidebarContent() { }
}