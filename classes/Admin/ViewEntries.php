<?php
namespace Countdown\Admin;
use Countdown\Admin\View\CountdownTimerContainer;
use Countdown\Model\Timer;
use WordWrap\Admin\TaskController;

/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/12/15
 * Time: 12:47 PM
 */
class ViewEntries extends TaskController {

    private $countdownTimers = [];

    /**
     * override this to setup anything that needs to be done before
     * @param $action null|string the action that is being processed
     */
    public function processRequest($action = null) {
        $this->countdownTimers = Timer::fetchAll();
    }

    /**
     * override to render the main page
     */
    public function renderMainContent() {
        $countdownContainer = new CountdownTimerContainer($this->lifeCycle, $this->countdownTimers);

        return $countdownContainer->export();
    }

    /**
     * override to render the main page
     */
    public function renderSidebarContent() {
        // TODO: Implement renderSidebarContent() method.
    }


}