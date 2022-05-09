<?php include "../config.php"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the USERS table exists. */
  VerifyUsersTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the USERS table. */
  $user_name = htmlentities($_POST['NAME']);
  $user_cellphone = htmlentities($_POST['CELLPHONE']);

  if (strlen($user_name) || strlen($user_cellphone)) {
    AddUser($connection, $user_name, $user_cellphone);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>CELLPHONE</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="CELLPHONE" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Send" />
      </td>
    </tr>
  </table>
</form>
  
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>ID</td>
      <td>Message</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="ID" maxlength="20" size="30" />
      </td>
      <td>
        <input type="text" name="message" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Send" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>CELLPHONE</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM USERS");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an User to the table. */
function AddUser($connection, $name, $cellphone) {
   $n = mysqli_real_escape_string($connection, $name);
   $c = mysqli_real_escape_string($connection, $cellphone);

   $query = "INSERT INTO USERS (NAME, CELLPHONE) VALUES ('$n', '$c');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyUsersTable($connection, $dbName) {
  if(!TableExists("USERS", $connection, $dbName))
  {
     $query = "CREATE TABLE USERS (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         CELLPHONE VARCHAR(90)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
