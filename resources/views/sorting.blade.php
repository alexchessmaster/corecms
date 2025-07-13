<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sorting Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        button {
            padding: 10px 20px;
            font-size: 1rem;
            margin-bottom: 2rem;
            border: none;
            background-color: #4f46e5;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        #game {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .category {
            border: 2px solid #ccc;
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
            width: 200px;
            min-height: 200px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .category h3 {
            margin-top: 0;
            text-align: center;
        }

        .term {
            background: #dbeafe;
            padding: 0.5rem;
            border-radius: 8px;
            margin: 0.5rem 0;
            cursor: grab;
            text-align: center;
        }

        .dragging {
            opacity: 0.5;
        }

        #result {
            font-size: 1.1rem;
            color: #333;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <button onclick="startGame()">Start Game</button>
    <div id="game"></div>
    <div id="result"></div>

    <script>
        const messagePromise = new Promise((resolve) => {
            //window.addEventListener("message", (event) => {
            //    console.log('Message received:', event.data);
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
                    btn: 'sorting',
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

            //   {
            //     categories: ["Causes", "Effects"],
            //     terms: [
            //       { text: "Pollution", category: "Causes" },
            //       { text: "Climate Change", category: "Effects" },
            //       { text: "Deforestation", category: "Causes" },
            //       { text: "Global Warming", category: "Effects" }
            //     ]
            //   };

            const gameContainer = document.getElementById('game');
            const resultBox = document.getElementById('result');
            gameContainer.innerHTML = '';
            resultBox.textContent = '';

            let startTime = Date.now();
            const totalTerms = data.terms.length;
            let placedCount = 0;
            let correctCount = 0;

            const shuffled = data.terms
                .map(item => ({
                    ...item,
                    id: crypto.randomUUID()
                }))
                .sort(() => 0.5 - Math.random());

            const termBox = document.createElement('div');
            termBox.classList.add('category');
            termBox.innerHTML = '<h3>Terms</h3>';
            shuffled.forEach(item => {
                const div = document.createElement('div');
                div.className = 'term';
                div.textContent = item.text;
                div.draggable = true;
                div.dataset.category = item.category;
                div.dataset.id = item.id;

                div.addEventListener('dragstart', e => {
                    e.dataTransfer.setData('text/plain', JSON.stringify(item));
                    div.classList.add('dragging');
                });

                div.addEventListener('dragend', () => {
                    div.classList.remove('dragging');
                });

                termBox.appendChild(div);
            });
            gameContainer.appendChild(termBox);

            data.categories.forEach(cat => {
                const catBox = document.createElement('div');
                catBox.className = 'category';
                catBox.innerHTML = `<h3>${cat}</h3>`;
                catBox.addEventListener('dragover', e => e.preventDefault());
                catBox.addEventListener('drop', e => {
                    e.preventDefault();
                    const item = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const original = document.querySelector(`.term[data-id='${item.id}']`);
                    if (original) original.remove();

                    const dropped = document.createElement('div');
                    dropped.className = 'term';
                    dropped.textContent = item.text;

                    if (item.category === cat) {
                        dropped.style.backgroundColor = '#bbf7d0'; // green
                        correctCount++;
                    } else {
                        dropped.style.backgroundColor = '#fecaca'; // red
                    }

                    catBox.appendChild(dropped);
                    placedCount++;

                    if (placedCount === totalTerms) {
                        const timeTaken = ((Date.now() - startTime) / 1000).toFixed(1);
                        const percentage = ((correctCount / totalTerms) * 100).toFixed(0);
                        resultBox.textContent =
                            `✅ You got ${percentage}% correct in ⏱ ${timeTaken} seconds!`;
                    }
                });
                gameContainer.appendChild(catBox);
            });
        }
    </script>
</body>

</html>
