<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \bulldozer\users\forms\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('users', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="site-login">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('users', 'Please enter login and password:') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div style="color:#999;margin:1em 0">
                        <?= Yii::t('users', 'If you forget the password you can') ?>
                        <?= Html::a(Yii::t('users', 'restore it'), ['/users/request-password-reset']) ?>.
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('users', 'Log in'),
                            ['class' => 'btn btn-primary', 'name' => 'login-button']
                        ) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>