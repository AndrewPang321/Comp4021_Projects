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

        function deletePagination(totalPage) {
            for (let i = 1; i < totalPage; i++) {
                $(".pagination li:nth-child(2)").next().remove();
            }
            $(".pagination li:nth-child(2)").addClass("page-item active");
        }

        function listViewHelper(data) {
            var index = 0;  // For 5 items per page
            var pageNo = 1; // Initial page number
            var html;
            // console.log(data.contents);
            if (Object.keys(data.contents).length === 0 && data.contents.constructor === Object) {
                html = "<h4 class='mt-5 mb-5'>No search result is found!</h4>";
            } else {
                if (data.contents == "") {
                    html = "<h4 class='mt-5 mb-5'>You currently have no contents!</h4>";
                    html += "<div class='mb-5'><button id='noContentAdd' class='btn btn-info'><i class='fas fa-plus-square'></i> <small>Add Item</small></button></div>";
                } else {
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
                        html += "<div class='delete'><button class='btn-sm btn-danger' data-toggle='modal' data-target='#deleteModal'><i class='fas fa-trash-alt'></i> <small>Delete</small></button></div>";
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
                }
                html += "</div>";
            }
            return html;
        }

        function editPageHelper(url, href, params, currTag) {
            if (params["orderby"] != null && (params["s"] == null || params["s"] == "")) {
                url += "?orderby=" + encodeURIComponent(params["orderby"]);
            } else if (params["s"] != null && params["s"] != "" && params["orderby"] == null) {
                url += "?s=" + encodeURIComponent(params["s"]);
            } else if (params["orderby"] != null && params["s"] != null && params["s"] != "") {
                if (href.lastIndexOf("s=") < href.lastIndexOf("orderby=")) {
                    url += "?s=" + encodeURIComponent(params["s"]) + "&orderby=" + encodeURIComponent(params["orderby"]);
                } else {
                    url += "?orderby=" + encodeURIComponent(params["orderby"]) + "&s=" + encodeURIComponent(params["s"]);
                }
            }
            window.location = url + "#edit";
            var movieTitle = currTag.parent().parent().find(".name").text();
            var movieYear = currTag.parent().parent().find(".year").text();
            var moviePoster = currTag.parent().parent().find(".image img").attr("src");
            $("#itemName").val(movieTitle);
            $("#itemYear").val(movieYear);
            $("#itemPoster").val(moviePoster);
            $("#reservedPoster").html("<label><img src='" + moviePoster + "' class='w-25 p-1 mt-1 mb-1' alt='Image'></label>");
            return movieTitle;
        }

        function editFormSubmit(url, href, params, oldTitle) {
            $(".editForm").on("submit", function() {
                var username = "<?= $username ?>";
                var updatedTitle = $(".editForm #itemName")[0].value.trim();
                var updatedYear = $(".editForm #itemYear")[0].value.trim();
                var updatedPoster = $(".editForm #itemPoster")[0].value.trim();
                var query = "username=" + encodeURIComponent(username);
                query += "&oldtitle=" + encodeURIComponent(oldTitle);
                query += "&newtitle=" + encodeURIComponent(updatedTitle);
                query += "&year=" + encodeURIComponent(updatedYear);
                query += "&poster=" + encodeURIComponent(updatedPoster);

                $.get("edit.php", query, (data) => {
                    if (params["orderby"] != null && (params["s"] == null || params["s"] == "")) {
                        url += "?orderby=" + encodeURIComponent(params["orderby"]);
                    } else if (params["s"] != null && params["s"] != "" && params["orderby"] == null) {
                        url += "?s=" + encodeURIComponent(params["s"]);
                    } else if (params["orderby"] != null && params["s"] != null && params["s"] != "") {
                        if (href.lastIndexOf("s=") < href.lastIndexOf("orderby=")) {
                            url += "?s=" + encodeURIComponent(params["s"]) + "&orderby=" + encodeURIComponent(params["orderby"]);
                        } else {
                            url += "?orderby=" + encodeURIComponent(params["orderby"]) + "&s=" + encodeURIComponent(params["s"]);
                        }
                    }
                    window.location = url;
                }).fail(function() {
                    alert("Unknown error!");
                }, "json");
                return false;
            });
        }

        function addFormSubmit(url, href, params) {
            $(".addForm").on("submit", function() {
                var username = "<?= $username ?>";
                var title = $(".addForm #itemName")[0].value.trim();
                var year = $(".addForm #itemYear")[0].value.trim();
                var poster = $(".addForm #itemPoster")[0].value.trim();
                var query = "username=" + encodeURIComponent(username);
                query += "&title=" + encodeURIComponent(title);
                query += "&year=" + encodeURIComponent(year);
                query += "&poster=" + encodeURIComponent(poster);

                $.get("add.php", query, (data) => {
                    if (data.success == "duplicate") {
                        $('#addModal').modal('show');
                        $('#addModal').on('shown.bs.modal', function(e) {
                            $('#addModal').trigger('focus')
                            // Update Modal's text
                            $("#addModal h5").text("Add \"" + title + "\"");
                        });
                    } else {
                        if (params["orderby"] != null && (params["s"] == null || params["s"] == "")) {
                            url += "?orderby=" + encodeURIComponent(params["orderby"]);
                        } else if (params["s"] != null && params["s"] != "" && params["orderby"] == null) {
                            url += "?s=" + encodeURIComponent(params["s"]);
                        } else if (params["orderby"] != null && params["s"] != null && params["s"] != "") {
                            if (href.lastIndexOf("s=") < href.lastIndexOf("orderby=")) {
                                url += "?s=" + encodeURIComponent(params["s"]) + "&orderby=" + encodeURIComponent(params["orderby"]);
                            } else {
                                url += "?orderby=" + encodeURIComponent(params["orderby"]) + "&s=" + encodeURIComponent(params["s"]);
                            }
                        }
                        window.location = url;
                    }
                }).fail(function() {
                    alert("Unknown error!");
                }, "json");
                return false;
            });
        }

        function profileFormSubmit(url, href, params) {
            $(".addProfileForm").on("submit", function() {
                var oldusername = "<?= $username ?>";
                var newusername = $(".addProfileForm #profileUsername")[0].value.trim();
                var firstname = $(".addProfileForm #profileFirstName")[0].value.trim();
                var lastname = $(".addProfileForm #profileLastName")[0].value.trim();
                var profilepic = $(".addProfileForm #profilePic")[0].value.trim();
                var password = $(".addProfileForm #profilePw")[0].value.trim();
                var query = "oldusername=" + encodeURIComponent(oldusername);
                query += "&newusername=" + encodeURIComponent(newusername);
                query += "&firstname=" + encodeURIComponent(firstname);
                query += "&lastname=" + encodeURIComponent(lastname);
                query += "&profilepic=" + encodeURIComponent(profilepic);
                query += "&password=" + encodeURIComponent(password);

                $.post("profile.php", query, (data) => {
                    if (data.success == "incorrect") {
                        $('#addModal').modal('show');
                        $('#addModal').on('shown.bs.modal', function(e) {
                            $('#addModal').trigger('focus')
                            // Update Modal's text
                            $("#addModal h5").text("Update Profile");
                            $("#addModal .modal-body span").text("Incorrect password!");
                        });
                    } else {
                        if (oldusername == newusername) {
                            redirect = href.substr(0, href.lastIndexOf("#"));
                            window.location = redirect;
                        } else {
                            var signinQuery = "username=" + encodeURIComponent(newusername);
                            signinQuery += "&password=" + encodeURIComponent(password);
                            // Automatically login the user again after update of username
                            $.post("signinuser.php", signinQuery, (data) => {
                                if (data.error) {
                                    console.log("Sign-in error after update the profile.");
                                } else {
                                    if (params["orderby"] != null && (params["s"] == null || params["s"] == "")) {
                                        url += "?orderby=" + encodeURIComponent(params["orderby"]);
                                    } else if (params["s"] != null && params["s"] != "" && params["orderby"] == null) {
                                        url += "?s=" + encodeURIComponent(params["s"]);
                                    } else if (params["orderby"] != null && params["s"] != null && params["s"] != "") {
                                        if (href.lastIndexOf("s=") < href.lastIndexOf("orderby=")) {
                                            url += "?s=" + encodeURIComponent(params["s"]) + "&orderby=" + encodeURIComponent(params["orderby"]);
                                        } else {
                                            url += "?orderby=" + encodeURIComponent(params["orderby"]) + "&s=" + encodeURIComponent(params["s"]);
                                        }
                                    }
                                    window.location = url;
                                }
                            }, "json");
                        }
                    }
                }).fail(function() {
                    alert("Unknown error!");
                }, "json");
                return false;
            });
        }

        function addItemButtonRegister() {
            $("#noContentAdd").on("click", function() {
                if (window.location.href.substr(window.location.href.length - 1) != "#") {
                    window.location = window.location.href + "#add";
                } else {
                    window.location = window.location.href + "add";
                }
                return false;
            });
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
            // Check for the username in profile page
            $("#profilePage #profileUsername").on("change", function() {
                // Hide the error
                $("#profilePage #unavailError").hide();
                // Show the error if it is not available
                if ($("#profileUsername").val() != "") {
                    var oldusername = "<?= $username ?>";
                    if (oldusername != $("#profileUsername").val()) {
                        var query = "username=" + encodeURIComponent($("#profileUsername").val());
                        $.getJSON("checkusername.php", query, function(data) {
                            if (data.available == "no")
                                $("#profilePage #unavailError").show();
                            else 
                                $("#profilePage #unavailError").hide();
                        });
                    }
                }
            });

            addFormSubmit(url, window.location.href, params);
            profileFormSubmit(url, window.location.href, params);

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
                    case "#edit":
                        $("#editPage").show();
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
                var html = listViewHelper(data);
                $("#listContent").html(html);
                // console.log(html);

                // Dynamically create pagination nav bar
                createPagination($(".pagination-container #listContent div[data-page]").length);
                // Pagination which handles on clickPageChange
                paginationHandler();

                $("#listContent .edit").on("click", function() {
                    var oldTitle = editPageHelper(url, window.location.href, params, $(this));
                    editFormSubmit(url, window.location.href, params, oldTitle.trim());
                    return false;
                });

                addItemButtonRegister();
            }).fail(function() {
                alert("Unknown error!");
            }, "json");

            $('#deleteModal').on('shown.bs.modal', function(e) {
                $('#deleteModal').trigger('focus')
                // Get the item name
                var name = $(e.relatedTarget).parent().parent().parent().find(".name").text();
                // Update Modal's text
                $("#deleteModal h5").text("Delete \"" + name + "\"");
                $("#deleteModal span").text("Are you sure to delete \"" + name + "\" permanently?");
                $("#deleteItem").on("click", function() {
                    var originalTotalPage = $(".pagination-container #listContent div[data-page]").length;
                    $('#deleteModal').modal('hide');
                    query += "&key=" + encodeURIComponent(name);
                    // Handle the deletion from db.json
                    $.get("delete.php", query, (data) => {
                        var html = listViewHelper(data);
                        $("#listContent").html(html);
                        // console.log(html);
                        addItemButtonRegister();
                        
                        // Dynamically create pagination nav bar
                        deletePagination(originalTotalPage);
                        createPagination($(".pagination-container #listContent div[data-page]").length);
                        // Pagination which handles on clickPageChange
                        paginationHandler();

                        $("#listContent .edit").on("click", function() {
                            var oldTitle = editPageHelper(url, window.location.href, params, $(this));
                            editFormSubmit(url, window.location.href, params, oldTitle.trim());
                            return false;
                        });
                    }).fail(function() {
                        alert("Unknown error!");
                    }, "json");
                    // Delete the item from DOM
                    $(e.relatedTarget).parent().parent().parent().remove();
                    return false;
                });
            });
        });
    </script>
    <style>
        html,
        body {
            height: 100%;
        }
        body {
            background-color: #f5f5f5;
        }
        .navbar {
            min-height: 65px;
        }

        .editForm {
            width: 100%;
            max-width: 850px;
            padding: 15px;
            margin: auto;
        }
        .editForm .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .editForm .form-control:focus {
            z-index: 2;
        }

        .addForm {
            width: 100%;
            max-width: 850px;
            padding: 15px;
            margin: auto;
        }

        .addForm .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .addForm .form-control:focus {
            z-index: 2;
        }

        .addProfileForm {
            width: 100%;
            max-width: 850px;
            padding: 15px;
            margin: auto;
        }

        .addProfileForm .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .addProfileForm .form-control:focus {
            z-index: 2;
        }
    </style>
