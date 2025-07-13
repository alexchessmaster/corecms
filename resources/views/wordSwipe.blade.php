<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Word Swipe Game</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        canvas {
            border-radius: 16px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background: #ffffff33;
            color: white;
            cursor: pointer;
        }

        #scoreboard {
            margin-top: 10px;
            color: white;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <canvas id="gameCanvas" width="400" height="600"></canvas>
    <button id="startBtn">Start Game</button>
    <div id="scoreboard"></div>

    <script>
        const messagePromise = new Promise((resolve) => {
           // window.addEventListener("message", (event) => {
           //     console.log('Message received:', event.data);
            //    resolve(event.data);
            //});
        });
        async function startGame() {
            // const parentUrl = await messagePromise;
            // console.log('window', parentUrl)
            // if (!parentUrl) {
            //     console.log('iframe script not loaded!')
            //     const fullUrl = window?.parent[0]?.location?.href;
            //     const urlObj = new URL(fullUrl);
            //     parentUrl = urlObj.searchParams.get("url");
            // }
            const data = await fetch('/api/ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    btn: 'word_swipe',
                    url: "{{ request()->url }}",
                })
            }).then(res => {
                return res.json();
            }).then(data => {
                console.log('data', data);
                let jsonString = data.data.replace(/^```json\n|\n```$/g, '');
                console.log('jsonString before parsing:', jsonString);
                let parsedData;
                try {
                    parsedData = JSON.parse(jsonString);
                    // If `parsedData` is a string (i.e., the actual data is double-encoded)
                    if (typeof parsedData === 'string') {
                        parsedData = JSON.parse(parsedData);
                    }
                } catch (err) {
                    console.error('Failed to parse JSON:', err);
                    throw err;
                }
                console.log('Final parsed data:', parsedData);
                return parsedData;
            });

            data.fallSpeed = 2;
            data.wordFont = '20px Arial';
            data.duration = 30;

            const canvas = document.getElementById('gameCanvas');
            const ctx = canvas.getContext('2d');
            const scoreboard = document.getElementById('scoreboard');
            const startBtn = document.getElementById('startBtn');

            let words = [];
            let score = 0;
            let startTime = 0;
            let running = false;

            function getRandomWord() {
                const isRelated = Math.random() > 0.5;
                const word = isRelated ?
                    data.relatedWords[Math.floor(Math.random() * data.relatedWords.length)] :
                    data.unrelatedWords[Math.floor(Math.random() * data.unrelatedWords.length)];

                return {
                    text: word,
                    isRelated,
                    x: Math.random() * (canvas.width - 60),
                    y: -20,
                    opacity: 1,
                    scale: 1,
                    dying: false
                };
            }

            function drawWords() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.font = data.wordFont;

                for (let i = words.length - 1; i >= 0; i--) {
                    const word = words[i];

                    // Animate dying words
                    if (word.dying) {
                        word.opacity -= 0.05;
                        word.scale += 0.05;
                        if (word.opacity <= 0) {
                            words.splice(i, 1);
                            continue;
                        }
                    } else {
                        word.y += data.fallSpeed;
                    }

                    ctx.save();
                    ctx.globalAlpha = word.opacity;
                    ctx.translate(word.x, word.y);
                    ctx.scale(word.scale, word.scale);
                    ctx.fillStyle = 'white';
                    ctx.fillText(word.text, 0, 0);
                    ctx.restore();
                }
            }

            function checkClick(x, y) {
                for (let i = 0; i < words.length; i++) {
                    const word = words[i];
                    const width = ctx.measureText(word.text).width * word.scale;
                    const height = 20 * word.scale;

                    if (
                        x >= word.x && x <= word.x + width &&
                        y >= word.y - height && y <= word.y
                    ) {
                        if (!word.dying) {
                            if (word.isRelated) score++;
                            else score--;
                            word.dying = true;
                        }
                        break;
                    }
                }
            }

            canvas.addEventListener('pointerdown', (e) => {
                if (!running) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                checkClick(x, y);
            });

            function updateScoreboard(timeLeft) {
                scoreboard.textContent = `Score: ${score} | Time Left: ${Math.ceil(timeLeft)}s`;
            }

            async function gameLoop() {
                const elapsed = (Date.now() - startTime) / 1000;
                const timeLeft = data.duration - elapsed;

                if (timeLeft <= 0) {
                    running = false;
                    updateScoreboard(0);
                    alert(`Game Over! Your score is ${score}`);
                    return;
                }

                if (Math.random() < 0.03) {
                    words.push(getRandomWord());
                }

                drawWords();
                updateScoreboard(timeLeft);
                requestAnimationFrame(gameLoop);
            }

            startBtn.onclick = () => {
                score = 0;
                words = [];
                startTime = Date.now();
                running = true;
                gameLoop();
            };
        }

        startGame();
    </script>

</body>

</html>
