<html>
  <head>
    <!--Mia-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
  <!-- Nav go back to main page or go to checkout-->
        <a href='main_store.php'>Homepage</a>
        <a href='checkout.php'>Checkout</a>
    </div>
    <!--Connect to database-->
    <?php

     include("secrets.php");

     // start session for user to stay logged in
     session_start();

     if(!isset($_SESSION['user']) || $_SESSION['user'] != 'customer') //if the user isnt logged in
     {
      echo "<center><h2><br><p>Please login to view your shopping cart!</p></h2></center>";
      echo "<center><h4><a href='login.php'>Login</a></h4></center>";
     }
     else
     {
      try
      {

        $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
        $pdo = new PDO($dsn, $username, $password);

        $Cart = $pdo->query("SELECT P.prod_name, IC.quan_of_prod, IC.price_of_prod FROM InCart IC JOIN Product P ON IC.prod_id = P.prod_id WHERE IC.cart_id = (SELECT C.cart_id FROM Cart C WHERE C.cust_id = (SELECT U.ID FROM User U WHERE U.fname = \"$_SESSION[name]\"));");
        $inCart = $Cart->fetchAll(PDO::FETCH_ASSOC);

        echo "<center>";
        echo "<table border=1>";
        echo "<tr><th>Product Name</th><th>Quantity</th><th>Price of Products</th></tr>";

        foreach($inCart as $row)
        {
            // print if not def 
          if ($row['prod_name'] !== 'Def')
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

       $totalCartPrice = $pdo->prepare("SELECT price_of_prod From InCart WHERE cart_id = (SELECT cart_id From Cart WHERE cust_id = (SELECT ID From User WHERE fname=:name));");
       $totalCartPrice->execute([':name' => $_SESSION["name"]]);
       $totalPrice = $totalCartPrice->fetchAll(PDO::FETCH_ASSOC);
       $addTotal = 0;

       foreach($totalPrice as $row)
       {
         $addTotal = $addTotal + $row['price_of_prod'];
       }
       echo "<h3><center>Your shopping cart total is: $$addTotal</center></h3>";
////////////////////////////////////////
        echo "<form method='POST' action=''>";
        echo "<p><center>Pick a product to change quantity of product: </center>";
        echo "<center>";
        echo "<select name='prod' value='prod'>";
        foreach($inCart as $prods)
        {
           // wont display default product
          if ($prods['prod_name'] !== 'Def')
          {
            echo "<option value'" . $prods['prod_name'] . "'>" . $prods['prod_name'] . "</option>";
          }
        }
        echo "</select></p>";
        echo "</center>";

        echo "<center><p>Quantity to change to (Enter 0 to remove product): </center>";
        echo "<center>";
        echo "<input type='text' name='quantity'/></p>";

        echo "<input type='submit' name='submit'/>";
        echo "</form>";
        echo "</center>";
////////////////////////////////////////

        if(!empty($_POST["prod"]) && $_POST["prod"] != "Def") //checks if the user actually selected something (and isnt def)
        {
          if(!empty($_POST["quantity"]) || $_POST["quantity"] == 0)
          {
            if(is_numeric($_POST["quantity"])) //checks if the user put in a value that would change the quantity
            {
              $selectedProd = $_POST["prod"];
              $selectedQuan = $_POST["quantity"];

              $quanInStock = $pdo->prepare("SELECT quan_in_stock FROM Holds WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd);"); //get the quantity in stock of the product
              $quanInStock->execute([':selectedProd' => $selectedProd]);
              $currQuan = (int)$quanInStock->fetchColumn();

              if($currQuan < $selectedQuan) //check if the current quantity in stock is less than the quantity they want to add/remove
              {
                echo "<center>Not enough product in stock.</center>"; //there isnt enough in stock to add the selected quan into the cart
              }
              else //there is enough in stock, update quantity in cart and then print a line saying it was successful
              {
                $cartID = $pdo->prepare('SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name);');
                $cartID->execute([":name" => $_SESSION["name"]]);
                $cID = (int)$cartID->fetchColumn();
                $priceProd = $pdo->prepare("SELECT prod_price FROM Product WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd);");
                $priceProd->execute(['selectedProd' => $selectedProd]);
                $pProd = (float)$priceProd->fetchColumn();
                $ProdsPrice = $pProd * $selectedQuan;

                if($selectedQuan == 0)
                {
                  $updateQuant = $pdo->prepare("DELETE FROM InCart WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd) AND cart_id=:cID;");
                  $updateQuant->execute([':selectedProd' => $selectedProd, ':cID' => $cID]);
                  echo "<center>Product has been removed from shopping cart (refresh the page to see updated shopping cart).</center>";
                }
                else
                {
                  $updateQuant = $pdo->prepare("UPDATE InCart SET quan_of_prod=:selectedQuan, price_of_prod=:ProdsPrice WHERE prod_id = (SELECT prod_id FROM Product WHERE prod_name=:selectedProd) AND cart_id=:cID;");
                  $updateQuant->execute([':selectedQuan' => $selectedQuan, ':ProdsPrice' => $ProdsPrice, ':selectedProd' => $selectedProd, ':cID' => $cID]);
                  echo "<center>Product quantity has been updated (refresh the page to see new quantities).</center>";
                }
              }
            }
          }
        }
      }
      catch(PDOexception $e)
      {
        echo "Connection to database failed: " . $e->getMessage();
      }
     }
    ?>
</body>
<html>