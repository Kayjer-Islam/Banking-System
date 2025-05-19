<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Management</title>
    <style>
        /* Reset some basics */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            max-width: 480px;
            width: 100%;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h1 {
            font-weight: 700;
            font-size: 1.8rem;
            color: #0052cc;
            margin-bottom: 25px;
            border-bottom: 3px solid #0052cc;
            padding-bottom: 8px;
        }
        .card {
            background: linear-gradient(135deg, #0052cc 0%, #2680eb 100%);
            color: white;
            font-weight: 600;
            font-size: 1.3rem;
            padding: 25px 30px;
            border-radius: 15px;
            text-align: center;
            letter-spacing: 2px;
            box-shadow: 0 6px 15px rgba(38, 128, 235, 0.5);
            user-select: none;
        }
        .controls {
            margin-top: 35px;
        }
        .control-item {
            display: flex;
            align-items: center;
            margin: 20px 0;
            font-size: 1rem;
            color: #333;
        }
        .switch {
            position: relative;
            width: 52px;
            height: 28px;
            background: #ccc;
            border-radius: 14px;
            cursor: pointer;
            transition: background 0.4s ease;
            margin-right: 14px;
            flex-shrink: 0;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.15);
        }
        .switch::after {
            content: "";
            position: absolute;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.12);
            transition: left 0.4s ease;
        }
        .switch.active {
            background: #28a745;
            box-shadow: inset 0 2px 8px rgba(40,167,69,0.7);
        }
        .switch.active::after {
            left: 26px;
        }
        button, .atm-link {
            background: #0052cc;
            color: white;
            border: none;
            padding: 14px 22px;
            margin: 15px 10px 0 0;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(38, 128, 235, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
            user-select: none;
        }
        button:hover, .atm-link:hover {
            background-color: #003d99;
            box-shadow: 0 6px 20px rgba(0,61,153,0.6);
        }
        .message {
            margin-top: 18px;
            padding: 12px 15px;
            background-color: #d4edda;
            border-left: 6px solid #28a745;
            color: #155724;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: none;
            user-select: none;
        }
        .pin-input {
            margin-top: 20px;
            display: none;
        }
        input[type="text"] {
            padding: 12px 14px;
            width: 100%;
            max-width: 180px;
            font-size: 1.1rem;
            font-weight: 600;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease;
            user-select: text;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #0052cc;
            box-shadow: 0 0 8px rgba(0,82,204,0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Card Management</h1>

        <div class="card">
            VISA ****1234
        </div>

        <div class="controls">
            <div class="control-item">
                <span class="switch" id="lockSwitch" onclick="toggleSwitch('lockSwitch', 'Lock Card')"></span>
                <span>Lock Card</span>
            </div>

            <div class="control-item">
                <span class="switch" id="intlSwitch" onclick="toggleSwitch('intlSwitch', 'International Use')"></span>
                <span>International Use</span>
            </div>

            <button onclick="reportLost()">Report Lost/Stolen</button>
            <button onclick="togglePinInput()">Change PIN</button>
            <a href="Atm loc.html" target="_blank" class="atm-link">ATMs Near Me</a>

            <div class="pin-input" id="pinInput">
                <input type="text" id="pinField" maxlength="4" placeholder="Enter 4-digit PIN" oninput="validatePIN(this)">
            </div>

            <div class="message" id="messageBox"></div>
        </div>
    </div>

    <script>
        function showMessage(text) {
            const msgBox = document.getElementById('messageBox');
            msgBox.textContent = text;
            msgBox.style.display = 'block';
            setTimeout(() => {
                msgBox.style.display = 'none';
            }, 3000);
        }

        function reportLost() {
            showMessage('Report submitted');
        }

        function togglePinInput() {
            const inputBox = document.getElementById('pinInput');
            inputBox.style.display = inputBox.style.display === 'block' ? 'none' : 'block';
        }

        function validatePIN(input) {
            input.value = input.value.replace(/\D/g, '').slice(0, 4);
        }

        function toggleSwitch(id, label) {
            const element = document.getElementById(id);
            element.classList.toggle('active');
            const state = element.classList.contains('active') ? 'enabled' : 'disabled';
            showMessage(`${label} ${state}`);
        }
    </script>
</body>
</html>
