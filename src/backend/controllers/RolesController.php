<?php

namespace bulldozer\users\backend\controllers;

use bulldozer\App;
use bulldozer\users\backend\forms\FormRole;
use bulldozer\users\backend\search\RoleSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Role model.
 */
class RolesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['roles_manage'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FormRole();

        $auth = App::$app->authManager;
        $permissions = $auth->getPermissions();

        if ($model->load(App::$app->request->post()) && $model->save()) {
            App::$app->getSession()->setFlash('success', 'Роль успешно добавлена');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'permissions' => $permissions,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate(string $id)
    {
        $auth = App::$app->authManager;

        $permission = $auth->getRole($id);

        if ($permission === null) {
            throw new NotFoundHttpException();
        }

        $model = new FormRole($permission);

        $permissions = $auth->getPermissions();

        if ($model->load(App::$app->request->post()) && $model->save()) {
            App::$app->getSession()->setFlash('success', 'Роль успешно обновлена');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'permissions' => $permissions,
            ]);
        }
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete(string $id)
    {
        $auth = App::$app->authManager;

        $permission = $auth->getRole($id);

        if ($permission === null) {
            throw new NotFoundHttpException();
        }

        $auth->remove($permission);
        App::$app->getSession()->setFlash('success', 'Роль успешно удалена');

        return $this->redirect(['index']);
    }
}
