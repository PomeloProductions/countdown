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
     * @var datetime the date when the countdown timer was deleted
     */
    public $deleted_at = null;

    /**
     * @var array $dateTimeFields overrides parent $dateTimeFields with countdown timer
     */
    protected $dateTimeFields = ["countdown_end_time", "deleted_at"];

    /**
     * @var array $availableTemplates the mustache templates for this plugins admin section
     */
    public static $availableTemplates = [
        "edit"
    ];

    /**
     * @var string the template the user has chosen
     */
    public $template = "edit";

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
            "countdown_end_time" => "DATETIME",
            "deleted_at" => "DATETIME"
        ];
    }

    /**
     * @return string the template attached to this timer
     */
    public function getTemplate() {

        if ($this->template)
            return $this->template;

        return "edit";
    }

}