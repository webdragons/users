<?php

use bulldozer\users\enums\UserStatusEnum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model \bulldozer\users\models\User
 * @var $roles \yii\rbac\Role
 * @var $permissions \yii\rbac\Permission
 */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->hasErrors()): ?>
        <div class="alert alert-danger">
            <?= $form->errorSummary($model) ?>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'status')->dropDownList(UserStatusEnum::listData(), [
        'prompt' => 'Не выбрано',
    ]) ?>

    <?= $form->field($model, 'roles')->checkboxList(ArrayHelper::map($roles, 'codename', 'name')) ?>

    <?= $form->field($model, 'permissions')->checkboxList(ArrayHelper::map($permissions, 'codename', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
