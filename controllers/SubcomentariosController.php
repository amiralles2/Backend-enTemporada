<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Subcomentarios;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\SubcomentariosSearch;

/**
 * SubcomentariosController implements the CRUD actions for Subcomentarios model.
 */
class SubcomentariosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
         return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','delete','update', 'index', 'view'],
                'rules' => [
                    ['allow' => true,
                     'actions' => ['create', 'delete', 'update', 'index', 'view'],
                     'matchCallback' => function ($rule, $action) {
                                            if (!Yii::$app->user->isGuest){
                                                return Yii::$app->user->identity->hasRole('A');

                                            }
                                        }
 
                    ],
                    ['allow' => false,
                    'actions' => ['create', 'delete', 'update', 'index', 'view'],
                    'matchCallback' => function ($rule, $action) {
                                           return !Yii::$app->user->isGuest;
                                        }
 
                    ],
 
                ],
            ],
        ];
    }

    /**
     * Lists all Subcomentarios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SubcomentariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subcomentarios model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subcomentarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Subcomentarios();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Subcomentarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subcomentarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subcomentarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Subcomentarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subcomentarios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
