<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>True or False Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        #game,
        #results {
            display: none;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .question {
            font-size: 1.5rem;
            margin: 20px 0;
        }

        .btn {
            padding: 12px 24px;
            margin: 10px;
            font-size: 1.1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .true {
            background-color: #4caf50;
            color: white;
        }

        .false {
            background-color: #f44336;
            color: white;
        }

        .start-btn {
            background-color: #ffffff;
            color: #333;
        }

        .result-item {
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            font-size: 1.1rem;
        }

        .correct {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .wrong {
            background-color: #ffcdd2;
            color: #c62828;
        }

        #timer {
            margin-top: 10px;
            font-size: 1rem;
        }
    </style>
</head>

<body>

    <div id="start-screen">
        <h1>True or False Game</h1>
        <button class="btn start-btn" id="startBtn">Start Game</button>
    </div>

    <div id="game">
        <div id="timer">Time: 0s</div>
        <div class="question" id="question"></div>
        <div>
            <button class="btn true" id="trueBtn">True</button>
            <button class="btn false" id="falseBtn">False</button>
        </div>
    </div>

    <div id="results">
        <h2>Results</h2>
        <div id="time-taken"></div>
        <div id="answer-list"></div>
    </div>
    
    <script>
        const messagePromise = new Promise((resolve) => {
            window.addEventListener("message", (event) => {
                console.log('Message received:', event.data);
                resolve(event.data);
            });
        });
        let startGame, answer;
        (async () => {

            let sentences = [];

            const init = async() => {
                const parentUrl = await messagePromise;
                console.log('window', parentUrl)
                if (!parentUrl) {
                    console.log('iframe script not loaded!')
                    const fullUrl = window?.parent[0]?.location?.href;
                    const urlObj = new URL(fullUrl);
                    parentUrl = urlObj.searchParams.get("url");
                }
                sentences = await fetch('/api/ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        btn: 'true_or_false',
                        url: parentUrl,
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
            };


            // [{
            //         text: "The sky is blue.",
            //         answer: true
            //     },
            //     {
            //         text: "2 + 2 = 5.",
            //         answer: false
            //     },
            //     {
            //         text: "JavaScript is the same as Java.",
            //         answer: false
            //     },
            //     {
            //         text: "The Earth revolves around the Sun.",
            //         answer: true
            //     },
            //     {
            //         text: "HTML stands for Hot Mail Link.",
            //         answer: false
            //     }
            // ];

            let currentIndex = 0;
            let startTime, interval;
            let userAnswers = [];

            const startScreen = document.getElementById('start-screen');
            const game = document.getElementById('game');
            const question = document.getElementById('question');
            const results = document.getElementById('results');
            const answerList = document.getElementById('answer-list');
            const timerEl = document.getElementById('timer');
            const timeTakenEl = document.getElementById('time-taken');

            function showQuestion() {
                if (currentIndex < sentences.length) {
                    question.textContent = sentences[currentIndex].text;
                } else {
                    endGame();
                }
            }

            function endGame() {
                clearInterval(interval);
                game.style.display = 'none';
                results.style.display = 'flex';

                const totalTime = Math.floor((Date.now() - startTime) / 1000);
                timeTakenEl.textContent = `You finished in ${totalTime} seconds!`;

                answerList.innerHTML = '';
                userAnswers.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'result-item ' + (item.correctAnswer === item.userAnswer ? 'correct' :
                        'wrong');
                    div.textContent =
                        `"${item.question}" — Your answer: ${item.userAnswer ? 'True' : 'False'}`;
                    answerList.appendChild(div);
                });
            }

            // Define functions and assign event listeners
            startGame = async() => {
                await init();
                currentIndex = 0;
                userAnswers = [];
                startTime = Date.now();
                timerEl.textContent = "Time: 0s";

                startScreen.style.display = 'none';
                results.style.display = 'none';
                game.style.display = 'flex';

                interval = setInterval(() => {
                    const elapsed = Math.floor((Date.now() - startTime) / 1000);
                    timerEl.textContent = `Time: ${elapsed}s`;
                }, 1000);

                showQuestion();
            };

            answer = function(choice) {
                userAnswers.push({
                    question: sentences[currentIndex].text,
                    correctAnswer: sentences[currentIndex].answer,
                    userAnswer: choice
                });
                currentIndex++;
                showQuestion();
            };

            // Add event listeners instead of using inline onclick
            document.getElementById('startBtn').addEventListener('click', startGame);
            document.getElementById('trueBtn').addEventListener('click', () => answer(true));
            document.getElementById('falseBtn').addEventListener('click', () => answer(false));
        })();
    </script>
</body>

</html>
