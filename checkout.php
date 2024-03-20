=<html>
  <head>
    <!--Mia-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
</style>
      </head>


<body>
<div class="topnav">
  <!-- Nav go back to main page or go to checkout-->
        <a href='main_store.php'>Homepage</a>
        <a href='checkout.php'>Checkout</a>
    </div>
 <?php
  session_start();

  include("secrets.php");

  if(!isset($_SESSION['user']) || $_SESSION['user'] != 'customer')
  {
    echo "<center><h2><br><p>Please login to checkout!</p></h2></center>";
    echo "<center><h4><a href='login.php'>Login</a></h4></center>";
  }
  else
  {
    try{
     $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to swithc the zID to your own, so that you dont get an error msg
     $pdo = new PDO($dsn, $username, $password);

     $totalCartPrice = $pdo->prepare("SELECT price_of_prod From InCart WHERE cart_id = (SELECT cart_id From Cart WHERE cust_id = (SELECT ID From User WHERE fname=:name));");
     $totalCartPrice->execute([':name' => $_SESSION["name"]]);
     $totalPrice = $totalCartPrice->fetchAll(PDO::FETCH_ASSOC);
     $addTotal = 0;

     foreach($totalPrice as $row)
     {
       $addTotal = $addTotal + $row['price_of_prod'];
     }
     echo "<h3><center>Your shopping cart total is: $$addTotal</center></h3>";

     if($addTotal == 0)
     {
       echo "<center>There is nothing in your shopping cart.</center>";
     }
     else
     {
///////////////////////////////////////
       echo "<form method='POST' action=''>";

       /////// This is for checkboxes pulling from system. 
       echo "<h4><center><p>Use the address and/or billing information I have on file.</center></p></h4>"; 
       echo "<center>";
       echo "<input type='checkbox' id='add_file' name='add_file' value='address'>";
       echo "<label for='add_file'>Address on File</label><br>";
       echo "<input type='checkbox' id='bill_file' name='bill_file' value='billing'>";
       echo "<label for='bill_file'>Billing Info on File</label><br>";
       echo "</p>";
       echo "</center>";

       // separate submit button to autofill textboxes with default values from system 
       echo "<center>";
       echo "<input type='submit' name='default_vals' value='Use Primary'/>";
       echo "</center>";
       /////// 

       echo "<h3><center>Enter a different shipping address and card number.</center></h3>";
       echo "<center>";
       echo "<p>Shipping Address: ";
       echo "<input type='text' name='ship_addr' value='";

       // if the checkbox is selected for address on file, grab that address from database and echo it into the single line textbox 
       if (isset($_POST['add_file']))
       {
         $user_add = $pdo->prepare("SELECT prim_addr FROM User WHERE fname=:name;");
         $user_add->execute([':name' => $_SESSION["name"]]);
         $addie = $user_add->fetch(PDO::FETCH_ASSOC);

         echo $addie['prim_addr'];
       }

       echo "'/></p>";

       echo "<p>Billing Info: ";
       echo "<input type='text' name='bill_info' value='";

       // if the checkbox is selected for address on file, grab that address from database and echo it into the single line textbox 
       if (isset($_POST['bill_file']))
       {
         $user_bill = $pdo->prepare("SELECT prim_card FROM User WHERE fname=:name;");
         $user_bill->execute([':name' => $_SESSION["name"]]);
         $billie = $user_bill->fetch(PDO::FETCH_ASSOC);

         echo $billie['prim_card'];
       }

       echo "'/></p>";

       echo "<input type='submit' name='submit' value='Confirm Order'/>";
       echo "</form>";
//////////////////////////////////////

      $CustID = $pdo->prepare("SELECT ID FROM User WHERE fname=:name;");
      $CustID->execute([':name' => $_SESSION["name"]]);
      $custID = $CustID->fetch(PDO::FETCH_ASSOC);

      $cID = $pdo->prepare("SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name);");
      $cID->execute([':name' => $_SESSION["name"]]);
      $cartID = (int)$cID->fetchColumn();

      if(empty($_POST["ship_addr"]) || empty($_POST["bill_info"]))
      {
        echo "<br><br>";
        echo "Shipping Address or Billing Info missing.";
      }
      else if(!is_numeric($_POST["bill_info"]) || strlen($_POST["bill_info"]) != 16)
      {
        echo "<br><br>";
        echo "Billing Info is not correct. Must be numeric and 16 digits long.";
      }
      else
      {

        //this updates the Holds table (subtracts whatever product quantities in the cart from the inv)
        $removeInv = $pdo->prepare("SELECT prod_id, quan_of_prod FROM InCart WHERE cart_id = (SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name));");
        $removeInv->execute([':name' => $_SESSION["name"]]);
        $remove = $removeInv->fetchAll(PDO::FETCH_ASSOC);

        foreach($remove as $row)
        {
          $removeProd = $row['prod_id'];
          $removeQuan = $row['quan_of_prod'];

          $updateInv = $pdo->prepare("UPDATE Holds SET quan_in_stock = quan_in_stock - :quantity WHERE prod_id = :prodID;");
          $updateInv->execute([':quantity' => $removeQuan, ':prodID' => $removeProd]);
        }

        $randomNumber = '';
        for ($i = 0; $i < 10; $i++)
        {
          $randomNumber .= rand(0, 9);
        }

        $ship_addr = $_POST["ship_addr"];
        $bill_info = $_POST["bill_info"];

        $updateOrders = $pdo->prepare("INSERT INTO Orders VALUES(:randomNumber, :ship_addr, :bill_info, CURDATE(), 'No notes on this order', 'Processing', 'E1243');");
        $updateOrders->execute([':randomNumber' => $randomNumber, ':ship_addr' => $ship_addr, ':bill_info' => $bill_info]);

        //updates the placing order table
        $placeOrder = $pdo->prepare("INSERT INTO PlacingOrder VALUES(:custID, :cartID, :order_num, NULL, :totalPrice)");
        // boolean success to not clear cart until order has been placed
        $success = $placeOrder->execute([':custID' => $custID['ID'], ':cartID' => $cartID, ':order_num' => $randomNumber, ':totalPrice' => $addTotal]);

        // grab prod details
        $place_order_prod = $pdo->prepare("SELECT InCart.prod_id, Product.prod_name, Product.prod_price, Product.prod_type, Product.genre FROM InCart AS InCart JOIN Product AS Product ON InCart.prod_id = Product.prod_id WHERE InCart.cart_id = (SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name));");

        $place_order_prod->execute([':name' => $_SESSION["name"]]);
        $prod_res = $place_order_prod->fetchAll(PDO::FETCH_ASSOC);

        // if order has been placed successfully, clear the cart and add to place order table 
        if ($success)
        {
          // attempting to insert into the place_order table, but it just generates a new random number. 
          $place_order_prod = $pdo->prepare("SELECT prod_id, quan_of_prod FROM InCart WHERE cart_id = (SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name));");
          $place_order_prod->execute([':name' => $_SESSION["name"]]);
          $prod_res = $place_order_prod->fetchAll(PDO::FETCH_ASSOC);

          foreach ($prod_res as $prod) 
          {
            // insert into ProductOrder the prod_id, order_num, and the amount ordered. 
            $insert_products = $pdo->prepare("INSERT INTO ProductOrder VALUES(:prod_id, :order_num, :quan_in_order);");
            $insert_products->execute([':prod_id' => $prod['prod_id'], ':order_num' => $randomNumber, ':quan_in_order' => $prod['quan_of_prod']]);
          }
          $clearCart = $pdo->prepare("DELETE FROM InCart WHERE cart_id = :cartID AND prod_id != 0");
          $clearCart->execute([':cartID' => $cartID]);  
        }
        echo "<p>Your order has been placed.</p>";
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