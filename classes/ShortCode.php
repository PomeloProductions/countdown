<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 5/24/15
 * Time: 8:15 PM
 */

namespace Countdown;


use Countdown\Model\Timer;
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

        $timerCollection = $this->buildTimer($timer);

        return $timerCollection->export();
    }

    private function buildTimer($timer){

        $timerCollection = $this->buildTimerTemplate($timer);

        return $timerCollection;
    }

    private function buildTimerTemplate($timer){
        $collection = new ViewCollection($this->lifeCycle, 'front_end-entry', 'mustache');

        $collection->setTemplateVar("title", $timer->title);
        $collection->setTemplateVar("countdown_end_time", $timer->countdown_end_time);

        return $collection;
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