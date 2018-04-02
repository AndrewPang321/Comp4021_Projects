var rightPressed = false;
var leftPressed = false;
var upPressed = false;
var downPressed = false;
var spacePressed = false;
var gameover = false;
var bulletReload = false;   // The bullet can only be fired in every 2s
var reloading;  // For storing the setInterval() in order to clear it
var countdownNumberEl = document.getElementById('countdown-number');
var countdown = 180;

// Get back the current x-coordinate value in px
function showStart()
{
  document.getElementById("StartScreen").style.display="block";
  document.getElementById("EndScreen").style.display="none";
}
function start()
{
  document.getElementById("StartScreen").style.display="none";
  document.getElementById("EndScreen").style.display="none";
  makeObstacle();
  requestAnimationFrame(checkGameover);
  gameover=false;

}
function end()
{
  document.getElementById("EndScreen").style.display="block";
  // // $("#rect_1").y=300;
  // // $("#rect_1").x=200;
  // // $("#rect_2").y=70;
  // // $("#rect_2").x=20;
  // // $("#rect_3").y=100;
  // // $("#rect_3").x=300;
  // // $("#rect_4").y=200;
  // // $("#rect_4").x=600;
  restartAnimation($("#rect_1"));
  restartAnimation($("#rect_2"));
  restartAnimation($("#rect_3"));
  restartAnimation($("#rect_4"));
  $("#rect_1").css("animationPlayState", "paused");
  $("#rect_2").css("animationPlayState", "paused");
  $("#rect_3").css("animationPlayState", "paused");
  $("#rect_4").css("animationPlayState", "paused");
}
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

function reset()
{
  location.reload();
}

// document.addEventListener("keyup", keyUpHandler, false);
document.addEventListener("keydown", keyDownHandler, false);
// Handling the movement of up, down, left, right when key pressed
function keyDownHandler(event) {
    if (!gameover) {
        if (event.keyCode == 32) {
            spacePressed = true;
            console.log("space clicked");
            // Check whether reload is completed
            if (!bulletReload) {
                $('#player').css("transform", function(index, value) {
                    var transformX = getComputedTranslateX(value) + 65;
                    var transformY = getComputedTranslateY(value) + 30;
                    if (transformX == -1 || transformY == -1) {
                        console.log("getComputedTranslate value error: -1")
                    } else {
                        // Define keyframe css animation dynamically by jquery.keyframes
                        $.keyframe.define({
                            name: 'fire-bullet',
                            from: {
                                'transform': 'translate(' + transformX + 'px,' + transformY + 'px)'
                            },
                            to: {
                                'transform': 'translate(100%,' + transformY + 'px)'
                            }
                        });
                        // To show the bullet according to the airplane's location
                        $('#bullet').css({
                            "display": "block",
                            "transform": "translate(" + transformX + "px," + transformY + "px)"
                        });
                        // Run the dynamically-defined keyframe
                        $('#bullet').playKeyframe({
                            name: 'fire-bullet',
                            duration: '2s',
                            timingFunction: 'linear',
                            fillMode: 'forwards'
                        });
                        // Reload the bullet
                        bulletReload = true;
                        reloading = setInterval(function(){
                            console.log('reloading bullet...');
                            // Reload is finished
                            bulletReload = false;
                            clearInterval(reloading);
                        },2000);
                    }
                });
            }
        }
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

    rect_2 = setTimeout(function() {
        $("#rect_2").css("animationPlayState", "running");
    }, Math.random() * 2000);

    rect_3 = setTimeout(function() {
        $("#rect_3").css("animationPlayState", "running");
    }, Math.random() * 2000);

    rect_4 = setTimeout(function() {
        $("#rect_4").css("animationPlayState", "running");
    }, Math.random() * 2000);
}

// Simple collision checking
function checkBoundingBoxIntersect(obj1, obj2) {
    var obj1 = obj1.getBoundingClientRect();
    var obj2 = obj2.getBoundingClientRect();

    // Check if two bounding boxes overlap
    return !(obj2.left > obj1.right || obj2.right < obj1.left || obj2.top > obj1.bottom || obj2.bottom < obj1.top);
}

function stopAnimation() {
    gameover = true;
    $("#rect_1").css("animationPlayState", "paused");
    $("#rect_2").css("animationPlayState", "paused");
    $("#rect_3").css("animationPlayState", "paused");
    $("#rect_4").css("animationPlayState", "paused");
    $("#bullet").pauseKeyframe();
    $("#bullet").css("display", "none");
    end();
}

function restartAnimation(obj) {
    var element = obj;
    var newone = element.clone(true);
    newone.css("display", "block");
    newone.css("animationPlayState", "running");
    element.before(newone);
    element.remove();
}

function restartPlayer(obj) {
    var element = obj;
    var newone = element.clone(true);
    element.before(newone);
    element.remove();
}

// Helper function on animation of bullet-obstacle collsion
function bulletObstacleCollisionAnimation(bullet, obstacle) {
    // Reload bullet immediately
    clearInterval(reloading);
    bulletReload = false;
    // Hide Obstacle
    obstacle.css("display", "none");
    obstacle.css("animationPlayState", "paused");
    // Hide Bullet
    bullet.pauseKeyframe();
    bullet.css("display", "none");
    // Restart animation after random seconds
    setTimeout(function() {
        restartAnimation(obstacle);
    }, Math.random() * 2000);
}

function checkBulletCollision() {
    var bullet = $("#bullet").get(0);
    var rect_1 = $("#rect_1").get(0);
    var rect_2 = $("#rect_2").get(0);
    var rect_3 = $("#rect_3").get(0);
    var rect_4 = $("#rect_4").get(0);

    if (checkBoundingBoxIntersect(bullet, rect_1)) {
        bulletObstacleCollisionAnimation($("#bullet"), $("#rect_1"));
    } else if (checkBoundingBoxIntersect(bullet, rect_2)) {
        bulletObstacleCollisionAnimation($("#bullet"), $("#rect_2"));
    } else if (checkBoundingBoxIntersect(bullet, rect_3)) {
        bulletObstacleCollisionAnimation($("#bullet"), $("#rect_3"));
    } else if (checkBoundingBoxIntersect(bullet, rect_4)) {
        bulletObstacleCollisionAnimation($("#bullet"), $("#rect_4"));
    }
}

// Check if there is collision between player and obstacles
function checkGameover() {
    var player = $("#player").get(0);
    var rect_1 = $("#rect_1").get(0);
    var rect_2 = $("#rect_2").get(0);
    var rect_3 = $("#rect_3").get(0);
    var rect_4 = $("#rect_4").get(0);

    if (checkBoundingBoxIntersect(player, rect_1)) {
        stopAnimation();
    } else if (checkBoundingBoxIntersect(player, rect_2)) {
        stopAnimation();
    } else if (checkBoundingBoxIntersect(player, rect_3)) {
        stopAnimation();
    } else if (checkBoundingBoxIntersect(player, rect_4)) {
        stopAnimation();
    } else {
        requestAnimationFrame(checkBulletCollision);
        requestAnimationFrame(checkGameover);
    }
}

$(document).ready(function() {
    // Start the obstacle animation
    showStart();

    // Start the game over checking
    //requestAnimationFrame(checkGameover);
});

//timer
setInterval(function() {
  countdown = --countdown <= 0 ? 10 : countdown;

  document.getElementById("countdown-number").textContent = countdown;
}, 1000);
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
