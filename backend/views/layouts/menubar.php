<?php
use common\helpers\CommonHelper;
$adminUrl = CommonHelper::getPath('admin_url');
$user = CommonHelper::getUser();
$arrayMenu = array(
    
        array(
        'title' => 'Dashboard',
        'permissionName' => array(
            'Site.Index',
        ),
        'icon' => 'fa fa-home',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'site'
        ),
        'actionId' => array(
            'index'
        ),
        
        'childs' => array(
            array(
                'title' => 'Dashboard',
                'permissionName' => array(
                    'Site.Index'
                ),
                'icon' => '',
                'link' => '',
                'controllerId' => 'site',
                'actionId' => array('index')
            ),
        )
    ),
    
    array(
        'title' => 'Stores Management',
        'permissionName' => array(
            'Stores.Index',
            'Stores.Create',
            'Market.Index',
            'Market.Create',
            'MarketSegments.Index'
        ),
        'icon' => 'fa fa-bank',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'stores','configs','store-configuration','market-segments','market','market-contacts'
        ),
        'actionId' => array(
           'index', 'create','update','view','review','listing','rules','brands'
        ),
        'childs' => array(
            
             array(
                'title' => 'Market Clusters',
                'permissionName' => array(
                    'MarketSegments.Index'
                ),
                'icon' => '',
                'link' => 'market-segments/index',
                'controllerId' => 'market-segments',
                'actionId' => array('index')
            ),
            
            array(
                'title' => 'Markets',
                'permissionName' => array(
                    'Market.Index'
                ),
                'icon' => '',
                'link' => 'market/index',
                'controllerId' => 'market',
                'actionId' => array('index')
            ),
            
            array(
                'title' => 'Stores',
                'permissionName' => array(
                    'Stores.Index'
                ),
                'icon' => '',
                'link' => 'stores/index',
                'controllerId' => 'stores',
                'actionId' => array('index')
            ),
//            array(
//                'title' => 'Create',
//                'permissionName' => array(
//                    'Stores.Create'
//                ),
//                'icon' => '',
//                'link' => 'stores/create',
//                'controllerId' => 'stores',
//                'actionId' => array('create')
//            ),
        )
    ),
    
    array(
        'title' => 'User Management',
        'permissionName' => array(
            'Users.Index',
//            'Users.Create',
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
                'title' => 'Users',
                'permissionName' => array(
                    'Users.Index'
                ),
                'icon' => '',
                'link' => 'users/index',
                'controllerId' => 'users',
                'actionId' => array('index')
            ),
//            array(
//                'title' => 'Create',
//                'permissionName' => array(
//                    'Users.Create'
//                ),
//                'icon' => '',
//                'link' => 'users/create',
//                'controllerId' => 'users',
//                'actionId' => array('create')
//            ),
        )
    ),
//array(
//        'title' => 'Market Clusters',
//        'permissionName' => array(
//            'MarketSegments.Index',
////            'MarketSegments.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'market-segments'
//        ),
//        'actionId' => array(
//            'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'MarketSegments.Index'
//                ),
//                'icon' => '',
//                'link' => 'market-segments/index',
//                'controllerId' => 'market-segments',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'MarketSegments.Create'
////                ),
////                'icon' => '',
////                'link' => 'market-segments/create',
////                'controllerId' => 'market-segments',
////                'actionId' => array('create')
////            ),
//        )
//    ),
    
//    array(
//        'title' => 'Markets',
//        'permissionName' => array(
//            'Market.Index',
////            'Market.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'market','market-contacts'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view','rules'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Market.Index'
//                ),
//                'icon' => '',
//                'link' => 'market/index',
//                'controllerId' => 'market',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Market.Create'
////                ),
////                'icon' => '',
////                'link' => 'market/create',
////                'controllerId' => 'market',
////                'actionId' => array('create')
////            ),
//        )
//    ),
    
   
    array(
        'title' => 'Product Management',
        'permissionName' => array(
            'Catalogues.Index',
            'Catalogues.Create',
            'Product-Types.Index',
            'Product-Types.Create',
            'Product-Categories.Index',
            'Product-Categories.Create',
            'Brands.Index'
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'catalogues','product-categories','product-types','brands'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Products',
                'permissionName' => array(
                    'Catalogues.Index'
                ),
                'icon' => '',
                'link' => 'catalogues/index',
                'controllerId' => 'catalogues',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Brands',
                'permissionName' => array(
                    'Brands.Index'
                ),
                'icon' => '',
                'link' => 'brands/index',
                'controllerId' => 'brands',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Product Types',
                'permissionName' => array(
                    'Product-Types.Index'
                ),
                'icon' => '',
                'link' => 'product-types/index',
                'controllerId' => 'product-types',
                'actionId' => array('index','update')
            ),
            
            array(
                'title' => 'Product Categories',
                'permissionName' => array(
                    'Product-Categories.Index'
                ),
                'icon' => '',
                'link' => 'product-categories/index',
                'controllerId' => 'product-categories',
                'actionId' => array('index')
            ),
//            array(
//                'title' => 'Create',
//                'permissionName' => array(
//                    'Catalogues.Create'
//                ),
//                'icon' => '',
//                'link' => 'catalogues/create',
//                'controllerId' => 'catalogues',
//                'actionId' => array('create')
//            ),
        )
    ),
    
