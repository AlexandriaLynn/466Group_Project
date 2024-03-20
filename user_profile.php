<html>
  <head>
    <!--Ally-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
      
      /* This is all the CSS stuff for formatting. Further documentation on each stuff is on orders.php, but it's kind of self explanatory. */
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

        /*Tables*/
        table 
        {
            color: black; 
            background-color: white; 
            border-collapse: collapse;
            width: 70%;
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
   
  <!--Setting up nav-->
  <div class="topnav">
      <a href='main_store.php'>Homepage</a>
  </div>

  <?php

    include("secrets.php");

    // starting session
    session_start();

    if(!isset($_SESSION['user']) || $_SESSION['user'] != 'customer') //if the user hasnt logged in or its not a customer
    {
      echo "<h2><br><center>Please login to view your profile.</center></h2>";
      echo "<center><h4><a href='login.php'>Login</a></h4></center>";
    }
    else
    {
      $email = $_SESSION['email'];
      try
      {
        $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
        $pdo = new PDO($dsn, $username, $password);
        $profile = $pdo->query("SELECT * FROM User WHERE email = '$email'");

        $profile_table = $profile->fetchAll(PDO::FETCH_ASSOC);
        echo "<h2><p><center>Account Information</center></p></h2>";
        echo "<center>";
        echo "<table border=1>";
        echo "<tr><th>Account ID</th><th>First Name</th><th>Last Name</th><th>Address</th><th>Billing Info</th><th>Email</th></tr>";

        foreach($profile_table as $row)
        {
            echo "<tr>";

            foreach($row as $val)
            {
                echo "<td>$val</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
        echo "<center>";


    // sql statement to grab orders. uses placingorder and orders 
    $orders_placed = $pdo->query("SELECT PlacingOrder.order_num, PlacingOrder.tracking_num, PlacingOrder.total_cart_price, User.fname, Orders.ship_addr, Orders.billing_info, Orders.date_placed, Orders.note, Orders.order_status FROM PlacingOrder JOIN User ON PlacingOrder.cust_id = User.ID JOIN Orders ON PlacingOrder.order_num = Orders.order_num WHERE User.email = '$email'");
    $orders_table = $orders_placed->fetchAll(PDO::FETCH_ASSOC);

    // print table from combining PlacingOrder and Order SQL table. this does not include defaults. statements because things like tracking #, status, and note print differently based what's present in table
    echo "<h2><p><center>Orders</center></p></h2>";
    echo "<center>";
    echo "<table border=1>";
    echo "<tr><th>Order Number</th><th>Notes</th><th>Tracking</th><th>Total Price</th><th>Address</th><th>Billing Info</th><th>Date Placed</th><th>Order Status</th></tr>";
    foreach ($orders_table as $row) 
    {
        if ($row['order_num'] != '0')
        {
            echo "<tr>";
            echo "<td>" . $row['order_num'] . "</td>";
            
            if ($row['note'] == 'No notes on this order')
            {
              echo "<td>---</td>";
            }
            else 
            {
              echo "<td>" . $row['note'] . "</td>";
            }
            // if the order hasnt been fufilled yet (so theres no tracking num) the tracking number box displays as Processing. 
            if ($row['tracking_num'] == NULL) 
            {
                echo "<td>Not Shipped</td>";
            } 
            else 
            {
                echo "<td>" . $row['tracking_num'] . "</td>";
            }
    
            echo "<td> $" . $row['total_cart_price'] . "</td>";
            echo "<td>" . $row['ship_addr'] . "</td>";
            echo "<td>" . $row['billing_info'] . "</td>";
            echo "<td>" . $row['date_placed'] . "</td>";

            if ($row['order_status'] == 'Fulfilled')
            {
                echo "<td>Shipped</td>";
            }
            else if ($row['order_status'] == 'Processing')
            {
                echo "<td>Not Shipped</td>";
            }
            echo "</tr>";
        }
    }

    echo "</table>";
    echo "</center>";

    // sql for total price spent on ALL orders
    $orders_price = $pdo->prepare("SELECT SUM(PlacingOrder.total_cart_price) FROM PlacingOrder JOIN User ON PlacingOrder.cust_id = User.ID WHERE User.email = :email");
    $orders_price->execute([':email' => $email]);
    $order_price_res = $orders_price->fetch(PDO::FETCH_ASSOC);
    
    // display amount spent on orders
    if ($order_price_res['SUM(PlacingOrder.total_cart_price)'] == 0)
    {
      echo "<h3> No Orders Placed </h3>";
    }
    else
    {
      echo "<h3>Total amount spent on all orders: $" . $order_price_res['SUM(PlacingOrder.total_cart_price)'] . "</h3>";
    }
    
    ///// Form to drop down, select order number, view contents and select that order 
    echo "<form method='POST' action=''";
    echo "<p>";
    echo "<h3>Select an Order to view contents: </h3>";
    echo "<label for 'past_orders'>Choose Order:</label>";
    echo "<select name='past_orders' id='past_orders'>";
    echo "option value='select'>Orders</option>'";
    
    // grab orders nums from database 
    $orders_view = "SELECT PlacingOrder.order_num from PlacingOrder Join User on PlacingOrder.cust_id = User.ID WHERE User.email =:email";
    $orders_drop = $pdo->prepare($orders_view);
    $orders_drop->execute([':email' => $_SESSION["email"]]);

    // drop down for viewing order
    while ($row = $orders_drop->fetch(PDO::FETCH_ASSOC))
    {
        if ($row['order_num'] !== '0')
        {
            echo "<option value=" . $row['order_num'] . ">" . $row['order_num'] . "</option>";
        }
    }
    echo "</select>";
    echo "</p>";

    echo "<input type ='submit' value='View Contents'/>";
    echo "</form>";

    // grab from product order table and display
    if(isset($_POST['past_orders']))
    {
      $past_orders = $_POST['past_orders'];
      $grab_orders = $pdo->prepare("SELECT ProductOrder.*, Product.* FROM ProductOrder JOIN Product ON ProductOrder.prod_id = Product.prod_id WHERE ProductOrder.order_num = :past_orders");
      $grab_orders->execute([':past_orders' => $past_orders]);
      $display_orders = $grab_orders->fetchAll(PDO::FETCH_ASSOC);

      echo "<br>";
      echo "<h3><p>Order Information</p></h3>";
      echo "<table border=1>";
      echo "<tr><th>Name</th><th>Quantity Ordered<th>Price</th><th>Type</th><th>Genre</th></tr>";

      $total_order_cost = 0;

      foreach($display_orders as $row)
      {
        echo "<tr>";
    
        echo "<td>" . $row['prod_name'] . "</td>";
        echo "<td>" . $row['quan_in_order'] . "</td>";
        echo "<td> $" . $row['prod_price'] . "</td>";
        echo "<td>" . $row['prod_type'] . "</td>";
        echo "<td>" . $row['genre'] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
      
      // generate total_cart_price to be printed for order at the bottom 
      foreach ($orders_table as $row) 
      {
        if ($row['total_cart_price'] != '0.00' && $row['order_num'] == $past_orders)
        {
          echo "<h3>Order Total: $" . $row['total_cart_price'] . "</h3>";
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
</html>