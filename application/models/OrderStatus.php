<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_title
 * @property string $label
 * @property string $external_id
 * @property integer $edit_allowed
 * @property integer $not_deletable
 * @property string $internal_comment
 */
class OrderStatus extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => \devgroup\TagDependencyHelper\ActiveRecordHelper::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_title'], 'required'],
            [['title'], 'string'],
            [['edit_allowed', 'not_deletable'], 'integer'],
            [['external_id'], 'string', 'max' => 38],
            [['internal_comment', 'short_title', 'label'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['title', 'short_title', 'label', 'external_id', 'internal_comment'],
            'search' => ['id', 'title', 'short_title', 'label', 'external_id', 'edit_allowed', 'not_deletable'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'short_title' => Yii::t('app', 'Short Title'),
            'label' => Yii::t('app', 'Label'),
            'external_id' => Yii::t('app', 'External ID'),
            'edit_allowed' => Yii::t('app', 'Edit Allowed'),
            'not_deletable' => Yii::t('app', 'Not Deletable'),
            'internal_comment' => Yii::t('app', 'Internal Comment'),
        ];
    }
}
