<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/13/15
 * Time: 1:25 AM
 */

namespace Countdown\Admin\View;


use Countdown\Model\CountdownTimer;
use WordWrap\Assets\View\ViewCollection;
use WordWrap\LifeCycle;

class CountdownTimerContainer extends ViewCollection {

    /**
     * @param LifeCycle $lifeCycle
     * @param CountdownTimer[] $countdowns
     */
    public function __construct(LifeCycle $lifeCycle, $countdowns) {
        parent::__construct($lifeCycle, "countdown_container", 'mustache');

        foreach($countdowns as $countdown) {
            $view = new CountdownTimerTR($this->lifeCycle, $countdown->id, $countdown->title);

            //no idea what this is doing
            $this->addChildView("countdowns", $view);
        }
    }
}