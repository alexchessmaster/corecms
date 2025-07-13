<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fill-in-the-Blank Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background-color: #f4f7f9;
        }

        h2 {
            margin-bottom: 10px;
        }

        .sentence {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .blank {
            display: inline-block;
            width: 120px;
            min-height: 30px;
            border: 2px dashed #ccc;
            background-color: #fff;
            margin: 0 5px;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            border-radius: 6px;
            transition: border-color 0.3s;
            cursor: pointer;
        }

        .blank.correct {
            border-color: #4caf50;
            background-color: #e8f5e9;
            color: #2e7d32;
            font-weight: bold;
        }

        .blank.wrong {
            border-color: #f44336;
            background-color: #ffebee;
            color: #c62828;
            font-weight: bold;
        }

        .keyword {
            display: inline-block;
            background-color: #e0f7fa;
            color: #00796b;
            padding: 8px 12px;
            margin: 6px;
            border-radius: 20px;
            cursor: grab;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
            user-select: none;
        }

        .keyword:hover {
            background-color: #b2ebf2;
        }

        #keywords {
            margin-top: 30px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        button {
            padding: 10px 24px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #1976d2;
        }

        #result {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        #start-btn {
            background-color: #4caf50;
        }

        #start-btn:hover {
            background-color: #388e3c;
        }

        #gameArea {
            display: none;
        }
    </style>
</head>

<body>

    <h2>Fill in the Blanks</h2>
    <button id="start-btn" onclick="startGame()">Start Game</button>

    <div id="gameArea">
        <div id="game"></div>
        <div id="keywords"></div>
        <button onclick="checkAnswers()">Check</button>
        <div id="result"></div>
    </div>

    <script>
        //window.addEventListener("message", (event) => {
        ////    window.parentUrl = event.data;
        //});
        let sentencesWithKeywords = [];


        let allKeywords = [];
        let startTime = null;

        const gameContainer = document.getElementById('game');
        const keywordContainer = document.getElementById('keywords');
        const gameArea = document.getElementById('gameArea');
        const resultBox = document.getElementById('result');

        function startGame() {
            gameContainer.innerHTML = '';
            keywordContainer.innerHTML = '';
            resultBox.textContent = '';
            gameArea.style.display = 'block';
            allKeywords = [];
            startTime = new Date();

            renderGame();
        }

        const renderGame = async () => {

            // let parentUrl = window.parentUrl;
            // if (!parentUrl) {
            //     console.log('iframe script not loaded!')
            //     const fullUrl = window?.parent[0]?.location?.href;
            //     const urlObj = new URL(fullUrl);
            //     parentUrl = urlObj.searchParams.get("url");
            // }
            
            sentencesWithKeywords = await fetch('/api/ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    btn: 'fill_in_the_blank',
                    url: "{{ request()->url }}",
                })
            }).then(res => {
                return res.json();
            }).then(data => {
                console.log('data', (data))
                const jsonString = data.data.replace(/^```json\n|\n```$/g, '');
                console.log('jsonString', jsonString)
                return JSON.parse(jsonString);
                sentencesWithKeywords.push(JSON.parse(jsonString))
                console.log('sentencesWithKeywords', sentencesWithKeywords)

                // return response;
                // console.log('response', response)

                // // Then extract the actual JSON from the markdown code block
                // const dataString = response.data;
                // console.log('dataString', dataString)
                // const jsonString = dataString.replace(/^```json\n|\n```$/g, '');
                // console.log('jsonString', jsonString)
                // // Finally parse the inner JSON
                // const parsedData = JSON.parse(jsonString);
                // console.log('parsedData', parsedData)

                // return parsedData;
            });
            console.log('sentencesWithKeywords', sentencesWithKeywords)

            sentencesWithKeywords.forEach((item, index) => {
                const sentenceEl = document.createElement('div');
                sentenceEl.className = 'sentence';
                const parts = item.sentence.split('___');

                const blank = document.createElement('div');
                blank.className = 'blank';
                blank.setAttribute('data-index', index);
                blank.setAttribute('ondrop', 'drop(event)');
                blank.setAttribute('ondragover', 'allowDrop(event)');

                // Enable click to clear
                blank.addEventListener('click', () => {
                    if (blank.firstChild) {
                        keywordContainer.appendChild(blank.firstChild);
                        blank.textContent = '';
                        blank.classList.remove('correct', 'wrong');
                    }
                });

                sentenceEl.append(parts[0], blank, parts[1]);
                gameContainer.appendChild(sentenceEl);

                allKeywords.push(...item.keywords);
            });

            shuffle(allKeywords).forEach(word => {
                const wordEl = document.createElement('div');
                wordEl.className = 'keyword';
                wordEl.textContent = word;
                wordEl.setAttribute('draggable', true);
                wordEl.setAttribute('id', word);
                wordEl.setAttribute('ondragstart', 'drag(event)');
                keywordContainer.appendChild(wordEl);
            });
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function drop(ev) {
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text");
            const draggedEl = document.getElementById(data);
            const dropTarget = ev.target;

            if (dropTarget.classList.contains('blank')) {
                if (dropTarget.firstChild) {
                    keywordContainer.appendChild(dropTarget.firstChild);
                }
                dropTarget.textContent = '';
                dropTarget.appendChild(draggedEl);
            }
        }

        function checkAnswers() {
            let allCorrect = true;

            document.querySelectorAll('.blank').forEach(blank => {
                const index = blank.getAttribute('data-index');
                const answer = sentencesWithKeywords[index].answer;
                const userAnswer = blank.textContent.trim();

                blank.classList.remove('correct', 'wrong');
                if (userAnswer === answer) {
                    blank.classList.add('correct');
                } else {
                    blank.classList.add('wrong');
                    allCorrect = false;
                }
            });

            const endTime = new Date();
            const duration = Math.floor((endTime - startTime) / 1000);
            resultBox.textContent =
                `You completed the game in ${duration}s${allCorrect ? " — All Correct! 🎉" : " — Some mistakes ❌"}`;
        }

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }
    </script>
</body>

</html>
