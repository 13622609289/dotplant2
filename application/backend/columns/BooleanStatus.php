<?php

namespace app\backend\columns;

use Yii;
use yii\grid\DataColumn;

class BooleanStatus extends DataColumn
{
    public $header = 'Status';
    
    public $true_value = 'Active';
    public $true_label_class = 'label-success';

    public $false_value = 'Inactive';
    public $false_label_class = 'label-default';

    protected function renderDataCellContent($model, $key, $index)
    {
        $content = parent::renderDataCellContent($model, $key, $index);
        if ($content == "1") {
            return "<span class=\"label ".$this->true_label_class."\">". $this->true_value ."</span>";
        } else {
            return "<span class=\"label ".$this->false_label_class."\">". $this->false_value ."</span>";
        }
    }
}
