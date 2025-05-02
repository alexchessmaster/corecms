<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f0f0f0;
        }

        .controls {
            margin-bottom: 20px;
        }

        .game-board {
            display: grid;
            gap: 10px;
            perspective: 1000px;
        }

        .card {
            width: 80px;
            height: 80px;
            background: #333;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
            border-radius: 8px;
        }

        .card.flipped {
            transform: rotateY(180deg);
        }

        .card .front,
        .card .back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .card .front {
            background: #1e90ff;
        }

        .card .back {
            background: #fff;
            color: #333;
            transform: rotateY(180deg);
        }

        .message {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        button {
            margin-top: 10px;
            padding: 8px 16px;
            font-size: 16px;
            border: none;
            background-color: #1e90ff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        #timer {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <script>
        window.addEventListener("message", (event) => {
            window.parentUrl = event.data;
        });

        const setupGame = async () => {

            let parentUrl = window.parentUrl;
            if (!parentUrl) {
                console.log('iframe script not loaded!')
                const fullUrl = window?.parent[0]?.location?.href;
                const urlObj = new URL(fullUrl);
                parentUrl = urlObj.searchParams.get("url");
            }
            const words = await fetch('/api/ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    btn: 'match_pare',
                    url: parentUrl,
                })
            }).then(res => {
                return res.json();
            }).then(data => {
                // console.log('res 2', data);
                const jsonCompatible = data.data.replace(/'/g, '"');
                return JSON.parse(jsonCompatible);
            })

            const rows = parseInt(document.getElementById('rows').value);
            const cols = parseInt(document.getElementById('cols').value);
            const totalCards = rows * cols;
            if (totalCards % 2 !== 0) {
                alert("Please make sure total cards (rows x cols) is an even number.");
                return;
            }

            const gameBoard = document.getElementById('gameBoard');
            gameBoard.innerHTML = '';
            gameBoard.style.gridTemplateColumns = `repeat(${cols}, 80px)`;
            const selectedWords = shuffle(words).slice(0, totalCards / 2);
            const cardWords = shuffle([...selectedWords, ...selectedWords]);

            matchedPairs = 0;
            totalPairs = totalCards / 2;
            document.getElementById('message').textContent = '';
            document.getElementById('restartBtn').style.display = 'none';
            clearInterval(timerInterval);
            startTimer();

            cardWords.forEach(word => {
                gameBoard.appendChild(createCard(word));
            });
        };

        let firstCard = null;
        let lockBoard = false;
        let matchedPairs = 0;
        let totalPairs = 0;
        let timerInterval;
        let timeLeft = 60;

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function createCard(word) {
            const card = document.createElement('div');
            card.classList.add('card');
            card.innerHTML = `
          <div class="front"></div>
          <div class="back">${word}</div>
        `;
            card.dataset.word = word;
            card.addEventListener('click', flipCard);
            return card;
        }

        function flipCard() {
            if (lockBoard || this === firstCard || this.classList.contains('flipped')) return;
            this.classList.add('flipped');

            if (!firstCard) {
                firstCard = this;
            } else {
                const secondCard = this;
                lockBoard = true;
                if (firstCard.dataset.word === secondCard.dataset.word) {
                    firstCard.removeEventListener('click', flipCard);
                    secondCard.removeEventListener('click', flipCard);
                    matchedPairs++;
                    if (matchedPairs === totalPairs) {
                        clearInterval(timerInterval);
                        document.getElementById('message').textContent = '🎉 Congratulations! You won!';
                        document.getElementById('restartBtn').style.display = 'inline';
                    }
                    resetBoard();
                } else {
                    setTimeout(() => {
                        firstCard.classList.remove('flipped');
                        secondCard.classList.remove('flipped');
                        resetBoard();
                    }, 1000);
                }
            }
        }

        function resetBoard() {
            [firstCard, lockBoard] = [null, false];
        }

        function startTimer() {
            const inputTime = parseInt(document.getElementById('timeInput').value);
            timeLeft = isNaN(inputTime) ? 60 : inputTime;

            document.getElementById('timer').textContent = `Time: ${timeLeft}s`;
            timerInterval = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').textContent = `Time: ${timeLeft}s`;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    lockBoard = true;
                    document.getElementById('message').textContent = "⏰ Time's up! You lost!";
                    document.getElementById('restartBtn').style.display = 'inline';
                }
            }, 1000);
        }
    </script>

    <div class="controls">
        <label>Rows: <input type="number" id="rows" value="2" min="2" max="6"></label>
        <label>Cols: <input type="number" id="cols" value="3" min="2" max="6"></label>
        <label>Time (s): <input type="number" id="timeInput" value="60" min="10" max="300"></label>
        <button onclick="setupGame()">Start Game</button>
    </div>
    <div id="timer">Time: 60s</div>
    <div class="game-board" id="gameBoard"></div>
    <div class="message" id="message"></div>
    <button id="restartBtn" onclick="setupGame()" style="display: none;">Restart</button>

</body>

</html>
