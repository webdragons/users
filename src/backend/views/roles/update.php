<?php

/**
 * @var $this yii\web\View
 * @var $model \bulldozer\users\backend\models\FormRole
 * @var $permissions \yii\rbac\Permission[]
 */

use yii\helpers\Html;

$this->title = 'Редактирование роли: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Редактирование';
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

