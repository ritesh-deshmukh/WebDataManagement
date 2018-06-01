// WDM Project 1: Pong Game
// Name: Ritesh Deshmukh
// ID: 1001173911

function resetPong(){
        location.reload();
        //startBall();
    }

    // Function to keep score
    function scorekeep(){
        document.getElementById("score").innerHTML = count;
    }

    function maxscorekeep(){
        document.getElementById("maxscoreid").innerHTML = maxcount;
    }

    var count = 0;
    var maxcount = 0;
    var ballRadius = 35;
    var ballX = 300;
    var ballY = 300;
    var speedX = 0;
    var speedY = 0;
    var paddlesizeH = 150;
    var paddlesizeW = 10;
    var paddlespeed = 0;
    var paddlepos = 200;


    var slow1 = document.getElementById("slow");
    var medium1 = document.getElementById("medium");
    var fast1 = document.getElementById("fast");
    // var score = document.getElementById("scorecount").style.color;

    // defining keycodes for the paddle to move up and down
    document.addEventListener('keydown',function(e){
        if (e.keyCode == 38 || e.which == 38) {
            paddlepos -= 60;
            document.getElementById("paddle").style.top = (paddlepos) + "px"
        }
        if (e.keyCode == 40 || e.which == 40) {
            paddlepos += 60;
            document.getElementById("paddle").style.top = (paddlepos) + "px"
        }
    }, false);

    // for smoother paddles and reduce the up and downforce
    document.addEventListener('keyup', function(e){
        if (e.keyCode == 38 || e.which == 38){
            paddlespeed = 0;
        }
        if (e.keyCode == 40 || e.which == 40){
            paddlespeed = 0;
        }
    }, false);

    // to keep the paddle inside the pongarea
    window.setInterval(function main(){
        if (paddlepos <= 0) {
                paddlepos = 0;
            }
            if (paddlepos > 600 - paddlesizeH) {
                paddlepos = 600 - paddlesizeH;
            }
    }, 1000/60)


         function startPong() {
             if (slow1.checked == true || medium1.checked == true || fast1.checked == true) {
                 startBall();
             } else {
                 location.reload();
             }

                 // for smoother paddles
                 window.setInterval(function main() {
                     paddlepos += paddlespeed;

                     ballY += speedY;
                     ballX += speedX;

                     document.getElementById("paddle").style.top = (paddlepos) + "px";
                     document.getElementById("ball").style.top = (ballY) + "px";
                     document.getElementById("ball").style.left = (ballX) + "px";

                     // to keep the paddle inside the pongarea
                     if (paddlepos <= 0) {
                         paddlepos = 0;
                     }
                     if (paddlepos > 600 - paddlesizeH) {
                         paddlepos = 600 - paddlesizeH;
                     }

                     // to make the ball bounce off the top and the bottom of the container pongarea
                     if (ballY <= 0 || ballY >= 600 - ballRadius) {
                         // alert(1);
                         speedY = -speedY
                     }

                     // to make the ball bounce off the left side of the pongarea
                     if (ballX <= paddlesizeW) {
                         if (ballY > 0) {
                             speedX = -speedX;
                             // alert(1);
                         } else {
                             stopBall();
                         }
                     }

                     // to make the ball bounce on the paddle while it moves
                     if (ballX >= 600 - ballRadius - paddlesizeW) {

                         if (ballY > paddlepos && ballY < paddlepos + paddlesizeH) {
                             speedX = -speedX;
                             // alert(1);
                             count++;
                             scorekeep();
                             maxscorekeep();
                         } else {
                             //ballY = 300;
                             //ballX = 300;
                             stopBall();
                             // startBall();
                             // clearInterval(startBall());
                         }
                     }

                 }, 1000 / 60);
                 // clearInterval(startBall());
         }

    function startBall(){
        ballY = 300;
        ballX = 300;

        if (Math.random() < 0.5){
            var chooserandnum = 1;
        }
        else{
            var chooserandnum = -1;
        }
            if (medium1.checked == false && fast1.checked == false){
            speedY = Math.random() * -5 -3;
            speedX = chooserandnum * (Math.random() * 2 + 2);
            }
            else if (slow1.checked == false && fast1.checked == false){
            speedY = Math.random() * -5 -3;
            speedX = chooserandnum * (Math.random() * 2 + 6);
            }
            else if (slow1.checked == false && medium1.checked == false){
            speedY = Math.random() * -5 -3;
            speedX = chooserandnum * (Math.random() * 2 + 10);
        }
    }

    function stopBall()
    {
        ballX = 300;
        ballY = 300;

        speedX = 0;
        speedY = 0;

        resetPong();

    }
