<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/Interfaces/iFilter.php');

/**
 * Description of Filter
 *
 * @author Andrew Smith
 * @copyright (c) 2013, Florida Museum of Natural History
 * @version 1.0.0
 * @package Filter
 */
class Filter implements iFilter {

    public $session = array();
    public $filterFormID = '';
    public $filtersTable = '';
    public $filters = array();
    public $activatedFilters = array();
    public $newFiltersArray = array();
    public $filterTabs = array();
    public $postFiltersArray = array();
    public $em = '';
    public $where = '';
    public $filterSpan = 'span3';
    public $activeFilterSpan = 'span6';
    public $numberOfColumns = 5;
    public $editWhere = array();
    public $parentFilter = array();
    public $customFilterArray = array();
    private $columnFiltersArray = array();

    public function __contruct() {
        $this->getFilters();
        echo $this->pickFilters();

        if (empty($this->session)) {
            session_destroy();
        }
        if (!empty($this->session)) {
            echo $this->buildHTML();
        }
    }

    function getFilters() {
        if (!empty($this->filters)) {
            foreach ($this->filters as $key => $k) {
                $filterFullName = isset($k['filter']) ? $k['filter'] : '';
                $filterShortName = isset($k['shortName']) ? $k['shortName'] : '';
                $filterType = isset($k['filter_type']) ? $k['filter_type'] : '';

                $this->newFiltersArray[$filterFullName]['displayFilterName'] = $filterFullName;
                $this->newFiltersArray[$filterFullName]['shortFilterName'] = $filterShortName;
                $this->newFiltersArray[$filterFullName]['filter_type'] = $filterType;

                if (!empty($this->filterTabs)) {
                    foreach ($this->filterTabs as $tab => $groupArray) {
                        if (in_array($filterShortName, $groupArray)) {
                            $this->newFiltersArray[$filterFullName]['tab'] = $tab;
                        }
                    }
                }
            }
        }
    }

    public function pickFilters() {
        $html = '';
        $html .= $this->startForm('filterAct');
        $pickList = array();

        if (!empty($this->filters)) {
            foreach ($this->filters as $key => $subKey) {
                $pickList[$subKey['filter']] = $subKey['filter'];
            }
        }

        $html .= $this->classDiv("$this->activeFilterSpan");
        $html .= form_label('<i>Filters for Searching</i>', 'activeFilters');

        $js = "id='activeFilters' class='activeFilters' style='display:none;' multiple='multiple' width='50px'  ";

        $filtered = isset($this->postFiltersArray['activeFilters']) ? TRUE : FALSE;
        if ($filtered) {
            $html .= form_hidden('activeFilters' . "_hidden", $value = '1');
        }

        $html .= form_dropdown('activeFilters', $pickList, (!isset($this->postFiltersArray['activeFilters']) ? '' : $this->postFiltersArray['activeFilters']), $js, $filtered);
        $html .= "  " . form_button('go', 'Add', "id='go' style='vertical-align:top;' class='activateFilters'") . "  ";
        $html .= $this->endDiv();
        $html .= "<br>";
        $html .= $this->classDiv("$this->activeFilterSpan");


        if (empty($this->session)) {
            $html .= form_button('reset', 'Reset', 'id="reset"') . "  ";
            $html .= form_button('all', 'Display All Results', "id='all'  style='vertical-align: top;'");
        } else {
            $html .= form_button('reset', 'Reset', 'id="reset"') . "  ";
            $html .= form_button('all', 'Display All Results', 'id="all"') . "  ";
            $html .= form_button('search', 'Search', "id='search' class='autosubmit'") . "  ";
        }
        $html .= $this->endDiv();
        $html .= $this->endForm() . "<br>";

        return $html;
    }

    public function buildHTML() {
        $html = '';
        $html .= $this->startForm($this->filterFormID);
        $html .= $this->createFilters();
        $html .= $this->rowfluid('filtButtons');

        $html .= $this->endDiv();
        $html .= $this->endForm();
        return $html;
    }

    function removeFilters($array = array()) {
        foreach ($array as $remove) {
            unset($this->filters[$remove]);
        }
    }

