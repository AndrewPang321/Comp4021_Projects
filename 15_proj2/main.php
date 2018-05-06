<?php

// Add the code to start the session
session_start();
// Add the code to check for signed in user
if (!isset($_SESSION["username"])) {
  header("Location: signin.php");
  exit;
}
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];
// Add the code to validate the user from the JSON file
if (!array_key_exists($_SESSION["username"], $users)) {
  // Do something if the username does not exist!
  header("Location: signin.php");
  exit;
}
// Read the first name of the user
$firstname = $users[$_SESSION["username"]]["firstname"];
$username = $_SESSION["username"];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab 7: Main Page</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
    <script>
        function paginationHandler() {
            // store pagination container so we only select it once
            var $paginationContainer = $(".pagination-container"),
                $pagination = $paginationContainer.find('.pagination ul');

            // click event
            $pagination.find("li a").on('click.pageChange', function(e) {
                e.preventDefault();
                // get parent li's data-page attribute and current page
                var parentLiPage = $(this).parent('li').data("page"),
                currentPage = parseInt( $(".pagination-container div[data-page]:visible").data('page') ),
                numPages = $paginationContainer.find("div[data-page]").length;
                // make sure they aren't clicking the current page
                if ( parseInt(parentLiPage) !== parseInt(currentPage) ) {
                    // hide the current page
                    $paginationContainer.find("div[data-page]:visible").hide();
                    // console.log($paginationContainer.find("#listContent").find("div[data-page]:visible"));
                    if ( parentLiPage === '+' ) {
                        // next page
                        $paginationContainer.find("div[data-page="+( currentPage+1>numPages ? numPages : currentPage+1 )+"]").show();
                        $pagination.find("li[data-page="+currentPage+"]").attr("class", "page-item");
                        $pagination.find("li[data-page="+( currentPage+1>numPages ? numPages : currentPage+1 )+"]").attr("class", "page-item active");
                    } else if ( parentLiPage === '-' ) {
                        // previous page
                        $paginationContainer.find("div[data-page="+( currentPage-1<1 ? 1 : currentPage-1 )+"]").show();
                        $pagination.find("li[data-page="+currentPage+"]").attr("class", "page-item");
                        $pagination.find("li[data-page="+( currentPage-1<1 ? 1 : currentPage-1 )+"]").attr("class", "page-item active");
                    } else {
                        // specific page
                        $paginationContainer.find("div[data-page="+parseInt(parentLiPage)+"]").show();
                        $pagination.find("li[data-page="+currentPage+"]").attr("class", "page-item");
                        $pagination.find("li[data-page="+parseInt(parentLiPage)+"]").attr("class", "page-item active");
                    }
                }
            });
        };

        function createPagination(totalPage) {
            var html = "";
            for (let i = 1; i < totalPage; i++) {
                let pageNo = i+1;
                html += "<li class='page-item' data-page='" + pageNo + "'><a class='page-link' href='#'>" + pageNo + "</a></li>"; 
            }
            $(".pagination li:nth-child(2)").after(html);
            // console.log(html);
        }

        $(document).ready(function() {
            /*** For Sorting and Searching ***/
            // Construct the URL without the query string
            var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
            // Find the query string and parameters
            var qstring = window.location.search.substring(1);
            var pairs = qstring.split("&");
            var params = {};
            for (var i = 0; i < pairs.length; i++) {
                pairs[i] = pairs[i].split("=");
                params[pairs[i][0]] = pairs[i][1];
            }
            console.log(params);
            // Set up the event handlers
            $("#sort-by-title").on("click", function() {
                window.location = url + "?orderby=title" + (params["s"]? "&s=" + params["s"] : "");
                return false;
            });
            $("#sort-by-year").on("click", function() {
                window.location = url + "?orderby=year" + (params["s"]? "&s=" + params["s"] : "");
                return false;
            });
            $("#reset-sort-order").on("click", function() {
                window.location = url + (params["s"]? "?s=" + params["s"] : "");
                return false;
            });
            $("#search-button").on("click", function() {
                var search = $("#search-box").val().trim();
                if (search != "") {
                    window.location = url + "?s=" + search + (params["orderby"]? "&orderby=" + params["orderby"] : "");
                }
                return false;
            });
            $("#clear-button").on("click", function() {
                window.location = url + (params["orderby"]? "?orderby=" + params["orderby"] : "");
                return false;
            });
            $("#clear-all-button").on("click", function() {
                window.location = url;
                return false;
            });

            // This is the hashchange event function
            $(window).on('hashchange', function() {
                // Get the fragment identifier from the URL
                var page = window.location.hash;
                if (page == "") page = "#";

                // Handle the hashchange as SPA, ~router
                $(".page").hide();
                switch(page) {
                    case "#":
                        $("#listPage").show();
                        break;
                    case "#add":
                        $("#addPage").show();
                        break;
                    case "#profile":
                        $("#profilePage").show();
                        break;
                }
            });
            // You may want to trigger the hashchange event when the page loads
            $(window).trigger("hashchange");

            // Get php $username variable
            var username = "<?= $username ?>";
            query = "username=" + encodeURIComponent(username);
            if (params["orderby"] != null) {
                query += "&orderby=" + encodeURIComponent(params["orderby"]);
            }
            if (params["s"] != null && params["s"] != "") {
                query += "&s=" + encodeURIComponent(params["s"]);
            }
            $.get("list.php", query, (data) => {
                var index = 0;  // For 5 items per page
                var pageNo = 1; // Initial page number
                var html;
                // console.log(data.contents);
                for (let key in data.contents) {
                    if (index % 5 === 0) {
                        if (index === 0) {
                            // First page will always be displayed
                            html = "<div data-page='" + pageNo + "'>";
                        } else {
                            // Hide other pages
                            html += "<div data-page='" + pageNo + "' style='display:none;'>";
                        }
                        html += "<div class='row'>";
                        pageNo++;
                    }
                    index++;
                    html += "<div class='item col-4 col-md-3 col-lg-2 mr-4'>";
                    if (data.contents[key]["image"] != "") {
                        html += "<div class='image'><img src='" + data.contents[key]["image"] + "' class='w-100 p-1 mt-5 mb-3' alt='Image'></div>";
                    }
                    html += "<div class='name'>" + data.contents[key]["name"] + "</div>";
                    html += "<div class='year pb-1'>" + data.contents[key]["year"] + "</div>";
                    html += "<div class='row btn-group'>";
                    html += "<div class='edit pr-2'><button class='btn-sm btn-info'><i class='fas fa-edit'></i> <small>Edit</small></button></div>";
                    html += "<div class='delete'><button class='btn-sm btn-danger'><i class='fas fa-trash-alt'></i> <small>Delete</small></button></div>";
                    html += "</div>";
                    
                    // html += "<div class='types'>";
                    // for (let type in data[key]["genre"]) {
                    //     if (type > 0) html += ", ";
                    //     html += "<span class='type'>" + data[key]["genre"][type] + "</span>";
                    //     // console.log(data[key]["genre"][type]);
                    // }
                    // html += "</div>";

                    html += "</div>";
                    if (index % 5 === 0) {
                        // Close row and data-page div
                        html += "</div>";
                        html += "</div>";
                    }

                    console.log(key, data.contents[key]);
                }
                html += "</div>";

                if (data.contents == "") {
                    html = "<h4 class='mt-5 mb-5'>You currently have no contents!</h4>";
                    html += "<div class='add mb-5'><button class='btn btn-info'><i class='fas fa-plus-square'></i> <small>Add Item</small></button></div>";
                }
                $("#listContent").html(html);
                // console.log(html);

                // Dynamically create pagination nav bar
                createPagination($(".pagination-container #listContent div[data-page]").length);
                // Pagination which handles on clickPageChange
                paginationHandler();
            }).fail(function() {
                alert("Unknown error!");
            }, "json");
        });
    </script>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            /* display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px; */
            background-color: #f5f5f5;
        }

        .navbar {
            min-height: 65px;
        }
    </style>
