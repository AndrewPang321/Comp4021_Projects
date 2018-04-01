var rightPressed = false;
var leftPressed = false;
var upPressed = false;
var downPressed = false;
var gameover = false;

// Get back the current x-coordinate value in px
function getComputedTranslateX(value)
{
    var mat = value.match(/^matrix\((.+)\)$/);
    return mat ? parseFloat(mat[1].split(', ')[4]) : -1;
}

// Get back the current y-coordinate value in px
function getComputedTranslateY(value)
{
    var mat = value.match(/^matrix\((.+)\)$/);
    return mat ? parseFloat(mat[1].split(', ')[5]) : -1;
}

document.addEventListener("keydown", keyDownHandler, false);
// document.addEventListener("keyup", keyUpHandler, false);

// Handling the movement of up, down, left, right when key pressed
function keyDownHandler(event) {
    if (!gameover) {
        if (event.keyCode == 39) {
            rightPressed = true;
            console.log("right clicked");
            $('#player').css("transform", function(index, value) {
                var transformX = getComputedTranslateX(value);
                var transformY = getComputedTranslateY(value);
                if (transformX == -1 || transformY == -1) console.log("getComputedTranslate value error: -1");
                if (transformX < 720) {
                    transformX += 10;
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";  
                } else {
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                }
            });
        }
        else if (event.keyCode == 37) {
            leftPressed = true;
            console.log("left clicked");
            $('#player').css("transform", function(index, value) {
                var transformX = getComputedTranslateX(value);
                var transformY = getComputedTranslateY(value);
                if (transformX == -1 || transformY == -1) console.log("getComputedTranslate value error: -1");
                if (transformX > 10) {
                    transformX -= 10;
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                } else {
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                }
            });
        }
        else if (event.keyCode == 40) {
            downPressed = true;
            console.log("down clicked");
            $('#player').css("transform", function(index, value) {
                var transformX = getComputedTranslateX(value);
                var transformY = getComputedTranslateY(value);
                if (transformX == -1 || transformY == -1) console.log("getComputedTranslate value error: -1");
                if (transformY < 335) {
                    transformY += 10;
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                } else {
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                }
            });
        }
        else if(event.keyCode == 38) {
            upPressed = true;
            console.log("up clicked");
            $('#player').css("transform", function(index, value) {
                var transformX = getComputedTranslateX(value);
                var transformY = getComputedTranslateY(value);
                if (transformX == -1 || transformY == -1) console.log("getComputedTranslate value error: -1");
                if (transformY > 0) {
                    transformY -= 10;
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                } else {
                    return "translate(" + transformX + "px," + transformY + "px) scale(0.12, 0.12)";
                }
            });
        }
    }
}

function makeObstacle() {
    // Start the animation after a random time
    rect_1 = setTimeout(function() {
        $("#rect_1").css("animationPlayState", "running");
    }, Math.random() * 2000);
    
    circle_1 = setTimeout(function() {
        $("#circle_1").css("animationPlayState", "running");
    }, Math.random() * 2000);

    spinner_1 = setTimeout(function() {
        $("#spinner_1").css("animationPlayState", "running");
    }, Math.random() * 2000);
}

// Simple collision checking
function checkBoundingBoxIntersect(obj1, obj2) {
    var obj1 = obj1.getBoundingClientRect();
    var obj2 = obj2.getBoundingClientRect();

    // Check if two bounding boxes overlap
    return !(obj2.left > obj1.right || obj2.right < obj1.left || obj2.top > obj1.bottom || obj2.bottom < obj1.top);
}

// Advanced collision checking
// Credit: Jonas Raoni Soares Silva
// @ http://jsfromhell.com/math/is-point-in-poly [rev. #0]
function isPointInPoly(poly, pt){
    for(var c = false, i = -1, l = poly.length, j = l - 1; ++i < l; j = i)
        ((poly[i].y <= pt.y && pt.y < poly[j].y) || (poly[j].y <= pt.y && pt.y < poly[i].y))
        && (pt.x < (poly[j].x - poly[i].x) * (pt.y - poly[i].y) / (poly[j].y - poly[i].y) + poly[i].x)
        && (c = !c);
    return c;
}

function stopAnimation() {
    gameover = true;      
    $("#rect_1").css("animationPlayState", "paused");
    $("#circle_1").css("animationPlayState", "paused");
    $("#spinner_1").css("animationPlayState", "paused");
}

// Check if there is collision between player and obstacles
function checkGameover() {
    var player = $("#player").get(0);
    var rect_1 = $("#rect_1").get(0);
    var circle_1 = $("#circle_1").get(0);
    var spinner_1 = $("#spinner_1").get(0);
    
    if (checkBoundingBoxIntersect(player, rect_1)) {
        stopAnimation();
    } else if (checkBoundingBoxIntersect(player, circle_1)) {
        stopAnimation();
    } else if (checkBoundingBoxIntersect(player, spinner_1)) {
        stopAnimation();
    } else {
        requestAnimationFrame(checkGameover);
    }

    // Player x,y coordinate
    // var player = $("#player").css("transform");
    // var player_x = parseFloat(player.split(" ")[4]);
    // var player_y = parseFloat(player.split(" ")[5]);
    // console.log("player: " + player);
    // console.log($("#player").get(0).getBoundingClientRect());

    // Obstacle rect_1 x,y coordinate
    // var rect_1 = $("#rect_1").css("transform");
    // var rect_1_x = parseFloat(rect_1.split(" ")[4]);
    // var rect_1_y = parseFloat(rect_1.split(" ")[5]);
    // console.log("rect_1: " + rect_1);

    // if (player_x == rect_1_x && player_y == rect_1_y) {
    //     $("#rect_1").css("animationPlayState", "paused");
    //     $("#circle_1").css("animationPlayState", "paused");
    //     $("#spinner_1").css("animationPlayState", "paused");
    // } else {
    //     requestAnimationFrame(checkGameover);
    // }
}

$(document).ready(function() {
    // Start the obstacle animation
    makeObstacle();

    // Start the game over checking
    requestAnimationFrame(checkGameover);
});

// function keyUpHandler(e) {
//     if(event.keyCode == 39) {
//         rightPressed = false;
//     }
//     else if(event.keyCode == 37) {
//         leftPressed = false;
//     }
//     if(event.keyCode == 40) {
//     	downPressed = false;
//     }
//     else if(event.keyCode == 38) {
//     	upPressed = false;
//     }
// }
