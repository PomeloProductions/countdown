<?php

namespace Countdown\Admin\View;
use WordWrap\Assets\View\View;

/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 9/12/15
 * Time: 11:21 PM
 */
class EntryTR extends View{

    public function __construct($lifeCycle, $id, $name) {
        parent::__construct($lifeCycle, "admin/entry_tr");

        $this->setTemplateVar("id", $id);
        $this->setTemplateVar("name", $name);
    }
}