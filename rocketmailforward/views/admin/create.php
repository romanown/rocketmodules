<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model humhub\modules\rocketmailforward\models\MailForward */

$this->title = Yii::t('RocketmailforwardModule.base', 'Create new forwarding rule');
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
