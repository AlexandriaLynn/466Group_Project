<html>
  <head>
    <!--Ally-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
      
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
        <a href='login.php'>Login</a>
    </div>
    <?php
      // start and destroy session so it logs out
      session_start(); 
      session_destroy();
    ?>
    <h1><center>You have been logged out.</center></h1>

    </body>

</html>