<?php
use common\helpers\CommonHelper;
$adminUrl = CommonHelper::getPath('admin_url');
$user = CommonHelper::getUser();
$arrayMenu = array(
    array(
        'title' => 'Permissions',
        'permissionName' => array(
            'Permission.Create',
            'Permission.Matrix-Listing',
            'Permission.Index',
        ),
        'icon' => 'fa fa-edit',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'permission'
        ),
        'actionId' => array(
            'index', 'matrix-listing', 'create'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Permission.Index'
                ),
                'icon' => '',
                'link' => 'permission/index',
                'controllerId' => 'permission',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Permission.Create'
                ),
                'icon' => '',
                'link' => 'permission/create',
                'controllerId' => 'permission',
                'actionId' => array('create')
            ),
            array(
                'title' => 'Matrix',
                'permissionName' => array(
                    'Permission.Matrix-Listing'
                ),
                'icon' => '',
                'link' => 'permission/matrix-listing',
                'controllerId' => 'permission',
                'actionId' => array('matrix-listing')
            ),
        )
    ),
);
?>
<b>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo $adminUrl; ?>theme/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo $user->first_name.' '.$user->last_name; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
          
            <!-- <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form> -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <?php 
                    $controllerId = Yii::$app->controller->id;
                    $actionId = Yii::$app->controller->action->id;

                    foreach ($arrayMenu as $key => $value) { 
                        $parentMenuClass = '';
                        if (in_array($controllerId, $value['controllerId']) && in_array($actionId, $value['actionId'])) {
                            $parentMenuClass = ' active open';
                        }
                        ?>
                        <?php if ((CommonHelper::checkPermission($value['permissionName']))) { ?>
                            <li class="<?php echo $value['hasChildClass'].$parentMenuClass; ?>">
                                <a href="<?php echo $value['link']; ?>">
                                    <i class="fa fa-edit"></i>
                                    <span><?php echo $value['title']; ?></span>
                                    <?php if($value['childs']){ ?>
                                        <span class="pull-right-container">
                                          <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    <?php } ?>
                                </a>
                                <?php if($value['childs']){ ?>
                                    <ul class="treeview-menu">
                                        <?php 
                                            foreach ($value['childs'] as $childKey => $childValue) { 
                                                $childMenuClass = '';
                                                if($parentMenuClass && isset($childValue['actionId']) && isset($childValue['controllerId']) && ($childValue['controllerId'] == $controllerId) && in_array($actionId, $childValue['actionId'])){
                                                    $childMenuClass = 'active';
                                                }
                                                ?>
                                            <?php if((CommonHelper::checkPermission($childValue['permissionName']))) { ?>
                                                <li class="<?php echo $childMenuClass;?>">
                                                    <a href="<?php echo $adminUrl.$childValue['link']; ?>">
                                                        <i class="fa fa-circle-o"></i> <?php echo $childValue['title']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                <?php } ?>
            </ul>
        </section>
    </aside>
</b>