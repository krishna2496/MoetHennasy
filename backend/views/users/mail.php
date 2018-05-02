<?php
    use common\helpers\CommonHelper;
    $loginUrl = CommonHelper::getPath('admin_url').'site/login';
?>
<tr>
    <td style="font-size: 30px; font-weight: 700; color: #333333; font-family: 'Open Sans', sans-serif;">Hi [NAME],</td>
</tr>
<tr>
    <td height="15"></td>
</tr>
<tr>
    <td style="font-size: 14px; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif; line-height: 20px;">You have sucessfully registerd. You can now <a href="<?= $loginUrl; ?>" style="color: #00a3d4; text-decoration: none;">log in</a> using below username and password.</td>
</tr>
<tr>
    <td height="27">&nbsp;</td>
</tr>
<tr>
    <td style="border-top: 1px solid #d5d5d5;">&nbsp;</td>
</tr>

<tr>
    <td style="font-size: 16px; text-align: center; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif; line-height: 20px;">The details for accessing your account are: </td>
</tr>
<tr>
    <td height="10"></td>
</tr>
<tr>
    <td style="font-size: 12px; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif;"><b>Username:</b> [USERNAME]</td>
</tr>
<tr>
    <td height="10"></td>
</tr>
<tr>
    <td style="font-size: 12px; color: #333333; font-weight: 400; font-family: 'Open Sans', sans-serif;"><b>Password:</b> [PASSWORD]</td>
</tr>