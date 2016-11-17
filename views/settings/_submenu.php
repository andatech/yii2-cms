<?php
use yii\helpers\Html;
?>
<?php $this->beginContent('@anda/cms/views/layouts/main.php'); ?>
<?php
$menus = \anda\cms\models\Setting::getTypes();
$request = Yii::$app->request;
?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cog"></i> <?= ucfirst($this->context->id); ?></h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-header no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <?php foreach ($menus as $key => $menu) : ?>
                    <li<?= $this->context->action->id == $key ? ' class="active"' : ''; ?>>
                        <?php
                        $icon = (isset($menu['icon'])) ? '<i class="'.$menu['icon'].'"></i> ' : '';
                        ?>
                        <?= Html::a($icon.ucfirst($menu['label']), [$key]); ?>
                    </li>
                    <?php endforeach; ?>
                    <li><?= Html::a('<i class="fa fa-bolt" aria-hidden="true"></i> Flush Cache', ['flush'])?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title; ?></h3>

                <div class="box-tools pull-right">
                    <!--<div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Search Mail">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>-->
                    <?= (isset($this->params['search-box'])) ? $this->params['search-box'] : ''; ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                    <?php echo $content; ?>
            </div>
            <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
    </div>

</div>

<?php $this->endContent(); ?>
