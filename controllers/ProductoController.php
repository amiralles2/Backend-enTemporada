<?php

namespace app\controllers;

use Yii;
use FileController;
use yii\web\Controller;
use app\models\Producto;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\ProductoSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * ProductoController implements the CRUD actions for Producto model.
 */
class ProductoController extends Controller
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
     * Lists all Producto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Producto model.
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
     * Creates a new Producto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Producto();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $fileUpload = UploadedFile::getInstance($model, 'eventImage');
                $fileUploadB = UploadedFile::getInstance($model, 'eventImageB');
                if (!empty($fileUpload)) {
                    $model->imagen = $model->nombre . "." . $fileUpload->extension;
                }

                if ($model->save()) {
                    $path = realpath(dirname(getcwd())) . '/../assets/IMG/Articulos/basic/';
                    $pathB = realpath(dirname(getcwd())) . '/../assets/IMG/Articulos/background/';
                    $fileUpload->saveAs($path . $model->imagen);
                    $fileUploadB->saveAs($pathB . $model->imagen);

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Producto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $fileUpload = UploadedFile::getInstance($model, 'eventImage');
            $fileUploadB = UploadedFile::getInstance($model, 'eventImageB');
            if (!empty($fileUpload)) {
                $lastImagen =  $model->imagen;
                $model->imagen = $model->nombre . "." . $fileUpload->extension;

                // SI SE GUARDA CORRECTAMENTE EL MODELO
                if ($model->save()) {
                    $path = realpath(dirname(getcwd())) . '/../assets/IMG/Articulos/basic/';
                    $pathB = realpath(dirname(getcwd())) . '/../assets/IMG/Articulos/background/';
                    // LA LINEA DE ABAJO SIRVE PARA BORRAR EN CASO DE TENER NOMBRES DIFERENTES
                    if (file_exists($path . $lastImagen)) {
                        unlink($path . $lastImagen);
                    }
                    if (file_exists($pathB . $lastImagen)) {
                        unlink($pathB . $lastImagen);
                    }

                    // SUBIMOS LA IMAGEN
                    $fileUpload->saveAs($path . $model->imagen);
                    $fileUploadB->saveAs($pathB . $model->imagen);

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Producto model.
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
     * Finds the Producto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Producto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Producto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
