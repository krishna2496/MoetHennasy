<?php

use common\helpers\CommonHelper;

$loginUrl = CommonHelper::getPath('admin_url') . 'site/login';
?>
<table>
    <tbody>        
        <tr>
            <td height="15"></td>
        </tr>
        <tr>
            <td style="font-size: 14px; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif; line-height: 20px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the</td>
        </tr>
        <tr>
            <td height="27">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #d5d5d5;">&nbsp;</td>
        </tr>

        <tr>
            <td style="font-size: 16px; text-align: center; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif; line-height: 20px;">The details of your shelf : </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif;"><img src="<?=$image;?>" alt="shelf image"/></td>
        </tr>        
    </tbody>
</table>