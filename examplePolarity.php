<?php
  include 'lib/Sentiment.php';

  $sentiment = new Sentiment();

  $example1 = $sentiment->score('Today was rubbish'); //Neg
  $example2 = $sentiment->score('Today was amazing'); // Pos
  $example3 = $sentiment->score('Today was ok'); // Neu


?>
<html>
<head>
</head>

<body>
  <h1>phpInsight Example</h1>

  <h2>Example 1</h2>

  <p>Today was rubbish</p>
  <p>Polarity - <?php echo print_r($example1, 1); ?></p>

  <h2>Example 2</h2>

  <p>Today was amazing</p>
  <p>Polarity - <?php echo print_r($example2, 1); ?></p>

  <h2>Example 3</h2>

  <p>Today was ok</p>
  <p>Polarity - <?php echo print_r($example3, 1); ?></p>

</body>
</html>

