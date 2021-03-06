<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 5/24/15
 * Time: 8:15 PM
 */

namespace Countdown;


use Countdown\Model\Timer;
use DateTime;
use Exception;
use stdClass;
use WordWrap\Assets\BaseAsset;
use WordWrap\Assets\Template\Mustache\MustacheTemplate;
use WordWrap\Assets\View\ViewCollection;
use WordWrap\ShortCodeLoader;

class ShortCode extends ShortCodeLoader{

    /**
     * @param  $atts array inputs
     * @return string shortcode content
     * @throws Exception
     */
    public function onShortCode($atts) {

        if (!isset($atts["id"])){
            throw new Exception('ID not set for Countdown Shortcode');
        }

        $timer = Timer::find_one($atts["id"]);

        if(!$timer){
            throw new Exception('No timers found with that ID');
        }

        $formattedTimerDiffString = $this->formatDate($timer);

        $collection = new MustacheTemplate($this->lifeCycle, 'front_end-entry', ['countdown_end_time' => $formattedTimerDiffString]);

        return $collection->export();
    }

    /**
     * Takes a timer object and returns the amount of time until the countdown timer expires as a human readable string
     *
     * @param $timer
     * @return string
     */
    private function formatDate($timer){
        $endDate = new DateTime($timer->countdown_end_time);
        $currentDate = new DateTime("now");
        $timeLeft = $currentDate->diff($endDate);

        switch(true){
            case $timeLeft->format('%R%y') > 0:

                $countDownTimeLeft = $timeLeft->format('%y') . ' year';

                if($timeLeft->format('%m') > 1){
                    $countDownTimeLeft.= ' ' . $timeLeft->format('%m') .' months ';
                }elseif($timeLeft->format('%m') == 1){
                    $countDownTimeLeft.= ' ' . $timeLeft->format('%m') .  ' month ';
                }

                if($timeLeft->format('%d') > 1){
                    $countDownTimeLeft.= $timeLeft->format('%d'). ' days';
                }elseif($timeLeft->format('%d') == 1){
                    $countDownTimeLeft.= $timeLeft->format('%d'). ' day';
                }

                return $countDownTimeLeft;


            case $timeLeft->format('%R%m') > 0:

                $countDownTimeLeft = $timeLeft->format('%m');

                if($timeLeft->format('%m') > 1){
                    $countDownTimeLeft.= ' months ';
                }else{
                    $countDownTimeLeft.= ' month ';
                }

                if($timeLeft->format('%d') > 1){
                    $countDownTimeLeft.= $timeLeft->format('%d'). ' days';
                }elseif($timeLeft->format('%d') == 1){
                    $countDownTimeLeft.= $timeLeft->format('%d'). ' day';
                }

                return $countDownTimeLeft;


            case $timeLeft->format('%R%d') > 0:

                $countDownTimeLeft = $timeLeft->format('%d');

                if($timeLeft->format('%d') > 1){
                    $countDownTimeLeft.= ' days ';
                }else{
                    $countDownTimeLeft.= ' day ';
                }

                if($timeLeft->format('%H') > 1){
                    $countDownTimeLeft.= $timeLeft->format('%h'). ' hours';
                }elseif($timeLeft->format('%H') == 1){
                    $countDownTimeLeft.= $timeLeft->format('%h'). ' hour';
                }

                return $countDownTimeLeft;


            case $timeLeft->format('%R%H') > 0:
                $countDownTimeLeft = $timeLeft->format('%h');

                if($timeLeft->format('%H') > 1){
                    $countDownTimeLeft.= ' hours ';
                }else{
                    $countDownTimeLeft.= ' hour ';
                }

                if($timeLeft->format('%i') > 1){
                    $countDownTimeLeft.= $timeLeft->format('%i'). ' minutes';
                }elseif($timeLeft->format('%i') == 1){
                    $countDownTimeLeft.= $timeLeft->format('%i'). ' minute';
                }


                return $countDownTimeLeft;


            case $timeLeft->format('%R%i') > 0:

                $countDownTimeLeft = $timeLeft->format('%i');

                if($timeLeft->format('%i') > 1){
                    $countDownTimeLeft.= ' minutes ';
                }else{
                    $countDownTimeLeft.= ' minute ';
                }

                if($timeLeft->format('%s') > 1){
                    $countDownTimeLeft.= $timeLeft->format('%s'). ' seconds';
                }elseif($timeLeft->format('%s') == 1){
                    $countDownTimeLeft.= $timeLeft->format('%s'). ' second';
                }
                return $countDownTimeLeft;


            case $timeLeft->format('%R%s') > 0:
                return ($countDownTimeLeft = $timeLeft->format('%s') . ' seconds');


            default:
                return '';
        }
    }

    /**
     * Example:
     *   wp_register_script('my-script', plugins_url('js/my-script.js', __FILE__), array('jquery'), '1.0', true);
     *   wp_print_scripts('my-script');
     * @return void
     */
    public function addScript() {
        // TODO: Implement addScript() method.
    }

}