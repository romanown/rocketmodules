<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model humhub\modules\rocketmailforward\models\MailForward */

$this->title = Yii::t(
    'RocketmailforwardModule.base',
    'Update forwarding rule for the user #{user_id}',
    ['user_id' => $model->user_id]
);
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
