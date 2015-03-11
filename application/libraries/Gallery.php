<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/Interfaces/iGallery.php');

/**
 * Description of Gallery
 *
 * @author Andrew Smith
 * @copyright (c) 2013, Florida Museum of Natural History
 * @version 1.0.0
 * @package Gallery
 */
class Gallery { //implements iGallery

    public $results = array();
    public $imgGroup = '';
    public $level1text = '';
    public $level2text = '';
    public $level3text = '';
    public $level4text = '';

    /**
     * Options
     * 
     */
    public $imgSpan = '';
    public $textSpan = '';
    public $textDataSpan = '';
    public $fancyclass = '';
    public $displayImageMetaData = FALSE;
    public $columnFiltersArray = array();
    public $filenameCheck = array();
    public $unsetImageMetaData = array();
    public $addOns = array();
    public $addOnsDisplay = array();

    /**
     * Constructor
     * 
     * @param array $results image array results from Controller
     * @param string $imgspan default NULL
     * @param string $fancyclass default NULL
     * <p>
     * fancybox-effects-a : Title Outside Image<br>
     * fancybox-effects-b : Bottom Left of Image<br>
     * fancybox-effects-c : Bottom Border<br>
     * fancybox-effects-d : No Border, Shadow, Title Center bottom<br>
     * fancybox-buttons : Bottom Border , Image 1 of 10 - Title<br>
     * fancybox-thumbs : Thumbs slider below fancybox, no border, no buttons
     * </p>
     * @param string $displayImageMetaData default FALSE 
     */
    function __construct($results = array(), $imgSpan = NULL, $textSpan = NULL, $textDataSpan = NULL, $fancyclass = NULL, $displayImageMetaData = FALSE) {
        $this->results = (isset($results)) ? $results : array();
        $this->imgSpan = (isset($imgSpan)) ? $imgSpan : 'span2';
        $this->textSpan = (isset($textSpan)) ? $textSpan : 'span2';
        $this->textDataSpan = (isset($textDataSpan)) ? $textDataSpan : 'span2';
        $this->fancyclass = (isset($fancyclass)) ? $fancyclass : 'fancybox-buttons';
        $this->displayImageMetaData = (isset($displayImageMetaData)) ? TRUE : FALSE;
    }

    function getGallery($results, $filtersArray) {
        $html = '';
        $i = 0;
        $html .= self::startContainerDiv('gallery') . self::startRowDiv('results');
        if (!empty($results)) { //rule array minus one
            foreach ($results as $lev1 => $lev2Array) {
                $isGroup = strpos($lev1, '~');

                if ($isGroup) {
                    $groupArray = explode('~', $lev1);
                    $html .= "<h3>";
                    $html .= implode(" ~ ", $groupArray);
                    $html .= "</h3>";
                } else {
                    $html .= "<h3> $this->level1text " . strtoupper($lev1) . "</h3>";
                }
                $html .= self::startRowDiv('resultRow');
                foreach ($lev2Array as $keys => $imageArray) {
                    $html .= self::startRowDiv('imageRowSpan');
                    $html .= $this->generateImage($i, $imageArray, $filtersArray, $lev2Array);
                    $html .= self::endDiv(); //row
                    $i++;
                }
                $html .= self::endDiv();
            }
        } else {
            $html .= $this->generateImage($i, $imageArray, $filtersArray, $lev2Array);
        }
        return $html .= self::endDiv() . self::endDiv();
    }

    function generateImage($i, $imageArray = NULL, $filtersArray, $lev2Array = NULL) {
        if (!empty($imageArray)) {
            $html = '';
            $html .= self::renderImage($i, $imageArray, $filtersArray, $lev2Array);
            return $html;
        } else {
            return "No Image";
        }
    }

    function renderImage($i, $imageArray, $filtersArray, $lev2Array) {
        //printr($imageArray);
        $html = '';

        $html .= $this->getImage($i, $imageArray, $filtersArray, $lev2Array);

        if ($this->displayImageMetaData) {
            $html .= $this->getDisplayImageMetaData($imageArray, $lev2Array);
        }

        return $html;
    }

