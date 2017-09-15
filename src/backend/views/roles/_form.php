<?php

/**
 * @var $this yii\web\View
 * @var $model \bulldozer\users\backend\models\FormRole
 * @var $form yii\widgets\ActiveForm
 * @var $permissions \yii\rbac\Permission[]
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= $form->errorSummary($model) ?>
    </div>
<?php endif ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'codename')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'permissions')->checkboxList(ArrayHelper::map($permissions, 'codename', 'name')) ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('users', 'Create') : Yii::t('users', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    ) ?>
</div>

<?php ActiveForm::end(); ?>