var rightPressed = false;
var leftPressed = false;
var upPressed = false;
var downPressed = false;

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
    if(event.keyCode == 39) {
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
    else if(event.keyCode == 37) {
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
    if(event.keyCode == 40) {
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
