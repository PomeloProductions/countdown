<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 8/25/15
 * Time: 5:22 PM
 */

namespace Countdown\Model;


use DateTime;
use WordWrap\ORM\BaseModel;

class Timer extends BaseModel{

    /**
     * @var int the primary id of this timer
     */
    public $id;

    /**
     * @var string the title of this timer
     */
    public $title;

    /**
     * @var string the subtitle of this timer
     */
    public $subtitle;

    /**
     * @var datetime the date and time the countdown timer is ticking towards
     */
    public $countdown_end_time;

    /**
     * Overwrite this in your concrete class. Returns the table name used to
     * store models of this class.
     *
     * @return string
     */
    public static function getTableName(){
        return "countdown_timers";
    }

    /**
     * Get an array of fields to search during a search query.
     *
     * @return array
     */
    public static function getSearchableFields() {
        // TODO: Implement get_searchable_fields() method.
    }

    /**
     * Get an array of all fields for this Model with a key and a value
     * The key should be the name of the column in the database and the value should be the structure of it
     *
     * @return array
     */
    public static function getFields() {
        return [
            "title" => "TEXT",
            "subtitle" => "TEXT",
            "countdown_end_time" => "DATETIME"
        ];
    }

}