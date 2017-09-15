<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \bulldozer\users\models\User
 * @var $roles \yii\rbac\Role
 * @var $permissions \yii\rbac\Permission
 */

$this->title = Yii::t('users', 'Update user: {name}', ['name' => $model->email]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'Users'), ['url' => ['index']]];
$this->params['breadcrumbs'][] = $model->email;
$this->params['breadcrumbs'][] = Yii::t('users', 'Update');
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                </div>

                <h2 class="panel-title"><?= Html::encode($this->title) ?></h2>
            </header>

            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'roles' => $roles,
                    'permissions' => $permissions,
                ]) ?>
            </div>
        </section>
    </div>
</div>
