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

class CountdownTimer extends BaseModel{

    /**
     * @var int the primary id of this entry
     */
    public $id;

    /**
     * @var string the title of this entry
     */
    public $title;

    /**
     * @var string the subtitle of this entry
     */
    public $subtitle;

    /**
     * @var datetime the date and time the countdown timer is ticking towards
     */
    public $countdown_end_time;

    /**
     * @var DateTime when the object was deleted
     */
    public $deleted_at = null;

    /**
     * Overrides parent function sets this objects deleted at field to be now, and then saves
     */
    public function delete() {
        $this->deleted_at = new DateTime();

        $this->save();
    }

    /**
     * Overwrite this in your concrete class. Returns the table name used to
     * store models of this class.
     *
     * @return string
     */
    public static function getTableName(){
        return "wp_countdown_timer_entries";
    }

    /**
     * Calls the parent function to install any tables that are needed
     */
    public static function install_table(){
        parent::installTable();
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

    /**
     * @return CountdownTimer[] all countdown timers in the countdown table
     */
    public static function fetchAll(){
        $SQL = "SELECT * FROM `" . static::getTableName() . "` WHERE `deleted_at` IS NULL";

        global $wpdb;
        $rows = $wpdb->get_results($SQL, ARRAY_A);

        $timers = [];
        foreach($rows as $row) {
            $timers[] = new CountdownTimer($row);
        }

        return $timers;
    }
}