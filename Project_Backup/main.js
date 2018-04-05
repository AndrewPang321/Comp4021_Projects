var rightPressed = false;
var leftPressed = false;
var upPressed = false;
var downPressed = false;
var spacePressed = false;
var gamestart = false;
var gameover = false;
var bulletReload = false;   // The bullet can only be fired in every 2s
var reloading;  // For storing the setInterval() in order to clear it
var lives = 3;  // Player's lives
var score = 0;  // Game score
var countdownNumberEl = document.getElementById('countdown-number');
var countdown = 180;

function playBulletCollisionSound() {
    $("audio")[1].currentTime = 0;
    $("audio")[1].play();
}

function playBulletSound() {
    $("audio")[0].currentTime = 0;
    $("audio")[0].play();
}

function playloseLivesSound() {
    $("audio")[2].currentTime = 0;
    $("audio")[2].play();
}

function playBgStartSound() {
    $("audio")[3].currentTime = 0;
    $("audio")[3].play();
}

function playBgEndSound() {
    $("audio")[4].currentTime = 0;
    $("audio")[4].play();
}

// Get back the current x-coordinate value in px
function showStart()
{
    playBgStartSound();
    document.getElementById("StartScreen").style.display="block";
    document.getElementById("EndScreen").style.display="none";
    document.getElementById("IntroScreen").style.display="none";
}
function start()
{
    $("audio")[3].pause();
    $("audio")[4].pause();
    gamestart = true;
    document.getElementById("StartScreen").style.display="none";
    document.getElementById("EndScreen").style.display="none";
    makeObstacle();
    // Timer
    timer = setInterval(function() {
        countdown = --countdown <= 0 ? 10 : countdown;

        document.getElementById("countdown-number").textContent = countdown;
    }, 1000);
    $("#countdown-circle").css("animationPlayState", "running");
    requestAnimationFrame(checkGameover);
    gameover = false;
}
function end()
{
    playBgEndSound();
    document.getElementById("gameScore").innerHTML = ("Your Score: " + score);
    document.getElementById("EndScreen").style.display="block";
    $("#countdown-circle").css("animationPlayState", "paused");
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
    if (!gameover && gamestart) {
        if (event.keyCode == 32) {
            spacePressed = true;
            console.log("space clicked");
            // Check whether reload is completed
            if (!bulletReload) {
                playBulletSound();
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

function hideObstacleAndRestart(obstacle) {
    // Hide Obstacle
    obstacle.css("display", "none");
    obstacle.css("animationPlayState", "paused");
    // Restart animation after random seconds
    setTimeout(function() {
        restartAnimation(obstacle);
    }, Math.random() * 3000);
}

function updateLives() {
    // Collision happened, decrement number of available lives
    lives--;
    playloseLivesSound();
    if (lives == 2) {
        $("#life3").css("fill", "#a6a6a6");
        $("#heart_3").css("animationPlayState", "running");
    } else if (lives == 1) {
        $("#life2").css("fill", "#a6a6a6");
        $("#heart_2").css("animationPlayState", "running");
    } else if (lives == 0) {
        $("#life1").css("fill", "#a6a6a6");
        $("#heart_1").css("animationPlayState", "running");
    }
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
    clearInterval(timer);
    $("#gameover").css("animationPlayState", "running");
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
    restartAnimation(obstacle);
    // setTimeout(function() {
    //     restartAnimation(obstacle);
    // }, Math.random() * 2000);
}

function updateScore() {
    score += 5;
    document.getElementById("score_number").textContent = score;
    // Animation for updating the score
    $.keyframe.define({
        name: 'score-update',
        '0%': {
            'transform': 'scale(1, 1)'
        },
        '50%': {
            'transform': 'scale(1.05, 1.05)'
        },
        '100%': {
            'transform': 'scale(1, 1)'
        }
    });
    // Run the dynamically-defined keyframe
    $('#score_number').playKeyframe({
        name: 'score-update',
        duration: '1s',
        fillMode: 'forwards'
    });
}

function checkBulletCollision() {
    if ($("#bullet").css("display") == "block") {
        var bullet = $("#bullet").get(0);
        var rect_1 = $("#rect_1").get(0);
        var rect_2 = $("#rect_2").get(0);
        var rect_3 = $("#rect_3").get(0);
        var rect_4 = $("#rect_4").get(0);

        if (checkBoundingBoxIntersect(bullet, rect_1)) {
            playBulletCollisionSound();
            bulletObstacleCollisionAnimation($("#bullet"), $("#rect_1"));
            updateScore();
        } else if (checkBoundingBoxIntersect(bullet, rect_2)) {
            playBulletCollisionSound();
            bulletObstacleCollisionAnimation($("#bullet"), $("#rect_2"));
            updateScore();
        } else if (checkBoundingBoxIntersect(bullet, rect_3)) {
            playBulletCollisionSound();
            bulletObstacleCollisionAnimation($("#bullet"), $("#rect_3"));
            updateScore();
        } else if (checkBoundingBoxIntersect(bullet, rect_4)) {
            playBulletCollisionSound();
            bulletObstacleCollisionAnimation($("#bullet"), $("#rect_4"));
            updateScore();
        }
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
        updateLives();
        if (lives == 0) {
            stopAnimation();
        } else {
            hideObstacleAndRestart($("#rect_1"));
            requestAnimationFrame(checkBulletCollision);
            requestAnimationFrame(checkGameover);
        }
    } else if (checkBoundingBoxIntersect(player, rect_2)) {
        updateLives();
        if (lives == 0) {
            stopAnimation();
        } else {
            hideObstacleAndRestart($("#rect_2"));
            requestAnimationFrame(checkBulletCollision);
            requestAnimationFrame(checkGameover);
        }
    } else if (checkBoundingBoxIntersect(player, rect_3)) {
        updateLives();
        if (lives == 0) {
            stopAnimation();
        } else {
            hideObstacleAndRestart($("#rect_3"));
            requestAnimationFrame(checkBulletCollision);
            requestAnimationFrame(checkGameover);
        }
    } else if (checkBoundingBoxIntersect(player, rect_4)) {
        updateLives();
        if (lives == 0) {
            stopAnimation();
        } else {
            hideObstacleAndRestart($("#rect_4"));
            requestAnimationFrame(checkBulletCollision);
            requestAnimationFrame(checkGameover);
        }
    } else {
        requestAnimationFrame(checkBulletCollision);
        requestAnimationFrame(checkGameover);
    }
}

function instruction()
{
  document.getElementById("StartScreen").style.display="none";
  document.getElementById("IntroScreen").style.display="block";
}

function returnToStart()
{
  document.getElementById("StartScreen").style.display="block";
  document.getElementById("IntroScreen").style.display="none";
}

$(document).ready(function() {
    // Start the obstacle animation
    showStart();

    // makeObstacle();

    // Start the game over checking
    // requestAnimationFrame(checkGameover);
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
