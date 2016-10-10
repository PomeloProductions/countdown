<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/13/15
 * Time: 1:25 AM
 */

namespace Countdown\Admin\View;


use Countdown\Model\Timer;
use WordWrap\Assets\View\ViewCollection;
use WordWrap\LifeCycle;

class CountdownTimerContainer extends ViewCollection {

    /**
     * @param LifeCycle $lifeCycle
     * @param Timer[] $countdowns
     */
    public function __construct(LifeCycle $lifeCycle, $countdowns) {
        parent::__construct($lifeCycle, "countdown_container");

        foreach($countdowns as $countdown) {
            $view = new CountdownTimerTR($this->lifeCycle, $countdown->id, $countdown->title);

            //looks like it's just adding the child view to the parent, shouldn't be crazy.
            $this->addChildView("countdown", $view);
        }

    }
}