<!DOCTYPE html>
<html>
<head>
 <title><?php echo lang('offer_letter') ?></title>
</head>
<body>
 
 
<h1><?php echo lang('offer_letter') ?></h1>
<table style="border:1px solid red;width:100%;">
 <tr>
 <th style="border:1px solid red">Id</th>
 <th style="border:1px solid red">Name</th>
 <th style="border:1px solid red">job name</th>
 </tr>
 <tr>
 <td style="border:1px solid red">1</td>
 <td style="border:1px solid red"><?php echo $user_data['first_name'].' '.$user_data['last_name'];?></td>
 <td style="border:1px solid red"><?php echo $job_details['job_title'];?></td>
 </tr>

</table>
 
 
</body>
</html>