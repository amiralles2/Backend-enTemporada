<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        $items = [];
        $perfil = [];
        if (Yii::$app->user->isGuest) {
            $perfil[] = '<li>'
                . Html::beginForm(['/site/login'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Login',
                    ['class' => 'btn btn-link log']
                )
                . Html::endForm()
                . '</li>';
        } else {
            $items =
                [
                    ['label' => 'Petición de registro', 'url' => ['/usuarios', "pendiente" => "P"]],
                    ['label' => 'Productos', 'url' => ['/producto']],
                    ['label' => 'Usuarios registrados', 'url' => ['/usuarios', "pendiente" => "A"]],
                    ['label' => 'Recetas', 'url' => ['/recetas', "pendiente" => "A"]],
                    ['label' => 'Recetas pendientes', 'url' => ['/recetas', "pendiente" => "P"]],

                ];


            $perfil[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Salir ( ' . Yii::$app->user->identity->nick . " - " . Yii::$app->user->identity->nombre . " " . Yii::$app->user->identity->apellidos . ' )',
                    ['class' => 'btn btn-link log']
                )
                . Html::endForm()
                . '</li>';
        }
        ?>
        <?php

        NavBar::begin([

            'brandLabel' => "<img class='imagen' src='../IMG/tituloW.png' alt=''>" . Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark barra fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-5 mr-auto mt-2 mt-lg-0'],
            'items' => $items
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav my-lg-0'],
            'items' => $perfil
        ]);
        NavBar::end();
        ?>
    </header>

    <main role="main" class="">
        <div class="container vista mt-5">
            <!-- <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?> -->
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>