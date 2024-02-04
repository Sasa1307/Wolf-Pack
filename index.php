<html><head><title>Sale&Elena2024</title></head>

<body>
    <table style="width:100%; border:1px solid"><tr style="width:100%; border:1px solid"><td style="width:50%; border:1px solid">
    <form method="post" action="index.php">
        Population:</br>
        <input type="number" name="gen_populat" value="100" step="10" /></br>
        Iterations number:</br>
        <input type="number" name="gen_iterat" value="10" step="10" /></br>
        Mutation probability:</br>
        <input type="number" name="gen_mutat" value="20" step="1" min="0" max="100"/>%</br>
        Rotation angle:</br>
        <input type="number" name="gen_angle" value="59.00" step="0..1" min="0" max="359.99"/>stepeni</br>

        <input type="checkbox" name="ang_fix" value="YES" checked>
        <label for="ang_fix" > Fixed angle for all selected chromosomes</label><br>
        <input type="checkbox" name="ang_rnd" value="YES">
        <label for="ang_rnd"> Random angles</label><br>

        <input type="submit" value="Calculate" />
</form>
</td><td style="width:50%; border:1px solid; text-align:justify">
Instructions<br/><br/>

Three options are possible:<br/>

1. The selected option is "Fixed angle," but not the option "Choose angle randomly" - in this case, the algorithm takes the angle from the form and rotates all randomly selected points by that same fixed angle.</br>
2. Both options are selected - in this case, the algorithm takes a random angle and rotates all selected points by that same angle.<br/>
3. Only the option "Choose angles randomly" is selected - in this case, the algorithm selects a random rotation angle for each of the selected points.</br>
If you do not choose any of the offered options, the algorithm will behave as in case 1.
</td></tr></table>
<?php
include "functional.php";

$limit_radius = 10;
$pop = @$_POST["gen_populat"];
$mut = @$_POST["gen_mutat"];
$ite = @$_POST["gen_iterat"];
$ang = @$_POST["gen_angle"];

$ang_fix = @$POST["ang_fix"];
$ang_rnd = @$POST["ang_rnd"];

$x_chords = array();
$y_chords = array();
$z_values = array();

$chromosome = array();

if($pop!="")
{
    echo '<b>Input sample</b></br>';
    echo '<table style="width:100%; border:1px solid"><tr style="width:100%; border:1px solid"><td style="width:2%; border:1px solid">';
    echo 'n.';
    echo '</td><td style="width:20%; border:1px solid">';
    echo 'X coordinate';
    echo '</td><td style="width:20%; border:1px solid">';
    echo 'Y coordinate';
    echo '</td><td style="width:38%; border:1px solid">';
    echo 'z(x,y)';
    echo '</td></tr>';

for($i=0; $i<$pop; $i++)
{
    $x_chords[$i] = rand(0, ($limit_radius*1000))/1000;
    $y_chords[$i] = rand(0, ($limit_radius*1000))/1000;
    $z_values[$i] = pow($x_chords[$i], 2) + pow($y_chords[$i], 2);                   // funkcija z = x^2 + y^2

    $sgn_x = rand(0, 1);
    if ($sgn_x==0)
    {
        $sgn_x = -1;
    }
    $sgn_y = rand(0, 1);
    if ($sgn_y==0)
    {
        $sgn_y = -1;
    }

    $chromosome[$i]['x_val'] = $x_chords[$i]*$sgn_x;                // sve pakujemo u jedan multidimensional array kako bismo odzali
    $chromosome[$i]['y_val'] = $y_chords[$i]*$sgn_y;               // kompaktibilnost tačaka i njihovih vrijednosti nakon sortiranja po z_val
    $chromosome[$i]['z_val'] = $z_values[$i];

    echo '<tr style="width:100%; border:1px solid"><td style="width:2%; border:1px solid">';
    echo $i+1;
    echo '</td><td style="width:20%; border:1px solid">';
    echo expToFloat($chromosome[$i]['x_val']);
    echo '</td><td style="width:20%; border:1px solid">';
    echo expToFloat($chromosome[$i]['y_val']);
    echo '</td><td style="width:38%; border:1px solid">';
    echo expToFloat($z_values[$i]);
    echo '</td></tr>';
}
 
$chromosome = sortArray($chromosome, 'z_val');
$solution = min($z_values);
echo '</table>';
echo '<table style="width:50%; border:1px solid"><tr style="width:50%; border:1px solid"><td style="width:50%; border:1px solid">';
echo 'Solution: '.expToFloat($solution);
echo '</td></tr></table><br/>';

}

