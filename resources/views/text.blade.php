<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Angel & Demon Widget</title>
    <style>
        button {
            font-size: 1rem;
            padding: 5px 10px;
            margin: 5px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
        }

        .angel {
            background-color: #d4f1f9;
            color: #0077cc;
        }

        .demon {
            background-color: #fbdada;
            color: #cc0000;
        }

        .fun {
            background-color: #acd7ff;
            color: #264e7a;
        }

        .summary {
            background-color: #d1ffac;
            color: #377a26;
        }

        .list {
            background-color: #fffbc4;
            color: #65670b;
        }
    </style>




<style>
/* body {
  background: radial-gradient(ellipse at center, #0f0f1b 0%, #060612 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
} */

.ai-placeholder {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(16px);
  border-radius: 16px;
  padding: 30px 40px;
  box-shadow: 0 4px 60px rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.08);
  width: 360px;
}

.pulse-line {
  height: 14px;
  background: linear-gradient(90deg, #99f, #aef, #99f);
  border-radius: 8px;
  margin: 12px 0;
  animation: pulse 1.6s ease-in-out infinite;
  opacity: 0.3;
  filter: blur(1.5px);
  background-size: 200% 100%;
}

.delay-0 { animation-delay: 0s; }
.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }

@keyframes pulse {
  0% { background-position: 200% 0; opacity: 0.2; }
  50% { background-position: 100% 0; opacity: 0.6; }
  100% { background-position: 0% 0; opacity: 0.2; }
}


</style>

</head>

<body>
    
    


        
    <button id="angel" class="angel">🧐 Scientist</button>
    <button id="demon" class="demon">😈 Devil advocate</button>
    <button id="fun" class="fun">🤯 Fun fact</button>
    <button id="list" class="list">📋 List</button>
    <button id="summary" class="summary">📝 Summary</button>
    <button id="children" class="angel">👶 Children</button>
    <div id="main-content"></div>
    <script>
        window.addEventListener("message", (event) => {
            window.parentUrl = event.data;
        });

        const handleClick = (btn) => {
            // console.log('btn', btn)
            // console.log("Received parent URL:", window.parentUrl);

            document.getElementById('main-content').innerHTML = `
<div class="ai-placeholder">
  <div class="pulse-line delay-0"></div>
  <div class="pulse-line delay-1"></div>
  <div class="pulse-line delay-2"></div>
</div>
`;

            let parentUrl = window.parentUrl;
            if(!parentUrl) {
                console.log('iframe script not loaded!')
                const fullUrl = window?.parent[0]?.location?.href;
                const urlObj = new URL(fullUrl);
                parentUrl = urlObj.searchParams.get("url");
            }

            // console.log('parentUrl', parentUrl)
            // console.log('windowwww', window)

            // send btn and parentUrl to poolai and get the response
            fetch('/api/ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    btn: btn,
                    url: parentUrl,
                })
            }).then(res => {
                console.log('res 1', res);

                return res.json();
            }).then(data => {

                console.log('res 2', data);
                document.getElementById('main-content').innerHTML = data.data
            })

        }

        document.getElementById('angel').addEventListener('click', () => handleClick('scientist'))
        document.getElementById('demon').addEventListener('click', () => handleClick('demon'))
        document.getElementById('fun').addEventListener('click', () => handleClick('fun_fact'))
        document.getElementById('list').addEventListener('click', () => handleClick('list'))
        document.getElementById('summary').addEventListener('click', () => handleClick('summary'))
        document.getElementById('children').addEventListener('click', () => handleClick('children'))
    </script>
</body>

</html>
