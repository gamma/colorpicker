<?php
/**

 *
 * @package    colorpicker
 * @author     Gabriel Birke <birke@d-scribe.de>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
 
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
 
class action_plugin_colorpicker extends DokuWiki_Action_Plugin {
 
  /**
   * return some info
   */
  function getInfo(){
    return array(
     'author' => 'Gabriel Birke',
     'email'  => 'birke@d-scribe.de',
     'date'   => '2007-10-25',
     'name'   => 'Color Picker',
     'desc'   => 'This plugin shows a color picker in the editor toolbar.'. 
                 'The allowed color combinations can be configured',
     'url'    => 'http://www.d-scribe.de/',
     );
  }
 
  /*
   * Register the handlers with the dokuwiki's event controller
   */
  function register(&$controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER',  $this, 'add_button');
  }
 
  /**
   * Parse the color scheme and add the button to the toolbar.
   *
   * The color scheme must at least contain one "colorname=color" pair.
   */
  function add_button(&$event, $param) {
    $colorscheme = $this->getConf('colorscheme');
    $scheme_lines = explode("\n", $colorscheme);
    $color_list = array();
    foreach ($scheme_lines as $line)
    {
      $combo = explode('=', $line, 2);
      if (count($combo) != 2) {
        continue;
      }
      $key = trim($combo[0]);
      $colorvalue = trim(preg_replace('/#.*$/', '', $combo[1])); // Remove comments
      if ($key && $colorvalue) {
        $color_list[$key] = $colorvalue;
      }
    }
    if (!empty($color_list))
    {
      $event->data[] = array(
                  'type'   => 'colorpicker',
                  'title'  => $this->getLang('colorpicker'),
                  'icon'   => 'picker.png',
                  'list'   => $color_list
                  );
    }
  }
  
}