for ($i=0; $i<$ite; $i++)
{
    for($j=1; $j<count($chromosome); $j++)
    {

        $chromosome[$j]['x_val'] = ($chromosome[$j]['x_val'] + $chromosome[0]['x_val'])/2;
        $chromosome[$j]['y_val'] = ($chromosome[$j]['y_val'] + $chromosome[0]['y_val'])/2;
        $chromosome[$j]['z_val'] = pow($chromosome[$j]['x_val'], 2) + pow($chromosome[$j]['y_val'], 2); 
    }

$chromosome = sortArray($chromosome, 'z_val');
$fvals = array_column($chromosome, 'z_val');
$solution = min($fvals);
$k=$i+1;
/////////////////////////// MUTACIJE ////////////////////////////////////
if (!isset($_POST["ang_rnd"]))
{
    for($k=0; $k<round($mut*$pop/100); $k++)
    {
      $ch_num = rand(1, $pop-1);  
      $chromosome[$ch_num] = rotateChr($chromosome[$ch_num], $chromosome[0]['x_val'], $chromosome[0]['y_val'], $ang); 
     
    }
}
if (isset($_POST["ang_rnd"]) && isset($_POST["ang_fix"]))
{ 
    $ang = rand(0, 35999)/100;
    for($k=0; $k<round($mut*$pop/100); $k++)
    {
      $ch_num = rand(1, $pop);  
      $chromosome[$ch_num] = rotateChr($chromosome[$ch_num], $chromosome[0]['x_val'], $chromosome[0]['y_val'], $ang); 
     
    }
}
if (isset($_POST["ang_rnd"]) && !isset($_POST["ang_fix"]))
{ 
    for($k=0; $k<round($mut*$pop/100); $k++)
    {
      $ang = rand(0, 35999)/100;
      $ch_num = rand(1, $pop);  
      $chromosome[$ch_num] = rotateChr($chromosome[$ch_num], $chromosome[0]['x_val'], $chromosome[0]['y_val'], $ang); 
     
    }
}
/////////////////////////////////////////////////////////////////////////


    echo '<b> Results after'.$k.' iterations</b></br>';
    echo '<table style="width:100%; border:1px solid"><tr style="width:100%; border:1px solid"><td style="width:2%; border:1px solid">';
    echo 'n.';
    echo '</td><td style="width:20%; border:1px solid">';
    echo 'X coordinate';
    echo '</td><td style="width:20%; border:1px solid">';
    echo 'Y coordinate';
    echo '</td><td style="width:38%; border:1px solid">';
    echo 'z(x,y)';
    echo '</td></tr>';

    for($j=0; $j<count($chromosome); $j++)
    {
    echo '<tr style="width:10%; border:1px solid"><td style="width:2%; border:1px solid">';
    echo $j+1;
    echo '</td><td style="width:20%; border:1px solid">';
    echo expToFloat($chromosome[$j]['x_val']);
    echo '</td><td style="width:20%; border:1px solid">';
    echo expToFloat($chromosome[$j]['y_val']);
    echo '</td><td style="width:38%; border:1px solid">';
    echo expToFloat($chromosome[$j]['z_val']);
    echo '</td></tr>';
    }

echo '</table>';
echo '<table style="width:50%; border:1px solid"><tr style="width:50%; border:1px solid"><td style="width:50%; border:1px solid">';
echo 'Solution: '.expToFloat($solution);
echo '</td></tr></table></br>';
}
?>





</body>
</html>