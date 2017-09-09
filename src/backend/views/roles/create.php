<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \bulldozer\users\backend\models\Role
 * @var $permissions \yii\rbac\Permission[]
 */

$this->title = 'Добавить роль';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                    'permissions' => $permissions,
                ]) ?>
            </div>
        </section>
    </div>
</div>
