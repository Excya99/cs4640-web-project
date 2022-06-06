<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class BFinancialController {
    private $command; //initialize command
    private $db; //initialize database
    private $apiKey = ""; //set API key (Polygon.io stock API)

    public function __construct($command) {
        $this->command = $command; //set command
        $this->db = new Database(); //instantiate database
    }

    public function run() {
        switch($this->command) {
            case "signup":
                $this->signup();
                break; //separate templates
            case "index":
                $this->index();
                break; //separate templates
            case "portfolios":
                $this->portfolios();
                break; //separate templates
            case "dashboard":
                $this->dashboard();
                break; //separate templates
            case "market_indexes":
                $this->market_indexes();
                break; //separate templates
            case "pricing":
                $this->pricing();
                break; //separate templates
            case "about":
                $this->about();
                break; //separate templates
            case "technologies":
                $this->technologies();
                break; //separate templates
            case "support":
                $this->support();
                break; //separate templates
            case "terms":
                $this->terms();
                break; //separate templates
            case "privacy":
                $this->privacy();
                break; //separate templates
            case "sitemap":
                $this->privacy();
                break; //separate templates
            //API commands (free tier access)
            case "API_DailyOpenClose":
                $this->API_DailyOpenClose();
                break; //separate templates
            case "API_PreviousClose":
                $this->API_PreviousClose();
                break; //separate templates
            case "API_AggregateDateRange":
                $this->API_AggregateDateRange();
                break; //separate templates
            case "API_StockNews":
                $this->API_StockNews();
                break; //separate templates
            case "API_StockFinancials":
                $this->API_StockFinancials();
                break; //separate templates
            //API commands (free tier access)
            case "login":
                $this->login();
                break; //separate templates
            case "logout":
                $this->destroyCookies();
                $this->destroySession();
            default:
                $this->login();
        }
    }

    // Clear all cookie data
    private function destroyCookies() {
        setcookie("email", "", time()-3600); //60sec per min * 60 min = 3600sec (client access expires after 1 hour) 
    }

    // Clear all session data
    private function destroySession() {
        session_destroy();
        session_start();
    }

    private function login() {
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $data = $this->db->query("select * from users where email = ?;", "s", $_POST["email"]);
            if ($data == false) {
                $error_msg = "Unrecognized account, signup below";
            }
            else if (!empty($data)) {
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    $_SESSION["name"] = $data[0]["name"]; //always track user's name
                    $_SESSION["email"] = $data[0]["email"]; //always track user's email
                    $_SESSION["id"] = $data[0]["id"]; //always track user's ID from database
                    setcookie("email", $_SESSION["email"], time()+3600); //also set a cookie from session data
                    if (isset($_POST["remember_session"])) {
                        $_SESSION["password"] = $data[0]["password"]; //store login information server-side if "remember me" is checked
                    }
                    header("Location: ?command=index");
                }
                else {
                    $error_msg = "Incorrect password please try again";
                }
            }
        }
        include("templates/login.php");
    }
    /*
    Future improvements for signup regarding database:
    check for unique email (important for DB management and schema) before automatically inserting into database
    */
    private function signup() {
        if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["email"])) {
            if (isset($_POST["terms_agreement"])) { //only allow user to signup if they click agree to terms displayed by front-end
                $fullname = $_POST["fname"] . " " . $_POST["lname"];
                $insert = $this->db->query("insert into users (name, email, password) values (?, ?, ?);", "sss", $fullname, $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                if ($insert == false) {
                    $error_msg = "Error registering user";
                }
                else {
                    $data = $this->db->query("select * from users where email = ?;", "s", $_POST["email"]);
                    $_SESSION["name"] = $data[0]["name"]; //always track user's name
                    $_SESSION["email"] = $data[0]["email"]; //always track user's email
                    $_SESSION["id"] = $data[0]["id"]; //always track user's ID from database
                    setcookie("email", $_SESSION["email"], time()+3600); //also set a cookie from session data
                    header("Location: ?command=index");
                }
            }
            else {
                $error_msg = "Please accept our Terms and Conditions";
            }
        }
        include("templates/signup.php");
    }

    private function checkTickerSymbol($ticker) {
        $pattern = "/^[A-Za-z]+$/";
        if ((preg_match($pattern, $ticker)) && (strlen($ticker) <= 5) && (strlen($ticker) >= 1)) {
            return true;
        }
        return false;
    }
    /*
    Future improvements for all functions involving API:
    A lot of things can be improved here, these functions are very basic and have hardcoded aspects to how the function makes calls to the API
    due to large number of different options to set per request
    */
    //Polygon.io API free tier request
    private function API_DailyOpenClose() {
        if ($this->checkTickerSymbol($_GET["ticker"])) {
            $daily_open_close_request = "https://api.polygon.io/v1/open-close/".$_GET["ticker"]."/".$_GET["today"]."?adjusted=true&apiKey=".$apiKey;
            header("Content-type: application/json");
            echo file_get_contents($daily_open_close_request);
        }
    }
    //Polygon.io API free tier request
    private function API_PreviousClose() {
        if ($this->checkTickerSymbol($_GET["ticker"])) {
            $previous_close_request = "https://api.polygon.io/v2/aggs/ticker/".$_GET["ticker"]."/prev?adjusted=true&apiKey=".$apiKey;
            header("Content-type: application/json");
            echo file_get_contents($previous_close_request);
        }
    }
    //Polygon.io API free tier request
    private function API_AggregateDateRange() {
        if ($this->checkTickerSymbol($_GET["ticker"])) {
            //Note: frequency is set to once per hour between date range (careful)
            $aggregate_date_range_request = "https://api.polygon.io/v2/aggs/ticker/".$_GET["ticker"]."/range/1/hour/".$_GET["from_date"]."/".$_GET["to_date"]."?adjusted=true&sort=asc&limit=120&apiKey=".$apiKey;
            header("Content-type: application/json");
            echo file_get_contents($aggregate_date_range_request);
        }
    }
    //Polygon.io API free tier request
    private function API_StockNews() {
        if ($this->checkTickerSymbol($_GET["ticker"])) {
            //Note: maximum returned news is set to 10
            $stock_news_request = "https://api.polygon.io/v2/reference/news?ticker=".$_GET["ticker"]."&limit=10&sort=published_utc&apiKey=".$apiKey;
            header("Content-type: application/json");
            echo file_get_contents($stock_news_request);
        }
    }
    //Polygon.io API free tier request
    private function API_StockFinancials() {
        if ($this->checkTickerSymbol($_GET["ticker"])) {
            //Note: timeframe is set to quarterly reports
            $stock_financial_data_request = "https://api.polygon.io/vX/reference/financials?ticker=".$_GET["ticker"]."&timeframe=quarterly&include_sources=false&sort=filing_date&apiKey=".$apiKey;
            header("Content-type: application/json");
            echo file_get_contents($stock_financial_data_request);
        }
    }

    private function index() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        include("templates/index.php");
    }

    private function portfolios() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        /*
        Fetch and preloading portfolio from database needs work:

        $portfolio_id = $this->db->query("select * from portfolios where user_id = ?;", "i", $user["id"])[0]["portfolio_id"]; 
        //Load portfolio from database when user visits portfolio page
        $loaded_portfolio = array();
        $count = 0;
        while(true) {
            $retrieveStocks = $this->db->query("select * from stocks where portfolio_id = ?;", "i", $portfolio_id);
            if ($retrieveStocks == false) {
                break;
            }
            else {
                array_push($loaded_portfolio, array("ticker"=>$retrieveStocks[0]["stock_ticker"], "price"=>$retrieveStocks[0]["price"], "original_cost"=>$retrieveStocks[0]["original_cost"], "acquire_date"=>$retrieveStocks[0]["acquire_date"]));
                $this->db->query("delete from stocks where stock_id = ?;", "i", $retrieveStocks[0]["stock_id"]);
                $count+=1;
            }
        }
        $jsonSend = json_encode($loaded_portfolio);
        echo $jsonSend;
        */
        if (isset($_POST["jsonPortfolio"])) {
            $portfolio_name = $user["name"]."'s "."portfolio";
            $checkPortfolio = $this->db->query("select * from portfolios where user_id = ?;", "i", $user["id"]);
            if (!empty($checkPortfolio)) {
                ; //Do nothing, already have portfolio
            }
            else {
                $insert = $this->db->query("insert into portfolios (portfolio_name, user_id) values (?, ?);", "si", $portfolio_name, $user["id"]);
                if ($insert == false) {
                    alert("Error creating portfolio");
                }
            }
            $data = $this->db->query("select * from portfolios where user_id = ?;", "i", $user["id"]);
            $portfolio_id = $data[0]["portfolio_id"];
            //Clear old stocks before syncing update by deleting all rows matching portfolio ID, much more efficient way of updating possible (clear/reset method was quickest during time of writing this code)
            $clear_old_stocks = $this->db->query("delete from stocks where portfolio_id = ?;", "i", $portfolio_id);
            $portfolio = json_decode($_POST["jsonPortfolio"], true);
            $total_portfolio_value = 0;
            $total_portfolio_cost = 0;
            for($i=0; $i<count($portfolio); $i++) {
                $stock_ticker = $portfolio[$i]["ticker"];
                $price = $portfolio[$i]["price"];
                $original_cost = $portfolio[$i]["original_cost"];
                $acquire_date = $portfolio[$i]["acquire_date"];
                $insert_stock = $this->db->query("insert into stocks (stock_ticker, price, original_cost, acquire_date, portfolio_id) values (?, ?, ?, ?, ?);", "sddsi", $stock_ticker, $price, $original_cost, $acquire_date, $portfolio_id);
                $total_portfolio_value+=$price;
                $total_portfolio_cost+=$original_cost;
            }
            $update_portfolio = $this->db->query("update portfolios set total_value = ?, total_cost = ? where portfolio_id = ?;", "ddi", $total_portfolio_value, $total_portfolio_cost, $portfolio_id);
        }
        include("templates/portfolios.html");
    }

    private function dashboard() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        include("templates/dashboard.html");
    }

    private function market_indexes() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        include("templates/market_indexes.html");
    }

    private function pricing() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        include("templates/pricing.html");
    }

    private function about() {
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "id" => $_SESSION["id"]
        ];
        include("templates/about.html");
    }

    private function technologies() {
        include("templates/technologies.html");
    }

    private function support() {
        include("templates/support.html");
    }

    private function terms() {
        include("templates/terms.html");
    }

    private function privacy() {
        include("templates/privacy.html");
    }

    private function sitemap() {
        include("templates/sitemap.html");
    }
}