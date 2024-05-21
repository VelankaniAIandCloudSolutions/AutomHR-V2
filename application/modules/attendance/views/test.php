<?php 
$date_string = date('Y-m-d', $time);
list($year, $month) = explode('-', $date_string);
$week_number = ceil(date('j', strtotime("last $year-$month")) / 7);
if($week_number == '1')
{
    if(!empty($saturday_weekend_working) && in_array('1', $saturday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else if(!empty($sunday_weekend_working) && in_array('2', $sunday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else{
        echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
    }

}
elseif($week_number == '2')
{
    if(!empty($saturday_weekend_working) && in_array('3', $saturday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else if(!empty($sunday_weekend_working) && in_array('4', $sunday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else{
        echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
    }
}
elseif($week_number == '3')
{
    if(!empty($saturday_weekend_working) && in_array('5', $saturday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else if(!empty($sunday_weekend_working) && in_array('6', $sunday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else{
        echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
    }
}
elseif($week_number == '4')
{
    if(!empty($saturday_weekend_working) && in_array('7', $saturday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else if(!empty($sunday_weekend_working) && in_array('8', $sunday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else{
        echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
    }
}
elseif($week_number == '5')
{
    if(!empty($saturday_weekend_working) && in_array('9', $saturday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else if(!empty($sunday_weekend_working) && in_array('10', $sunday_weekend_working))
    {
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
        echo'<td> - </td>';
    }
    else{
        echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
    }
}

else{
    echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off</td>';
}
?>