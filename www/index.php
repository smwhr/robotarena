<?php include "controller.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    pre{
      line-height: 18px;
      font-size: 18px;
    }
    .playerA{
      color:red;
    }
    .playerB{
      color:green;
    }
  </style>
  <title>Robot Arena</title>
</head>
<body>
<div class="container">
  <div class="row">
<pre>
<?php 
  list($mx,$my) = $arena->getSize();
  for($y = 0 ; $y < $my ; $y ++){
    for($x = 0 ; $x < $mx ; $x ++){
      echo $arena->charAtPosition($x,$y,true);
    }
    echo "\n";
  }
?>
</pre>
  </div>
  <div class="row">
  <?php 
  $APosition = $arena->robots["A"]["position"];
  $ALife = $arena->robots["A"]["life"];
  ?>
  <h4><span class="playerA">RobotA</span> (<?php echo $ALife;?>) sees :</h4>
<pre>
<?php 
  list($mx,$my) = [5,5];
  
  $surroundA = $arena->charAround($APosition->x, $APosition->y);
  for($y = 0 ; $y < $my ; $y ++){
    for($x = 0 ; $x < $mx ; $x ++){
      echo $surroundA[$y][$x];
    }
    echo "\n";
  }
?>
</pre>
  </div>
  <div class="row">
  <?php 
  $BPosition = $arena->robots["B"]["position"];
  $BLife = $arena->robots["B"]["life"];
  ?>
  <h4><span class="playerB">RobotB</span> (<?php echo $BLife;?>) sees :</h4>
<pre>
<?php 
  list($mx,$my) = [5,5];
  $surroundB = $arena->charAround($BPosition->x, $BPosition->y);
  for($y = 0 ; $y < $my ; $y ++){
    for($x = 0 ; $x < $mx ; $x ++){
      echo $surroundB[$y][$x];
    }
    echo "\n";
  }
?>
</pre>
  </div>
  <div class="row">
    <pre><?php 

  foreach($turn_report as $message){
        echo $message."\n";
  }
?></pre>
  </div>
  <div class="row">
    <form method="POST" action=".">
      <button type="submit" name="RESET" class="btn btn-danger">RESET</button>
    </form>
  </div>
  <!--
  <div class="row">
    <?php //var_dump($arena->robots);?>
  </div>
  -->

</div>


</body>
</html>