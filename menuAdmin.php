    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/menuBar.js"></script>
  <div class="navbar">
    <a href="welcome.php">Home</a>
    <div class="dropdown">
      <button class="dropbtn" onclick="qddFunction()">Quotes
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content" id="quoteDD">
        <a  href="createQuote.php">New Quote</a>
        <a  href="viewQuote.php">View Quotes</a>
      </div>
    </div> 
    <div class="dropdown">
      <button class="dropbtn" onclick="oddFunction()">Orders
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content" id="orderDD">
        <a  href="createOrder.php">New Order</a>
        <a  href="viewOrders.php">View Orders</a>
      </div>
    </div> 
    <div class="dropdown">
      <button class="dropbtn" onclick="iddFunction()">Invoices
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content" id="invoiceDD">
        <a  href="createInvoice.php">New Invoice</a>
        <a  href="viewInvoice.php">View Invoices</a>
      </div>
    </div> 
    <div class="dropdown">
      <button class="dropbtn" onclick="cddFunction()">Customers
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content" id="customerDD">
        <a  href="createCustomer.php">New Customer</a>
        <a  href="customersList.php">View Customers</a>
      </div>
    </div> 
  </div>
  <div class="topcorner">
    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a> | <a href="logout.php" class="btn btn-danger">Sign Out</a>
  </div>