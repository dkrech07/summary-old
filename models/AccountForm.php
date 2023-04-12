<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string|null $y_key_id
 * @property string|null $y_secret_key
 *
 */
class AccountForm extends Model
{
  public $y_key_id;
  public $y_secret_key;
  public $bucket_name;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'account';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['y_key_id', 'y_secret_key', 'bucket_name'], 'string'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'y_key_id' => 'Yandex key ID',
      'y_secret_key' => 'Yandex secret Key',
      'bucket_name' => 'Bucket Name',
    ];
  }
}
