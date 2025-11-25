<?php

/**
 * @copyright Copyright (c) 2015 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace app\components;

use yii\base\Model;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use nullref\datatable\DataTableColumn;
use nullref\datatable\LinkColumn;

class CustomDataTable extends Widget
{

    const COLUMN_TYPE_DATE = 'date';
    const COLUMN_TYPE_NUM = 'num';
    const COLUMN_TYPE_NUM_FMT = 'num-fmt';
    const COLUMN_TYPE_HTML_NUM = 'html-num';
    const COLUMN_TYPE_HTML_NUM_FMT = 'html-num-fmt';
    const COLUMN_TYPE_STRING = 'string';

    const PAGING_SIMPLE = 'simple';
    const PAGING_SIMPLE_NUMBERS = 'simple_numbers';
    const PAGING_FULL = 'full';
    const PAGING_FULL_NUMBERS = 'full_numbers';
    public $id;

    /**
     * @var array Html options for table
     */
    public $tableOptions = [];

    /**
     * @var array
     */
    public $extraColumns = [];

    public $withColumnFilter;
    /** @var bool */
    public $globalVariable = false;
    protected $_options = [];

    /**
     * @var \yii\data\DataProviderInterface the data provider for the view.
     */
    protected $_dataProvider;

    protected $_extraColumns = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception if ArrayHelper::getValue()
     */
    public function init()
    {
        parent::init();
        if ($this->data === null) {
            $this->data = is_null($this->_dataProvider) ? [] : $this->_dataProvider->getModels();
        }
        CustomDataTableAsset::register($this->getView());
        $this->initColumns();
        $this->initData();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function initColumns()
    {
        $this->_extraColumns = $this->extraColumns;
        if (isset($this->_options['columns'])) {
            $demoObject = $this->getModel();
            foreach ($this->_options['columns'] as $key => $value) {
                if (!is_array($value)) {
                    $value = [
                        'class' => DataTableColumn::class,
                        'attribute' => $value,
                        'label' => $demoObject instanceof \yii\base\Model
                            ? $demoObject->getAttributeLabel($value)
                            : Inflector::camel2words($value)
                    ];
                }
                if (isset($value['type'])) {
                    if ($value['type'] == 'link') {
                        $value['class'] = LinkColumn::className();
                        unset($value['type']);
                    }
                }
                if (!isset($value['class'])) {
                    $value['class'] = DataTableColumn::className();
                }
                if (isset($value['class'])) {
                    $column = \Yii::createObject($value);
                    if ($column instanceof DataTableColumn) {
                        $this->_extraColumns = array_merge($this->_extraColumns, $column->getExtraColumns());
                    }
                    $this->_options['columns'][$key] = $column;
                }
            }
        }
    }

    /**
     * Detect a model class from `dataProvider` or `data` attributes
     *
     * @see \yii\grid\DataColumn::getHeaderCellLabel()
     *
     * return Model|null    NULL is returned when only property $data is defined, and is either empty or first entry is not of type model
     */
    protected function getModel()
    {
        $provider = $this->_dataProvider;
        if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
            /* @var $modelClass Model */
            $modelClass = $provider->query->modelClass;
            $model = $modelClass::instance();
        } elseif ($provider instanceof ArrayDataProvider && $provider->modelClass !== null) {
            /* @var $modelClass Model */
            $modelClass = $provider->modelClass;
            $model = $modelClass::instance();
        } else {
            $models = $this->data;
            //$model = (count($models)) ? $models[0] : null;
            $model = reset($models);
        }
        return $model instanceof Model ? $model : null;
    }

    /**
     * @throws \Exception if ArrayHelper::getValue() throws
     */
    private function initData()
    {
        $this->_extraColumns = array_unique($this->_extraColumns);
        if (array_key_exists('data', $this->_options)) {
            $data = [];

            foreach ($this->_options['data'] as $obj) {
                $row = [];
                foreach ($this->_options['columns'] as $column) {
                    if ($column instanceof DataTableColumn) {
                        if ($column->data) {
                            $value = ArrayHelper::getValue($obj, $column->data);
                            if (($pos = strrpos($column->data, '.')) !== false) {
                                $keys = explode('.', $column->data);
                                $a = $value;
                                foreach (array_reverse($keys) as $key) {
                                    $a = [$key => $a];
                                }
                                $row[$keys[0]] = $a[$keys[0]];
                            } else {
                                $row[$column->data] = $value;
                            }
                        }
                    }
                }
                foreach ($this->_extraColumns as $column) {
                    $row[$column] = ArrayHelper::getValue($obj, $column);
                }
                if ($row) {
                    $data[] = $row;
                }
            }
            $this->_options['data'] = $data;
        }
    }

    public function run()
    {
        $id = isset($this->id) ? $this->id : $this->getId();
        echo Html::beginTag('table', ArrayHelper::merge(['id' => $id], $this->tableOptions));
        echo Html::endTag('table');

        $globalVariable = $this->globalVariable ? "window[$id] =" : '';
        if ($this->withColumnFilter) {
            $encodedParams = Json::encode($this->getParams());
            $js = "
                $.fn.dataTableExt.sErrMode = 'console';
                (function() {
                    var params = {$encodedParams};
                    var table;
                    {$globalVariable} table = jQuery('#{$id}').DataTable(params);
                    var filterRow = jQuery('<tr></tr>');
                    jQuery('#{$id} thead tr th').each(function(i) {
                        var cell = jQuery('<td></td>')
                            .attr('colspan', jQuery(this).attr('colspan'))
                            .attr('class', jQuery(this).attr('class'))
                            .attr('tabindex', jQuery(this).attr('tabindex'))
                            .attr('aria-controls', jQuery(this).attr('aria-controls'))
                            .removeClass('sorting sorting_disabled')
                            .appendTo(filterRow);

                        if (params.columns && params.columns[i] && params.columns[i].renderFilter) {
                            cell.html(jQuery.isFunction(params.columns[i].renderFilter) ? params.columns[i].renderFilter(table) : params.columns[i].renderFilter);
                        }
                    });
                    jQuery('#{$id} thead').append(filterRow);
                    jQuery('#{$id} thead tr:eq(1) td').each(function(i) {
                        jQuery(':input', this).on('keyup change', function() {
                            if (table.column(i).search() !== jQuery(this).val()) {
                                table
                                    .column(i)
                                    .search(jQuery(this).val())
                                    .draw();
                            }
                        });
                    });
                })();
            ";

            $this->getView()->registerJs($js);
        } else {
            $this->getView()->registerJs($globalVariable . 'jQuery("#' . $id . '").DataTable(' . Json::encode($this->getParams()) . ');');
        }
    }

    protected function getParams()
    {
        return $this->_options;
    }

    public function __get($name)
    {
        if ($name == 'dataProvider') {
            return $this->_dataProvider;
        }
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }

    public function __set($name, $value)
    {
        if ($name == 'dataProvider') {
            return $this->_dataProvider = $value;
        }
        return $this->_options[$name] = $value;
    }
}
