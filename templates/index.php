<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <meta name="author" content="Brandon Williams (byw3xy)">
  <meta name="description" content="CS4640 Project: BFinancials Web Application">
  <meta name="keywords" content="Financial Web Application">
  <title>BFinancials</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#index_stockSearch").on("keypress", function(key) {
        if (key.which == 13) { //when "Enter" key is pressed, prepare and send AJAX request
          var stockTicker = $(this).val();
          $(this).attr("disabled", "disabled"); //disable textbox, prevents multiple submits
          $.ajax({
            url: "?command=API_PreviousClose",
            type: "GET",
            data: {"ticker": stockTicker},
            dataType: "json",
            success: function(data) {
              $("#stock_name").text(data["results"][0]["T"]);
              $("#prev_open").text(data["results"][0]["o"]);
              $("#prev_high").text(data["results"][0]["h"]);
              $("#prev_low").text(data["results"][0]["l"]);
              $("#prev_close").text(data["results"][0]["c"]);
              $("#prev_volume").text(data["results"][0]["v"]);
            },
            error: function() {
              $("#stock_name").text("");
              $("#prev_open").text("");
              $("#prev_high").text("");
              $("#prev_low").text("");
              $("#prev_close").text("");
              $("#prev_volume").text("");
            }
          });
          $(this).removeAttr("disabled"); //re-enable textbox again
          return false; //prevents search bar from refreshing page!
        }
      });
    });
  </script>
</head>

<body>
    <!-- Bootstrap header template -->
    <header class="p-3 bg-dark text-white">
        <div class="container-fluid">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="?command=index" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
              <img src="templates/styles/icon.png" alt="Generic financial logo" width="40" height="40" class="d-inline-block align-text-top">
                BFinancials
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
              <li><a href="?command=portfolios" class="nav-link px-2 text-white">Portfolios</a></li>
              <li><a href="?command=dashboard" class="nav-link px-2 text-white">Dashboard</a></li>
              <li><a href="?command=market_indexes" class="nav-link px-2 text-white">Market Index</a></li>
              <li><a href="?command=pricing" class="nav-link px-2 text-white">Pricing</a></li>
              <li><a href="?command=about" class="nav-link px-2 text-white">About Us</a></li>
            </ul>
            
            <?php
            if (!empty($error_msg)) {
              echo "<div class='alert alert-danger'>$error_msg</div>";
            }
            ?>
            <!-- jQuery AJAX request -->
            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
              <input type="search" class="form-control form-control-dark" placeholder="Stock ticker search" aria-label="Stock searchbox" id="index_stockSearch" name="index_stockSearch">
            </form>

            <div class="text-end">
              <button class="btn btn-dark"><?=$user["name"]?></button>
              <a href="?command=logout" class="btn btn-info" role="button">Log out</a>
            </div>
          </div>
        </div>
      </header>
      <!-- Bootstrap Jumbotron template -->
      <div class="p-5 mb-4 text-white bg-secondary bg-gradient">
        <div class="container py-5">
            <h1 class="display-5 fw-bold">$B-Financials</h1>
            <p class="col-md-8 fs-4">Together we will help you brighten your financial assets.</p>
            <a class="btn btn-primary btn-lg" href="?command=technologies">Our Tools</a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Investment Portfolios</h3>
                <p>Customize and track your future portfolio(s) with us!<br>Also offering virtual portfolios for beginners to learn.</p>
                <a href="?command=portfolios" class="btn btn-info">Get Started</a>
            </div>

            <div class="col-md-4">
                <h3>Stock Indexes</h3>
                <p>Want to track popular market indexes on securities?<br>Live updates on multiple indexes all in one place!</p>
                <a href="?command=market_indexes" class="btn btn-danger btn-md">Track Now</a>
            </div>

            <div class="accordion col-md-4">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Previous Day's Prices
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <strong>Stock: </strong><code id="stock_name"></code>
                      <br>
                      <strong>Open: </strong><code id="prev_open"></code>
                      <br>
                      <strong>High: </strong><code id="prev_high"></code>
                      <br>
                      <strong>Low: </strong><code id="prev_low"></code>
                      <br>
                      <strong>Close: </strong><code id="prev_close"></code>
                      <br>
                      <strong>Volume: </strong><code id="prev_volume"></code>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-8 mb-0 text-muted">&copy; 2022 BFinancials. All rights reserved. All investments involve risks, including the possible loss of capital.</p>
            <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="?command=support" class="nav-link px-2 text-muted">Support</a></li>
                <li class="nav-item"><a href="?command=terms" class="nav-link px-2 text-muted">Terms & Conditions</a></li>
                <li class="nav-item"><a href="?command=privacy" class="nav-link px-2 text-muted">Privacy</a></li>
                <li class="nav-item"><a href="?command=sitemap" class="nav-link px-2 text-muted">Site Map</a></li>
            </ul>
        </footer>
    </div>
    <!-- Load Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>