    function getImage($i, $imageArray, $filtersArray = array(), $lev2Array = array()) {
        $html = '';
        $relation = '';
        $htmlArray = array();

        $title = (isset($imageArray['filename'])) ? "title='" . $imageArray['filename'] . "'" : "title='No Image Title'";
        $alt = (isset($imageArray['alt'])) ? "alt='" . $imageArray['alt'] . "'" : "alt='No Alt Info'";

        if (is_array($imageArray)) {
            $ahref = (isset($imageArray['file'])) ? "href='" . base_url("img/jpg/{$imageArray['file']}") . "'" : FALSE;
            $imgsrc = (isset($imageArray['filename'])) ? "src='" . base_url("img/thumb/{$imageArray['filename']}.png") . "'" : FALSE;

            foreach ($imageArray as $k => $v) {
                switch ($k) {
                    case 'file':
                        $filename = isset($v) ? $v : NULL;
                        break;
                }
            }
        }

        $gotImages = isset($imageArray['Images']) ? FALSE : TRUE;

        if ($gotImages) {
            $html .= "<a data-title-id='title-$i' href='" . base_url('img/no-image-lg.png') . "' class='$this->fancyclass' rel='all' $title >";
            $html .= "<img class='thumbnails imgThumbnail' src='" . base_url('img/no-image-sm.png') . "' align='left' $alt />";
            $html .= "</a>";
            $readMoreArray = $this->getMore($imageArray);
            $html .= "<div id='title-$i' class='hidden'> ";

            foreach ($readMoreArray as $key => $value) {
                switch (strtolower($key)) {
                    case 'genus':
                    case 'species':
                        $html .= "<b>$key:</b> <i>$value</i> ";
                        break;
                    default:
                        $html .= "<b>$key:</b> $value ";
                        break;
                }
                
            }
            $html .= "</div>";
        } else {
            $html .= "<a data-title-id='title-$i' $ahref class='$this->fancyclass' rel='all' $title >";
            $html .= "<img class='thumbnails imgThumbnail' $imgsrc  align='left' $alt />";
            $html .= "</a>";
            $readMoreArray = $this->getMore($imageArray);
            $html .= "<div id='title-$i' class='hidden'> ";

            foreach ($readMoreArray as $key => $value) {
                switch (strtolower($key)) {
                    case 'genus':
                    case 'species':
                        $htmlArray[] = "<b>$key:</b> <i>$value</i> ";
                        break;
                    default:
                        $htmlArray[] = "<b>$key:</b> $value ";
                        break;
                }
                
            }
            $html .= implode(' | ', $htmlArray);
            $html .= "</div>";
        }
        return $html;
    }

    function getDisplayImageMetaData($imageArray, $lev2Array = array()) {
        $html = '';

        $name = (isset($imageArray['name'])) ? "<b>Name:</b>&nbsp;<i>{$imageArray['name']}</i><br>" : '<b>Name:</b> <i>Unknown</i><br>';

        $readMoreArray = $this->getMore($imageArray);

        $html .= self::classDiv('textDataSpan span8');
        $html .= "<ul style='list-style-type: none'>";
        foreach ($readMoreArray as $key => $value) {
            switch (strtolower($key)) {
                    case 'genus':
                    case 'species':
                        $html .= "<li><b>$key:</b> <i>$value</i></li>";
                        break;
                    default:
                        $html .= "<li><b>$key:</b> $value</li>";
                        break;
                }
            
        }
        $html .= "</ul>";
        $html .= "<!--close textDataSpan -->";
        $html .= self::endDiv();
        return $html;
    }

    function getMore($array, $remove = TRUE) {
        $getMoreArray = array();
        foreach ($array as $k => $v) {
            $value = isset($v) ? $v : 'unknown';

            if ($value != 'unknown') {
                $column = (!empty($this->columnFiltersArray[$k]['columnName'])) ? $this->columnFiltersArray[$k]['columnName'] : '';
                $getMoreArray[$column] = $value;
            }

            foreach ($this->addOnsDisplay as $displayType) {
                if ($array['type'] == $displayType) {
                    foreach ($this->addOns as $key => $val) {
                        if ($k == $val) {
                            $getMoreArray[$k] = $value;
                        }
                    }
                }
            }
        }

        if ($remove) {
            foreach ($this->unsetImageMetaData as $un) {
                unset($getMoreArray[$un]);
            }
        }
        return $getMoreArray;
    }

    static function startContainerDiv($class = NULL) {
        $html = '';
        return $html .= "<div class='$class container-fluid'>";
    }

    static function endDiv() {
        $html = '';
        return $html .= "</div>";
    }

    static function startRowDiv($class = NULL) {
        $html = '';
        return $html .= "<div class='$class row-fluid'>";
    }

    function startImgSpanDiv() {
        $html = '';
        return $html .= "<div class = '$this->imgSpan thumbspan'>";
    }

    static function classDiv($css = NULL) {
        $html = '';
        $css = (isset($css)) ? "class = '$css'" : '';
        return $html .= "<div $css>";
    }

}

?>
