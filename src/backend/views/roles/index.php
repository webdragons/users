<?php

/**
 * @var $this yii\web\View
 * @var $searchModel \bulldozer\users\backend\search\RoleSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('users', 'Roles');
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
                <p>
                    <?= Html::a(Yii::t('users', 'Create role'), ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'name',
                            'codename',
                            'description:ntext',
                            'created_at:datetime',
                            'updated_at:datetime',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                            ],
                        ],
                    ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </section>
    </div>
</div>
