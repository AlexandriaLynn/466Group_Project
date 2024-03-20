<html>
  <head>
   <!--Mia-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <style>
      
      /*Formatting*/
      body 
      {
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        padding: 0;
      }

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

        /*Table formatting*/
        table 
        {
            color: black; 
            background-color: white; 
            border-collapse: collapse;
            width: 70%;
            margin-top: 50px;
        }

        table, th, td 
        {
            border: 1px solid black;
        }

        th, td 
        {
            padding: 10px;
            text-align: center;
        }

        th 
        {
            background-color: #D3D3D3; 
        }

</style>
      </head>


<body>
  <div class="topnav">
    <!-- Nav go back to main page-->
    <a href='main_store.php'>Homepage</a>
    <a href='logout.php'>Logout</a>
  </div>

<?php

 // start session
 session_start();


  if(!isset($_SESSION['user'])) //if the user isnt logged in
  {
    echo "<center><h2>Please login to view and change inventory!</h2></center>";
    echo "<center><h4><a href='login.php'>Login</a></h4></center>";
  }
  else if($_SESSION['user'] != 'owner') //if the user isnt the owner
  {
    echo "<center><h3>You're not allowed back here.</h3></center>";
  }
  else
  {

    include("secrets.php"); //this is another php, that has a $username and $password to connect to the db

  try{ //tries to connect to the db
    $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
    $pdo = new PDO($dsn, $username, $password);

    $SELECTprod = $pdo->query('SELECT P.prod_name, P.prod_price, P.prod_type, H.quan_in_stock FROM Product P LEFT JOIN Holds H ON P.prod_id = H.prod_id;'); //gets the prod name, prod price, prod type, and prod quantity
    $ProdsTable = $SELECTprod->fetchAll(PDO::FETCH_ASSOC);
    //table to display a product's name, price, type, and quantity
    echo "<h3><br><center>Products in stock</center></h3>";
    echo "<center>";
    echo "<table border=1>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Type</th><th>Quantity In Stock</th></tr>";
    foreach($ProdsTable as $row)
    {
      if($row['prod_name'] !== 'Def')
      {
        echo "<tr>";
        foreach($row as $val)
        {
          echo "<td>$val</td>";
        }
        echo "</tr>";
      }
    }
    echo "</table>";
    echo "</center>";

  /////////////////////////////// forms
  echo "<form method='POST' action=''>";
  echo "<center>";
  echo "<p>Pick a product to change quantities: ";
  echo "<select name='prod' value='prod'>";
  foreach($ProdsTable as $prods)
  {
    if($prods['prod_name'] !== 'Def')
    {
      echo "<option value='" . $prods['prod_name'] . "'>" . $prods['prod_name'] . "</option>";
    }
  }
  echo "</select></p>";
  echo "</center>";

  echo "<center>";
  echo "<p>Quantity to add/remove: ";
  echo "<input type='text' name='quantity' value='0'/></p>";

  echo "<input type='submit' name='submit'/>"; //submit button
  echo "</form>";
  echo "</center>";
////////////////////////////////

  if(!empty($_POST["prod"]) && $_POST["prod"] != "Def") //checks if the user actually selected something (and isnt def)
  {
    if(!empty($_POST["quantity"]) && $_POST["quantity"] != "0") //checks if the user put in a value that would change the quantity
    {
      $selectedProd = $_POST["prod"];
      $selectedQuan = $_POST["quantity"];

      $quanInStock = $pdo->prepare("SELECT quan_in_stock FROM Holds WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd);"); //get the quantity in stock of the product
      $quanInStock->execute([':selectedProd' => $selectedProd]);
      $currQuan = (int)$quanInStock->fetchColumn();

      if($currQuan + $selectedQuan < 0) //check if the current quantity in stock and the quantity the user wants to add/remove is less than 0
      {
        echo "<center>Not enough quantity in stock.</center>"; //there isnt enough in stock to remove the selected quan from stock
      }
      else //there is enough in stock, update quantity in stock and then print a line saying it was successful
      {
        $updateQuant = $pdo->prepare("UPDATE Holds SET quan_in_stock = quan_in_stock + :selectedQuan WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd);");
        $updateQuant->execute([':selectedQuan' => $selectedQuan, ':selectedProd' => $selectedProd]);
        echo "<center>Product quantity has been updated (refresh the page to see new quantities).</center>";
      }
    }
    else
    {
      echo "<center>No valid quantity inputted.</center>"; //was a 0 or nothing was inputted
    }
  }
 }
 catch(PDOexception $e){ //print an error if it fails to connect
   echo "Connection to database failed: " . $e->getMessage();
 }
}
?>
</body>
</html>