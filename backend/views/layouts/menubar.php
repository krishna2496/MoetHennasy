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
            /*array(
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
            ),*/
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
    
    array(
        'title' => 'User Management',
        'permissionName' => array(
            'Users.Index',
            'Users.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'users'
        ),
        'actionId' => array(
            'index', 'create'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Users.Index'
                ),
                'icon' => '',
                'link' => 'users/index',
                'controllerId' => 'users',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Users.Create'
                ),
                'icon' => '',
                'link' => 'users/create',
                'controllerId' => 'users',
                'actionId' => array('create')
            ),
        )
    ),
array(
        'title' => 'Market Segments',
        'permissionName' => array(
            'MarketSegments.Index',
            'MarketSegments.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'market-segments'
        ),
        'actionId' => array(
            'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'MarketSegments.Index'
                ),
                'icon' => '',
                'link' => 'market-segments/index',
                'controllerId' => 'market-segments',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'MarketSegments.Create'
                ),
                'icon' => '',
                'link' => 'market-segments/create',
                'controllerId' => 'market-segments',
                'actionId' => array('create')
            ),
        )
    ),
    
    array(
        'title' => 'Markets',
        'permissionName' => array(
            'Market.Index',
            'Market.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'market'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Market.Index'
                ),
                'icon' => '',
                'link' => 'market/index',
                'controllerId' => 'market',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Market.Create'
                ),
                'icon' => '',
                'link' => 'market/create',
                'controllerId' => 'market',
                'actionId' => array('create')
            ),
        )
    ),
    
    array(
        'title' => 'Stores',
        'permissionName' => array(
            'Stores.Index',
            'Stores.Create',
        ),
        'icon' => 'fa fa-bank',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'stores','configs'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Stores.Index'
                ),
                'icon' => '',
                'link' => 'stores/index',
                'controllerId' => 'stores',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Stores.Create'
                ),
                'icon' => '',
                'link' => 'stores/create',
                'controllerId' => 'stores',
                'actionId' => array('create')
            ),
        )
    ),
    
    array(
        'title' => 'Catalogue',
        'permissionName' => array(
            'Catalogues.Index',
            'Catalogues.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'catalogues'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Catalogues.Index'
                ),
                'icon' => '',
                'link' => 'catalogues/index',
                'controllerId' => 'catalogues',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Catalogues.Create'
                ),
                'icon' => '',
                'link' => 'catalogues/create',
                'controllerId' => 'catalogues',
                'actionId' => array('create')
            ),
        )
    ),
    array(
        'title' => 'Help',
        'permissionName' => array(
            'Help.Index',
            'Help.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'helps','help-categories'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Categories',
                'permissionName' => array(
                    'HelpCategories.Index'
                ),
                'icon' => '',
                'link' => 'help-categories/index',
                'controllerId' => 'help',
                'actionId' => array('categories')
            ), 
        )
    ),
    array(
        'title' => 'Brands',
        'permissionName' => array(
            'Brands.Index',
            'Brands.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'brands'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Brands.Index'
                ),
                'icon' => '',
                'link' => 'brands/index',
                'controllerId' => 'brands',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Brands.Create'
                ),
                'icon' => '',
                'link' => 'brands/create',
                'controllerId' => 'brands',
                'actionId' => array('create')
            ),
        )
    ),
    array(
        'title' => 'Product Categories',
        'permissionName' => array(
            'Product-Categories.Index',
            'Product-Categories.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'product-categories'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Product-Categories.Index'
                ),
                'icon' => '',
                'link' => 'product-categories/index',
                'controllerId' => 'product-categories',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Product-Categories.Create'
                ),
                'icon' => '',
                'link' => 'product-categories/create',
                'controllerId' => 'product-categories',
                'actionId' => array('create')
            ),
        )
    ),
    array(
        'title' => 'Rules',
        'permissionName' => array(
            'Rules.Index',
            'Rules.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'rules'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Rules.Index'
                ),
                'icon' => '',
                'link' => 'rules/index',
                'controllerId' => 'rules',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'rules.Create'
                ),
                'icon' => '',
                'link' => 'rules/create',
                'controllerId' => 'rules',
                'actionId' => array('create')
            ),
        )
    ),
        array(
        'title' => 'Ratings',
        'permissionName' => array(
            'Ratings.Index',
            'Ratings.Create',
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'ratings'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Listing',
                'permissionName' => array(
                    'Ratings.Index'
                ),
                'icon' => '',
                'link' => 'ratings/index',
                'controllerId' => 'ratings',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Create',
                'permissionName' => array(
                    'Ratings.Create'
                ),
                'icon' => '',
                'link' => 'ratings/create',
                'controllerId' => 'ratings',
                'actionId' => array('create')
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
                    <img src="<?php echo CommonHelper::getImage(UPLOAD_PATH_USER_IMAGES . $user->profile_photo); ?>" class="img-circle" alt="User Image">
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
                                    <i class="<?php echo $value['icon']; ?>"></i>
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