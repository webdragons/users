<?php

namespace bulldozer\users\backend\controllers;

use bulldozer\App;
use bulldozer\users\backend\forms\FormUser;
use bulldozer\users\backend\search\UserSearch;
use bulldozer\users\forms\LoginForm;
use bulldozer\users\models\User;
use bulldozer\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * UsersController implements the CRUD actions for User model.
 */
class DefaultController extends Controller
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
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!App::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(App::$app->request->post()) && $model->login()) {
            if (!App::$app->user->can('admin_panel')) {
                App::$app->user->logout(true);
                throw new ForbiddenHttpException();
            } else {
                return $this->goBack();
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        App::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FormUser(null, ['scenario' => 'create']);

        $auth = App::$app->authManager;
        $permissions = $auth->getPermissions();
        $roles = $auth->getRoles();

        if ($model->load(App::$app->request->post()) && $model->save()) {
            App::$app->getSession()->setFlash('success', Yii::t('users', 'User successfully created'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'roles' => $roles,
                'permissions' => $permissions,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $user = $this->findModel($id);

        $model = new FormUser($user);

        $auth = App::$app->authManager;
        $permissions = $auth->getPermissions();
        $roles = $auth->getRoles();

        if ($model->load(App::$app->request->post()) && $model->save()) {
            App::$app->getSession()->setFlash('success', Yii::t('users', 'User successfully updated'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $roles,
                'permissions' => $permissions,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        App::$app->getSession()->setFlash('success', Yii::t('users', 'User successfully deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}