</head>
<body class="text-center">
    <!-- Modal for delete -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span>Are you sure to delete this item permanently?<span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="deleteItem" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span>Add failed! Duplicated title existed!<span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
            <div class="pagination-container col-12">
                <div id="listContent"></div>
                <br><br>
                <nav id="pageNav" aria-label="Page Navigation">
                    <div class="pagination justify-content-end">
                        <ul class="pagination">
                            <li class="page-item" data-page="-"><a class="page-link" href="#">Previous</a></li>
                            <li id="firstPage" class="page-item active" data-page="1"><a class="page-link" href="#">1</a></li>
                            <li class="page-item" data-page="+"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Edit Page -->
    <div id="editPage" class="container page pt-3 pb-3" style="display: none">
        <form class="editForm">
            <div class="form-row">
                <div class="col-6 form-group">
                    <label for="itemName">Movie Title</label>
                    <input type="text" class="form-control" id="itemName" placeholder="Enter movie title" required>
                </div>
                <div class="col-6 form-group">
                    <label for="itemYear">Movie Year</label>
                    <input type="text" class="form-control" id="itemYear" placeholder="Enter movie year" required>
                </div>
            </div>
            <div class="form-group">
                <label for="itemPoster">Movie Poster</label>
                <div id="reservedPoster"></div>
                <input type="text" class="form-control" id="itemPoster" placeholder="Enter movie poster url" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <!-- Add Page -->
    <div id="addPage" class="container page pt-3 pb-3" style="display: none">
        <form class="addForm">
            <div class="form-row">
                <div class="col-6 form-group">
                    <label for="itemName">Movie Title</label>
                    <input type="text" class="form-control" id="itemName" placeholder="Enter movie title" required>
                </div>
                <div class="col-6 form-group">
                    <label for="itemYear">Movie Year</label>
                    <input type="text" class="form-control" id="itemYear" placeholder="Enter movie year" required>
                </div>
            </div>
            <div class="form-group">
                <label for="itemPoster">Movie Poster</label>
                <div id="reservedPoster"></div>
                <input type="text" class="form-control" id="itemPoster" placeholder="Enter movie poster url" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Profile Page -->
    <div id="profilePage" class="container page pt-3 pb-3" style="display: none">
        <form class="addProfileForm">
            <div class="form-row">
                <div class="col-6 form-group">
                    <label for="profileUsername">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                        </div>
                        <input type="text" class="form-control" id="profileUsername" name="profileUsername" placeholder="Enter username" required autofocus>
                    </div>
                </div>
                <div id="unavailError" class="col-6 form-group text-danger" style="display: none">
                    <label></label>
                    <div class="input-group"></div>
                    <i class="fas fa-times"></i> The username is already existed.
                </div>
            </div>
            <div class="form-row">
                <div class="col-6 form-group">
                    <label for="profileFirstName">First Name</label>
                    <input type="text" class="form-control" id="profileFirstName" name="profileFirstName" placeholder="First Name" required>
                </div>
                <div class="col-6 form-group">
                    <label for="profileLastName">Last Name</label>
                    <input type="text" class="form-control" id="profileLastName" name="profileLastName" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 form-group">
                    <label for="profilePic">Profile Picture</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                        </div>
                        <input type="text" class="form-control" id="profilePic" name="profilePic" placeholder="Profile Picture" required>
                    </div>
                </div>
            </div>
            <!-- <div class="form-row">
                <div class="col-6 form-group">
                    <label for="profileOldPw">Current Password</label>
                    <input type="text" class="form-control" id="profileOldPw" name="profileOldPw" placeholder="Current Password" required>
                </div>
                <div class="col-6 form-group">
                    <label for="profileNewPw">New Password</label>
                    <input type="text" class="form-control" id="profileNewPw" name="profileNewPw" placeholder="New Password" required>
                </div>
            </div>
            <div class="form-row">
                <div id="unavailError" class="col-6 form-group text-danger" style="display: none">
                    <label></label>
                    <div class="input-group"></div>
                    <i class="fas fa-times"></i> The password is not correct.
                </div>
            </div> -->
            <div class="form-row">
                <div class="col-12 form-group">
                    <label for="profilePw">Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                        </div>
                        <input type="text" class="form-control" id="profilePw" name="profilePw" placeholder="Confirm Password" required>
                    </div>
                </div>
                <!-- <div id="unavailError" class="col-6 form-group text-danger" style="display: none">
                    <label></label>
                    <div class="input-group"></div>
                    <i class="fas fa-times"></i> Incorrect password.
                </div> -->
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
