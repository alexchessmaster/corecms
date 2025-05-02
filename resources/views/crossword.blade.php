<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crossword Puzzle Game</title>
</head>

<body>
    <script>
        async function startCrosswordGame() {
            // Define crossword structure
            const crosswordData = {
                gridSize: 5,
                grid: [
                    ['C', 'A', 'T', '', ''],
                    ['', '', 'R', '', ''],
                    ['', '', 'O', '', ''],
                    ['', '', 'S', '', ''],
                    ['', '', 'S', '', ''],
                ],
                clues: [{
                        clue: '1 Across: A small domesticated feline',
                        row: 0,
                        col: 0,
                        direction: 'across'
                    },
                    {
                        clue: '3 Down: Opposite of win',
                        row: 1,
                        col: 2,
                        direction: 'down'
                    },
                ]
            };

            // Create game elements
            const body = document.querySelector('body');
            body.innerHTML = ''; // Clear any existing content

            // Create a container for the crossword game
            const crosswordContainer = document.createElement('div');
            crosswordContainer.style.display = 'grid';
            crosswordContainer.style.gridTemplateColumns = `repeat(${crosswordData.gridSize}, 50px)`;
            crosswordContainer.style.gap = '5px';
            crosswordContainer.style.marginTop = '20px';
            crosswordContainer.style.justifyItems = 'center';

            body.appendChild(crosswordContainer);

            // Create and show start button
            const startBtn = document.createElement('button');
            startBtn.innerHTML = 'Start Game';
            startBtn.style.padding = '15px 30px';
            startBtn.style.fontSize = '1.5em';
            startBtn.style.marginBottom = '20px';
            startBtn.style.backgroundColor = '#4CAF50';
            startBtn.style.color = 'white';
            startBtn.style.border = 'none';
            startBtn.style.borderRadius = '10px';
            startBtn.style.cursor = 'pointer';
            startBtn.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.2)';
            body.appendChild(startBtn);

            // Function to generate the grid with numbered clues
            function generateGrid() {
                crosswordContainer.innerHTML = ''; // Reset the grid
                let inputCells = [];

                for (let row = 0; row < crosswordData.grid.length; row++) {
                    for (let col = 0; col < crosswordData.grid[row].length; col++) {
                        const input = document.createElement('input');
                        input.setAttribute('maxlength', '1');
                        input.style.width = '50px';
                        input.style.height = '50px';
                        input.style.textAlign = 'center';
                        input.style.fontWeight = 'bold';
                        input.style.fontSize = '1.5em';
                        input.style.borderRadius = '5px';
                        input.classList.add('cell');

                        // Check if the cell should have a letter or be empty
                        if (crosswordData.grid[row][col] === '') {
                            input.style.backgroundColor = 'transparent';
                            input.disabled = false;
                        } else {
                            input.value = crosswordData.grid[row][col];
                            input.style.backgroundColor = '#f0f0f0';
                            input.disabled = true; // Numbered cells are disabled
                        }

                        // Add clue numbers to the grid
                        crosswordData.clues.forEach((clue, index) => {
                            if (clue.row === row && clue.col === col) {
                                input.value = index + 1; // Add the clue number
                                input.style.backgroundColor = '#f0f0f0';
                                input.disabled = true; // Disable numbered cells so they can't be edited
                            }
                        });

                        crosswordContainer.appendChild(input);
                        inputCells.push({
                            input,
                            row,
                            col
                        });
                    }
                }

                return inputCells;
            }

            // Create clues box
            const cluesBox = document.createElement('div');
            cluesBox.style.marginTop = '30px';
            cluesBox.style.padding = '20px';
            cluesBox.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
            cluesBox.style.borderRadius = '10px';
            cluesBox.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
            cluesBox.style.maxWidth = '400px';
            cluesBox.style.textAlign = 'left';
            body.appendChild(cluesBox);

            const cluesHeader = document.createElement('h3');
            cluesHeader.innerHTML = 'Clues:';
            cluesBox.appendChild(cluesHeader);

            const cluesList = document.createElement('ul');
            crosswordData.clues.forEach(clue => {
                const li = document.createElement('li');
                li.innerHTML = clue.clue;
                cluesList.appendChild(li);
            });
            cluesBox.appendChild(cluesList);

            // Create check button
            const checkBtn = document.createElement('button');
            checkBtn.innerHTML = 'Check Answers';
            checkBtn.style.marginTop = '15px';
            checkBtn.style.padding = '12px 24px';
            checkBtn.style.fontSize = '1.2em';
            checkBtn.style.backgroundColor = '#0077ff';
            checkBtn.style.color = 'white';
            checkBtn.style.borderRadius = '8px';
            checkBtn.style.border = 'none';
            checkBtn.style.cursor = 'pointer';
            checkBtn.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.2)';
            cluesBox.appendChild(checkBtn);

            // Start button functionality
            startBtn.addEventListener('click', () => {
                crosswordContainer.style.display = 'grid';
                startBtn.style.display = 'none';
                generateGrid();
                cluesBox.style.display = 'block';
            });

            // Check answers functionality
            checkBtn.addEventListener('click', () => {
                const inputCells = generateGrid();
                inputCells.forEach(({
                    input,
                    row,
                    col
                }) => {
                    const correctLetter = crosswordData.grid[row][col];
                    if (correctLetter && input.value.toUpperCase() === correctLetter) {
                        input.style.backgroundColor = '#8ff7a3'; // Correct answer: green
                    } else if (correctLetter) {
                        input.style.backgroundColor = '#f77a7a'; // Incorrect answer: red
                    }
                });
            });
        }

        // Initialize the game when the page is ready
        window.onload = async function() {
            await startCrosswordGame();
        };
    </script>
</body>

</html>
