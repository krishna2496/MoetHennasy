<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;

/**
 * Base controller
 */
class BaseController extends Controller
{
	public function __construct($id, $module, $config = [])
	{

		parent::__construct($id, $module, $config);	
	}
}
