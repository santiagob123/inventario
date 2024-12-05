<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Snake</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: radial-gradient(circle, #0f0f0f, #1a1a1a, #222);
            margin: 0;
            color: white;
            font-family: Arial, sans-serif;
            position: relative;
        }

        canvas {
            border: 2px solid white;
            border-radius: 10px;
        }

        #score {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .game-container {
            text-align: center;
        }

        .btn-back,
        .btn-restart {
            position: absolute;
            top: 20px;
            background-color: #004085;
            border-color: #004085;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover,
        .btn-restart:hover {
            background-color: #003366;
            transform: scale(1.05);
        }

        .btn-restart {
            left: 120px;
        }
    </style>
</head>
<body>
 
    <a href="javascript:void(0);" class="btn-restart" onclick="restartGame()">Reiniciar</a>
    <div class="game-container">
        <div id="score">Puntuación: 0</div>
        <canvas id="snakeGame" width="400" height="400"></canvas>
    </div>

    <script>
        const canvas = document.getElementById("snakeGame");
        const ctx = canvas.getContext("2d");

        const box = 20;
        let score = 0;
        let snake;
        let food;
        let d;
        let game;

        function initGame() {
            snake = [{ x: 9 * box, y: 9 * box }];
            food = {
                x: Math.floor(Math.random() * 19) * box,
                y: Math.floor(Math.random() * 19) * box,
            };
            d = "RIGHT"; // Dirección inicial
            score = 0;
            document.getElementById("score").textContent = "Puntuación: " + score;
        }

        document.addEventListener("keydown", direction);

        function direction(event) {
            if (event.keyCode === 37 && d !== "RIGHT") {
                d = "LEFT";
            } else if (event.keyCode === 38 && d !== "DOWN") {
                d = "UP";
            } else if (event.keyCode === 39 && d !== "LEFT") {
                d = "RIGHT";
            } else if (event.keyCode === 40 && d !== "UP") {
                d = "DOWN";
            }
        }

        function drawGame() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < snake.length; i++) {
                ctx.fillStyle = i === 0 ? "green" : "white";
                ctx.fillRect(snake[i].x, snake[i].y, box, box);
            }

            ctx.fillStyle = "red";
            ctx.fillRect(food.x, food.y, box, box);

            let snakeX = snake[0].x;
            let snakeY = snake[0].y;

            if (d === "LEFT") snakeX -= box;
            if (d === "UP") snakeY -= box;
            if (d === "RIGHT") snakeX += box;
            if (d === "DOWN") snakeY += box;

            if (snakeX === food.x && snakeY === food.y) {
                score++;
                document.getElementById("score").textContent = "Puntuación: " + score;
                food = {
                    x: Math.floor(Math.random() * 19) * box,
                    y: Math.floor(Math.random() * 19) * box,
                };
            } else {
                snake.pop();
            }

            const newHead = { x: snakeX, y: snakeY };

            if (
                snakeX < 0 ||
                snakeX >= canvas.width ||
                snakeY < 0 ||
                snakeY >= canvas.height ||
                collision(newHead, snake)
            ) {
                clearInterval(game);
                alert("¡Game Over!");
            }

            snake.unshift(newHead);
        }

        function collision(head, array) {
            for (let i = 0; i < array.length; i++) {
                if (head.x === array[i].x && head.y === array[i].y) {
                    return true;
                }
            }
            return false;
        }

        function startGame() {
            game = setInterval(drawGame, 100);
        }

        function restartGame() {
            clearInterval(game); // Detener el juego
            initGame();           // Inicializar el juego nuevamente
            startGame();          // Iniciar el juego desde el principio
        }

        initGame();
        startGame();
    </script>
</body>
</html>