//    array(
//        'title' => 'Brands',
//        'permissionName' => array(
//            'Brands.Index',
////            'Brands.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'brands'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Brands.Index'
//                ),
//                'icon' => '',
//                'link' => 'brands/index',
//                'controllerId' => 'brands',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Brands.Create'
////                ),
////                'icon' => '',
////                'link' => 'brands/create',
////                'controllerId' => 'brands',
////                'actionId' => array('create')
////            ),
//        )
//    ),
    
    array(
        'title' => 'Others',
        'permissionName' => array(
            'Helps.Index',
            'Helps.Create',
            'Rules.Index',
            'Rules.Create',
            'Questions.Index',
            'Questions.Create',
            'Glossary.Index',
            'Glossary.Create',
            'Help-Categories.Index'
        ),
        'icon' => 'fa fa-user',
        'link' => 'javascript:void(0)',
        'hasChildClass' => 'treeview ',
        'submenuToggleClass' => 'treeview-menu',
        'controllerId' => array(
            'helps','help-categories','rules','questions','glossary'
        ),
        'actionId' => array(
           'index', 'create','update','view'
        ),
        'childs' => array(
            array(
                'title' => 'Help Categories',
                'permissionName' => array(
                    'Help-Categories.Index'
                ),
                'icon' => '',
                'link' => 'help-categories/index',
                'controllerId' => 'help',
                'actionId' => array('categories')
            ), 
            array(
                'title' => 'Rules',
                'permissionName' => array(
                    'Rules.Index'
                ),
                'icon' => '',
                'link' => 'rules/index',
                'controllerId' => 'rules',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Questions',
                'permissionName' => array(
                    'Questions.Index'
                ),
                'icon' => '',
                'link' => 'questions/index',
                'controllerId' => 'quetions',
                'actionId' => array('index')
            ),
            array(
                'title' => 'Glossaries',
                'permissionName' => array(
                    'Glossary.Index'
                ),
                'icon' => '',
                'link' => 'glossary/index',
                'controllerId' => 'glossary',
                'actionId' => array('index')
            ),
        )
    ),
    
//    array(
//        'title' => 'Product Type',
//        'permissionName' => array(
//            'ProductTypes.Index',
////            'ProductTypes.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'product-types'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'ProductTypes.Index'
//                ),
//                'icon' => '',
//                'link' => 'product-types/index',
//                'controllerId' => 'brands',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Brands.Create'
////                ),
////                'icon' => '',
////                'link' => 'product-types/create',
////                'controllerId' => 'brands',
////                'actionId' => array('create')
////            ),
//        )
//    ),
//    array(
//        'title' => 'Product Categories',
//        'permissionName' => array(
//            'Product-Categories.Index',
////            'Product-Categories.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'product-categories'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Product-Categories.Index'
//                ),
//                'icon' => '',
//                'link' => 'product-categories/index',
//                'controllerId' => 'product-categories',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Product-Categories.Create'
////                ),
////                'icon' => '',
////                'link' => 'product-categories/create',
////                'controllerId' => 'product-categories',
////                'actionId' => array('create')
////            ),
//        )
//    ),
//    array(
//        'title' => 'Rules',
//        'permissionName' => array(
//            'Rules.Index',
////            'Rules.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'rules'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Rules.Index'
//                ),
//                'icon' => '',
//                'link' => 'rules/index',
//                'controllerId' => 'rules',
//                'actionId' => array('index')
//            ),
//            array(
//                'title' => 'Create',
//                'permissionName' => array(
//                    'rules.Create'
//                ),
//                'icon' => '',
//                'link' => 'rules/create',
//                'controllerId' => 'rules',
//                'actionId' => array('create')
//            ),
//        )
//    ),
      /* array(
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
    */
//    array(
//        'title' => 'Questions',
//        'permissionName' => array(
//            'Questions.Index',
////            'Questions.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'questions'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Questions.Index'
//                ),
//                'icon' => '',
//                'link' => 'questions/index',
//                'controllerId' => 'quetions',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Questions.Create'
////                ),
////                'icon' => '',
////                'link' => 'questions/create',
////                'controllerId' => 'questions',
////                'actionId' => array('create')
////            ),
//        )
//    ),
//    array(
//        'title' => 'Glossary',
//        'permissionName' => array(
//            'Glossary.Index',
////            'Glossary.Create',
//        ),
//        'icon' => 'fa fa-user',
//        'link' => 'javascript:void(0)',
//        'hasChildClass' => 'treeview ',
//        'submenuToggleClass' => 'treeview-menu',
//        'controllerId' => array(
//            'glossary'
//        ),
//        'actionId' => array(
//           'index', 'create','update','view'
//        ),
//        'childs' => array(
//            array(
//                'title' => 'Listing',
//                'permissionName' => array(
//                    'Glossary.Index'
//                ),
//                'icon' => '',
//                'link' => 'glossary/index',
//                'controllerId' => 'glossary',
//                'actionId' => array('index')
//            ),
////            array(
////                'title' => 'Create',
////                'permissionName' => array(
////                    'Glossary.Create'
////                ),
////                'icon' => '',
////                'link' => 'glossary/create',
////                'controllerId' => 'glossary',
////                'actionId' => array('create')
////            ),
//        )
//    ),
     array(
        'title' => 'Permissions',
        'permissionName' => array(
//            'Permission.Create',
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
//            array(
//                'title' => 'Create',
//                'permissionName' => array(
//                    'Permission.Create'
//                ),
//                'icon' => '',
//                'link' => 'permission/create',
//                'controllerId' => 'permission',
//                'actionId' => array('create')
//            ),
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
    <aside class="main-sidebar sticky">
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