<html>
  <head>
    <!--Ally-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
      
      /*Formatting*/
      body 
      {
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        padding: 0;
      }

      /*Hover on nav bar*/
      .topnav a:hover 
      {
        background-color: #D3D3D3;
        color: black;
      }

        /*nav bar*/
        .topnav 
        {
          background-color: black;
          overflow: hidden;
          text-align: center;
          padding: 10px;
        }

        /*style for everything except welcome*/
        .topnav a 
        {
            text-decoration: none;
            color: white;
            padding: 15px 35px;
            display: inline-block;
            font-size: 17px;
        }
    </style>
    </head>


<body>
<div class="topnav">
  <!-- Nav go back to main page or go to checkout-->
        <a href='main_store.php'>Homepage</a>
    </div>
  <?php
    include("secrets.php");

    // start session for user to stay logged in
    session_start();

    try
    {
      $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
      $pdo = new PDO($dsn, $username, $password);

      echo '<h1><center>Login</center></h1>';
      echo '<h2><center>Enter email.<center></h2>';

      echo '<form align="center" method="POST" action="">';

      //Single line textbox to enter email
      echo '<label for="login">Email: </label>';
      echo '<input type ="text" name="login">';
      echo '<input type="submit"/>';

      echo '</form>';

      // if nothing in textbox, enter email.
      if(!isset($_POST['login']))
      {
          echo "<p><center>Email not entered</center></p>";
      }
      else
      {
        $email = $_POST['login'];

        // Queries to find employee/owner/user in database
        $is_employee = "SELECT emp_id FROM Employee WHERE emp_id = (SELECT ID FROM User WHERE email = '$email')";
        $is_owner = "SELECT own_id FROM Owners WHERE own_id = (SELECT ID FROM User WHERE email = '$email')";
        $is_user = "SELECT ID FROM User WHERE email = '$email' AND ID LIKE 'U%'";

        // prepare statements
        $dis_employee = $pdo->prepare($is_employee);
        $dis_owner = $pdo->prepare($is_owner);
        $dis_user = $pdo->prepare($is_user);

        // execute
        $dis_employee->execute();
        $dis_owner->execute();
        $dis_user->execute();

        // row count to check if they exist in database
        $emp_result = $dis_employee->rowCount();
        $own_result = $dis_owner->rowCount();
        $user_result = $dis_user->rowCount();

        // grabbing users name from table based on email entered
        $user_name = $pdo->prepare("SELECT fname FROM User WHERE email = '$email'");
        $user_name->execute();
        $found_name = $user_name->fetch(PDO::FETCH_ASSOC);

        // employee result found
        if ($own_result > 0)
        {
          // This is the owner of the webstore.

          $_SESSION['user'] = 'owner';
          $_SESSION['name'] = $found_name['fname'];
          header("Location: inventory.php");
          exit();
        }
        else if ($emp_result > 0)
        {
          // This is an employee of the webstore
          header("Location: orders.php" );

          $_SESSION['user'] = 'employee';
          $_SESSION['name'] = $found_name['fname'];

          exit();
        }
        else // a regular user
        {
          // User account exists
          if ($user_result > 0)
          {
            // storing info in session
            $_SESSION['user'] = 'customer';
            $_SESSION['name'] = $found_name['fname'];
            $_SESSION['email'] = $email;
            header("Location: main_store.php");
            exit();
          }
          else
          {
            // No user account. Potentially add a create if time.
            echo "<center>Account not found or nothing was entered.</center>";
          }
        }
      }
    }
    catch(PDOexception $e)
    {
      echo "Connection to database failed: " . $e->getMessage();
    }
  ?>
</body>
</html>