    function createFilters() {
        $html = '';
        $fishyFilterArray = array();

        if (isset($this->session['act'])) {
            foreach ($this->session['act'] as $k => $v) {
                foreach ($this->newFiltersArray as $hook => $bait) {
                    if ($hook == $v) {
                        $fishyFilterArray[$v] = $bait;
                    }
                }
            }
        }

        if (!empty($fishyFilterArray)) {
            $i = 0;
            $html .= self::startFilterContainerDiv('filterset');
            foreach ($fishyFilterArray as $key => $column) {
                $displayFilterName = isset($column['displayFilterName']) ? $column['displayFilterName'] : '';
                $shortFilterName = isset($column['shortFilterName']) ? $column['shortFilterName'] : '';
                $showFilter = $this->showFilters($displayFilterName);

                if ((!empty($showFilter))) {

                    if ($i == 0) {
                        $html .= self::rowfluid();
                    }
                    switch ($column['filter_type']) {
                        case 'string':
                        case 'decimal':
                            $html .= $this->dropDownMulti($displayFilterName, $shortFilterName);
                            $html .= form_hidden($shortFilterName . "_history");
                            break;
                        case 'datetime':
                            $html .= $this->dateTime($column);
                            break;
                        case 'integer':
                            $html .= $this->checkbox($column);
                            break;
                        default:
                            $html .= $this->textInput($column);
                            break;
                    }
                    $i++;
                    if ($i == ($this->numberOfColumns)) {
                        $html .= self::endDiv(); //row
                        $i = 0;
                    }
                }
            }
            $html .= self::endDiv(); //filterset container
        }

        if (!empty($this->customFilterArray)) {
            $i = 0;
            $html .= self::startFilterContainerDiv('filterset');
            $html .= self::rowfluid();
            $html .= "<div id='filterTabs' class='container-fluid'>";
            $html .= "<ul>";
            foreach ($this->customFilterArray as $tab => $groupArray) {
                $html .= "<li><a href='#$tab'>$tab</a></li>";
            }
            $html .= "<li><a style='pointer-events: none;cursor: default;font-size: smaller; font-weight: normal;'><i>(Click on Tabs for more options)</i></a></li>";
            $html .= "</ul>";
            foreach ($this->customFilterArray as $tab => $groupArray) {
                $html .= "<div id='$tab' class='customFilterTabs' class='container-fluid'>";
                $max = count($groupArray);
                $j = 0;
                foreach ($groupArray as $column) {
                    $j++;
                    $displayFilterName = isset($column) ? $column : '';
                    $displayFilterName = isset($column) ? $column : '';
                    $showFilter = $this->showFilters($displayFilterName);
                    if ((!empty($showFilter))) {
                        if ($i == 0) {
                            $html .= self::rowfluid();
                        }
                        $html .= $this->dropDownMulti($displayFilterName, $displayFilterName);

                        $i++;
                        if ($i == ($this->numberOfColumns) || $j == $max) {
                            $html .= self::endDiv(); //row
                            $i = 0;
                        }
                    }
                }
                $html .= "</div> <!-- tab$tab -->";
            }
            $html .= "</div> <!-- filterTabs -->";
            $html .= self::endDiv(); //row
            $html .= self::endDiv(); //filterset container
        }
        return $html;
    }

    function textInput($filters) {
        $html = '';
        $displayFilterName = isset($filters['displayFilterName']) ? $filters['displayFilterName'] : '';
        $shortFilterName = isset($filters['shortFilterName']) ? $filters['shortFilterName'] : '';
        $currentValue = (isset($this->postFiltersArray[$shortFilterName][0])) ? $this->postFiltersArray[$shortFilterName][0] : '';

        $data = array(
            'name' => $shortFilterName,
            'id' => $shortFilterName,
            'value' => $currentValue,
            'maxlength' => '50',
            'size' => '50',
            'style' => 'width:50%',
        );

        $html .= self::classDiv("form $this->filterSpan");
        $html .= form_label($displayFilterName, $displayFilterName);
        $html .= form_input($data);
        if (!empty($currentValue)) {
            $html .= form_button("btn_$shortFilterName", 'Undo', "id='$shortFilterName' style='vertical-align: top;'");
        }
        $html .= self::endDiv();
        return $html;
    }

    function dropDownMulti($displayFilterName, $shortFilterName) {
        $html = '';
        $pickList = array();

        $options = $this->showFilters($displayFilterName);

        $html .= self::classDiv("form $this->filterSpan");

        $filtered = isset($this->postFiltersArray[$shortFilterName]) ? TRUE : FALSE;

        $html .= form_label($displayFilterName, $displayFilterName);

        $js = "id='$shortFilterName' class='autoSubmit' style='display:none;' multiple='multiple'";

        if (!empty($options)) {
            foreach ($options as $k => $v) {
                $pickList[$v['value']] = $v['value'];
            }
        }

        natcasesort($pickList);
        if ($filtered) {
            $html .= form_hidden($shortFilterName . "_hidden", $value = '1');
        }

        $html .= form_dropdown($shortFilterName, $pickList, (!isset($this->postFiltersArray[$shortFilterName]) ? '' : $this->postFiltersArray[$shortFilterName]), $js, $filtered);

        $html .= self::endDiv();
        return $html;
    }

