<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $profile->resultInfo->avatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $profile->resultInfo->fullname ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
        <?php
        if(isset($this->context->module->masterModule)) {
            $masterModule = $this->context->module->masterModule;
        }else{
            $masterModule = $this->context->module;
        }

        $menu1 = [
            ['label' => 'Modules', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'fa fa-dashboard', 'url' => ['/'.$masterModule->id.'/default/index']],
        ];
        $dynamicMenus = [];
        $activeModules = $masterModule->activeModules;
        //print_r($this->context->module->activeModules());
        foreach ($activeModules as $key => $activeModule){
            $dynamicMenus[] = ['label' => $activeModule->title, 'icon' => $activeModule->icon, 'url' => ['/'.$masterModule->id.'/'.$key]];
        }

        $menu2 = [
            ['label' => 'Administrators', 'options' => ['class' => 'header']],
            ['label' => 'Settings', 'icon' => 'fa fa-cog', 'url' => ['/'.$masterModule->id.'/settings']],
            ['label' => 'Module', 'icon' => 'fa fa-cubes', 'url' => ['/'.$masterModule->id.'/module']],
        ];
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                /*'items' => [
                    ['label' => 'User settings', 'options' => ['class' => 'header']],
                    ['label' => 'Dashboard', 'icon' => 'fa fa-dashboard', 'url' => ['/'.$masterModuleId.'/default/index']],
                    \extract($modules, EXTR_PREFIX_SAME),
                    ['label' => 'Settings', 'icon' => 'fa fa-cog', 'url' => ['/'.$masterModuleId.'/settings']],
                    ['label' => 'Modules', 'icon' => 'fa fa-cubes', 'url' => ['/'.$masterModuleId.'/modules']],
                ],*/
                'items' => array_merge($menu1, $dynamicMenus, $menu2)
            ]
        ) ?>

    </section>

</aside>
