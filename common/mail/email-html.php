<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\CommonHelper;

$baseUrl = CommonHelper::getPath('admin_url');
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8"> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <title>Workflow Manager</title> 
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

  <style>
    html,
    body {margin:0 auto !important; padding: 0 !important; height: 100% !important;width: 100% !important; font-family: 'Open Sans', sans-serif; font-weight: 400;}
    img {display: block; margin: 0; padding: 0;}
    input:focus { box-shadow: none !important; outline: none}

    @media screen and (max-width: 650px) {
      body{padding: 0 20px !important;box-sizing: border-box}
      table { width: 100% !important;table-layout: fixed; }
    }

  </style>

</head>
<body width="100%" style="margin: 0; mso-line-height-rule: exactly;">
  
  <table cellspacing="0" cellpadding="0" border="0" align="center" width="650">
    <tr>
      <td>
        <table cellspacing="0" cellpadding="0" border="0" align="center" width="545">
          <tr>
            <td height="40">&nbsp;</td>
          </tr>
          <tr>
            <td><a style="text-decoration:none;" href="<?= $baseUrl; ?>"><img src="<?=$baseUrl.'images/logo-MH.jpg'?>" alt=""></a></td>
          </tr>
          <tr>
            <td height="7"></td>
          </tr>
          <tr>
            <td style="height: 5px; border-top: 5px solid #947549">&nbsp;</td>
          </tr>
          <tr>
            <td height="24">&nbsp;</td>
          </tr>
          <?= $content ?>
          <tr>
            <td height="10">&nbsp;</td>
          </tr>
          <tr>
            <td style="border-bottom: 1px solid #d5d5d5;">&nbsp;</td>
          </tr>
          <tr>
            <td height="10"></td>
          </tr>
          <tr>
            <td  style="font-size: 14px; color: #8c8c8c; font-weight: 400; font-family: 'Open Sans', sans-serif;">© 2018 - DIYS by ODiTY.</td>
          </tr>
          
          <tr>
            <td height="40">&nbsp;</td>
          </tr>

          <tr>
            <td height="40">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

</body>
</html>