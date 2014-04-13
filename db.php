<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dkasyanov
 * Date: 4/11/14
 * Time: 2:53 PM
 * To change this template use File | Settings | File Templates.
 */

include 'conf.php';
//if(session_id() == '')
		session_start();

if (isset($_POST['exit']))
{
    session_destroy();
    header("Location: index.html");
    exit(0);
}


if(isset($_GET) && isset($_SESSION['login']))
{
    $result = mysqli_query($con, "SELECT * FROM dictionary WHERE `user_id`=(SELECT users_id FROM users WHERE users_login='".mysqli_real_escape_string($con, $_SESSION['login'])."') LIMIT 1");
    $i=1;
    while($row = mysqli_fetch_array($result))
      {
      echo "<tr>";
      echo "<td>" . $i . "</td>";
      echo "<td>" . $row['word'] . "</td>";
      echo "<td>" . $row['word_type'] . "</td>";
      echo "<td>" . $row['transcription'] . "</td>";
      echo "<td>" . $row['word_translation'] . "</td>";
      echo "<td>" . $row['phrase'] . "</td>";
      echo "<td>" . $row['phrase_translation'] . "</td>";
      echo "</tr>";
      $i += 1;
      }
      exit(0);
}
exit(1);
?>
