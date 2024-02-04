<?php
function pointDistance3D($x1, $y1, $x2, $y2)
{
    $dist = sqrt(pow($x1-$x2,2) + pow($y1-$y2,2));

    return $dist;
}

function sortArray($arr, $column)
{
    
    $key_values = array_column($arr, $column); 
    array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $arr);
    return $arr;
}


function expToFloat($exp_n)
{
$check_a = substr($exp_n, -3, 1);            /// npr. 3.6E-5
$check_b = substr($exp_n, -4, 1);            /// npr. 3.6E-12
$check_sgn = substr($exp_n, 0, 1);
$sgn=1;

if ($check_sgn=='-')
{
    $sgn = -1;
}
$basic=$exp_n;

if($check_a=='E' || $check_a=='e')
{
      $steps=substr($exp_n, -1, 1);
      $steps = $steps-1;
      $basic=substr($exp_n, 0, -3);
      $basic=str_replace(".", "", $basic);

      for($i=0; $i<$steps; $i++)
      {
        $basic = '0'.$basic;
      }
      $basic = '0.'.$basic;
}
if($check_b=='E' || $check_b=='e')
{
      $steps=substr($exp_n, -2, 2);
      $steps = $steps-1;
      $basic=substr($exp_n, 0, -4);
      $basic=str_replace(".", "", $basic);

      for($i=0; $i<$steps; $i++)
      {
        $basic = '0'.$basic;
      }
      $basic = '0.'.$basic;
}
      $basic = str_replace("-", "", $basic);
      if ($sgn==-1)
      {
      $basic = '-'.$basic;
       }
      
return $basic;
}


function rotateChr($chr, $c_x, $c_y, $ang)       // chr - chromosome; c_x, c_y - center coordinates; ang - angle
{
    echo '<table style="width:100%; border:1px solid"><tr style="width:100%; border:1px solid"><td style="width:45%; border:1px solid">';
    echo 'Input point:&nbsp;'.$chr['x_val'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$chr['y_val'].'<br/>';
    echo '</td><td style="width:45%; border:1px solid">';
    $new_x = cos($ang) * ($chr['x_val']-$c_x) - sin($ang) * ($chr['y_val']-$c_y) + $c_x;
    $new_y = sin($ang) * ($chr['x_val']-$c_x) + cos($ang) * ($chr['y_val']-$c_y) + $c_y;
    $new_z = pow($new_x, 2) + pow($new_y, 2);

    $chr['x_val'] = $new_x;
    $chr['y_val'] = $new_y;
    $chr['z_val'] = $new_z;

    echo 'Output point:&nbsp;'.$chr['x_val'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$chr['y_val'].'<br/>';
    echo '</td><td style="width:10%; border:1px solid">';
    echo 'Angle '.$ang.'deg.';
    echo '</td></tr></table>';
    return $chr;

}
?>