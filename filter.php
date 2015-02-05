<?php
// This file is part of the lectora filter
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  Media plugin filtering
 *
 *  This filter will replace any links to a media file with
 *  a media plugin that plays that media inline
 *
 * @package    filter
 * @subpackage filter_quizfeedback
 * @copyright  2015 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class filter_quizfeedback extends moodle_text_filter {

    private $endofmodule;

    public $popupcounter = 1;

    public function filter($text, array $options = array()) {
        global $COURSE;

        if ($COURSE->id == 1) {
            return $text;
        }


        //$content = str_replace('{popup}', 'dav', $text);

        // if (stripos($text, 'generalfeedback') === false) {
        //     // Performance shortcut - if not Lectora, do nothing.
        //     return $text;
        // }

        $popup = '/{popup}(.*){\/popup}/Uis';

        $newtext = preg_replace_callback($popup, array($this, 'popup_inject'), $text);

        return $newtext;
    }


    private function popup_inject(array $matches) {

        $content = $matches[1];

        $modal = '
        <!-- Link trigger modal -->
        <a href="#" role="button" data-toggle="modal" class="quizfeedbacklink btn btn-info complete" data-target="#popupdm'.++$this->popupcounter.'" class="">Explain</a>

        <!-- Modal -->
        <div class="modal filtermodal" id="popupdm'.$this->popupcounter.'" tabindex="-1" role="dialog" aria-labelledby="webvideo" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Explanation</h4>
                      </div>
                      <div class="modal-body">
                        '. $content .'
                      </div>
                    </div>
              </div>
        </div>
        ';
        return $modal;

    }

}
