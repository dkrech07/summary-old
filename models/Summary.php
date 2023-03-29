<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "summary".
 *
 * @property int $id
 * @property string $number
 * @property int $status
 * @property string $title
 * @property string|null $detail
 * @property string|null $summary
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Status $status0
 */
class Summary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'status', 'title', 'created_at', 'updated_at'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['number', 'title', 'detail', 'summary'], 'string', 'max' => 256],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'status' => 'Status',
            'title' => 'Title',
            'detail' => 'Detail',
            'summary' => 'Summary',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Status::class, ['id' => 'status']);
    }
}