</head>
<body class="text-center">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(87, 99, 102);">
        <a class="navbar-brand" href="#">COMP4021 Group 15</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">List Item <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#add">Add Item</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#profile">Profile</a>
                </li>
            </ul>
            <h5><span class="badge badge-dark mr-3 mt-2 p-2">
                <i class="far fa-smile"></i>
                Welcome, <?= htmlspecialchars($firstname) ?>!
            </span></h5>
            <form id="signoutForm" action="signout.php">
                <button type="submit" class="btn btn-success my-2 my-sm-0"><i class="fas fa-sign-out-alt mr-2"></i> Sign Out</button>
            </form>
        </div>
    </nav>

    <!-- List Page (with Delete) -->
    <div id="listPage" class="container page pt-3 pb-3" style="display: none">
        <div class="row">
            <!-- Sorting & Searching bar -->
            <div class="dropdown pr-2">
                <a class="btn btn-outline-secondary dropdown-toggle" href="#" id="dropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sorting
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" id="sort-by-title" href="#">Sort by Title</a>
                    <a class="dropdown-item" id="sort-by-year" href="#">Sort by Year</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" id="reset-sort-order" href="#">Reset Sort Order</a>
                </div>
            </div>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" id="search-box" type="search" placeholder="Search" aria-label="Search" value="<?= $search; ?>">
                <button class="btn btn-outline-success my-2 my-sm-0" id="search-button" type="submit">Search</button>
                <button class="btn btn-outline-info ml-1 my-2 my-sm-0" id="clear-button">Clear</button>
                <button class="btn btn-outline-warning ml-1 my-2 my-sm-0" id="clear-all-button">Clear All</button>
            </form>
        </div>

        <!-- This is the div for showing the item list -->
        <div class="row">
            <div class="pagination-container">
                <div id="listContent"></div>
                <br><br>
                <nav id="pageNav" aria-label="Page Navigation">
                    <div class="pagination justify-content-end">
                        <ul class="pagination">
                            <li class="page-item" data-page="-"><a class="page-link" href="#">Previous</a></li>
                            <li id="firstPage" class="page-item active" data-page="1"><a class="page-link" href="#">1</a></li>
                            <!-- <li class="page-item" data-page="2"><a class="page-link" href="#">2</a></li>
                            <li class="page-item" data-page="3"><a class="page-link" href="#">3</a></li> -->
                            <li class="page-item" data-page="+"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Edit Page -->
    <div id="editPage" class="container page pt-3 pb-3" style="display: none">
        Edit
    </div>
    <!-- Add Page -->
    <div id="addPage" class="container page pt-3 pb-3" style="display: none">
        Add
    </div>
    <!-- Profile Page -->
    <div id="profilePage" class="container page pt-3 pb-3" style="display: none">
        Profile
    </div>
</body>
</html>
