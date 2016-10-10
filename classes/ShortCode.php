<?php
/**
 * Created by PhpStorm.
 * User: bryce
 * Date: 5/24/15
 * Time: 8:15 PM
 */

namespace Countdown;


use Countdown\Model\Timer;
use stdClass;
use WordWrap\Assets\BaseAsset;
use WordWrap\Assets\Template\Mustache\MustacheTemplate;
use WordWrap\Assets\View\ViewCollection;
use WordWrap\ShortCodeScriptLoader;

class ShortCode extends ShortCodeScriptLoader{

    /**
     * @param  $atts array inputs
     * @return string shortcode content
     */
    public function handleShortcode($atts) {

        if (!isset($atts["id"]))
            $timers = Timer::all();
        else
            $timers = [Timer::find_one($atts["id"])];

        return $timers;
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