    function dropDown($fieldName) {
        $html = '';
        $pickList = array();

        $optionsList = $this->getOptions($fieldName);

        $html .= self::classDiv("form $this->filterSpan");
        $html .= form_label($fieldName, $fieldName);
        $js = "id='$fieldName' class='autoSubmit'";
        foreach ($optionsList as $k => $v) {
            $pickList[$v[$fieldName]] = $v[$fieldName];
        }
        natcasesort($pickList);

        $html .= form_dropdown($fieldName, $pickList, (!isset($this->postFiltersArray[$fieldName]) ? '' : $this->postFiltersArray[$fieldName]), $js);

        $html .= self::endDiv();

        return $html;
    }

    function dateTime($metaArray) {
        $html = '';
        $width = "75px;";
        $html .= self::classDiv("form $this->filterSpan");
        $fieldName = $metaArray['fieldName'];
        $columnName = $metaArray['columnName'];
        $html .= $columnName . "<br>";
        $html .= self::rowfluid();
        $html .= self::classDiv("date $this->filterSpan");
        $html .= form_label("To:", "to_" . $fieldName);
        $toDate = array(
            'name' => "to_" . $fieldName,
            'id' => "to_" . $fieldName,
            'class' => 'datepicker autoSubmit',
            'maxlength' => '30',
            'size' => '30',
            'style' => "width:$width font-style:italic"
        );
        $html .= form_input($toDate, (isset($this->postFiltersArray["to_" . $fieldName]) ? $this->postFiltersArray["to_" . $fieldName] : ''));
        $html .= self::endDiv();

        $html .= self::classDiv("date $this->filterSpan");
        $html .= form_label("From:", "from_" . $fieldName);
        $fromDate = array(
            'name' => "from_" . $fieldName,
            'id' => "from_" . $fieldName,
            'class' => 'datepicker autoSubmit',
            'maxlength' => '30',
            'size' => '30',
            'style' => "width:$width font-style:italic"
        );
        $html .= form_input($fromDate, (isset($this->postFiltersArray["from_" . $fieldName]) ? $this->postFiltersArray["from_" . $fieldName] : ''));
        $html .= self::endDiv();

        $html .= self::endDiv();
        $html .= self::endDiv();
        return $html;
    }

    function checkbox($i = 0) {
        $html = '';
        $html .= self::classDiv("form $this->filterSpan");
        $shortFilterName = isset($this->filters[$i]['shortFilterName']) ? $this->filters[$i]['shortFilterName'] : '';
        $filterName = isset($this->filters[$i]['displayFilterName']) ? $this->filters[$i]['displayFilterName'] : '';

        $data = array(
            'name' => $shortFilterName,
            'id' => $shortFilterName,
            'value' => 'accept',
            'checked' => FALSE,
            'style' => 'margin:10px',
        );

        $html .= form_checkbox($data);

        $html .= $filterName;

        $html .= self::endDiv();
        return $html;
    }

    public function getOptions($column) {
        $query = $this->em->createQuery("SELECT distinct s.value FROM $this->filtersTable s $this->where and s.filter = '$column' ");

        return $query->getArrayResult();
    }

    public function showFilters($column) {
        $query = $this->em->createQuery("SELECT distinct s.value  FROM $this->filtersTable s where s.filter = '$column'"); //where s.$column is not null 

        $filterArray = $query->getArrayResult();

        if (array_key_exists($column, $this->editWhere)) {
            foreach ($filterArray as $key => $value) {
                foreach ($value as $k => $v) {
                    $match = preg_match($this->editWhere[$column], $v);
                    if ($match) {
                        unset($filterArray[$key]);
                    }
                }
            }
        }
        return $filterArray;
    }

    static function startFilterContainerDiv($id = null) {
        $html = '';
        return $html .= "<div id='$id' class='container-fluid'>";
    }

    static function rowfluid($span = NULL) {
        $html = '';
        return $html .= "<div class = 'row-fluid $span'>";
    }

    static function classDiv($css = NULL) {
        $html = '';
        $css = (isset($css)) ? "class = '$css'" : '';
        return $html .= "<div $css>";
    }

    static function endDiv() {
        $html = '';
        return $html .= "</div>";
    }

    public function startForm($formID) {
        $html = '';
        $html .= self::startFilterContainerDiv("{$formID}form");
        $html .= form_open(NULL, array('id' => "{$formID}form"));
        return $html;
    }

    static function endForm() {
        $html = '';
        $html .= form_close();
        $html .= self::endDiv();
        return $html;
    }

}

?>
