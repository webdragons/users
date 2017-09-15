<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \bulldozer\users\frontend\forms\PasswordResetRequestForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('users', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="site-request-password-reset">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('users', 'Please enter your email address. On it we will send a letter with information on password recovery.') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('users', 'Send'), ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>