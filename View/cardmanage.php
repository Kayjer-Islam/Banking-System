<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "webtech section a");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("SELECT account_number FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($account_number);
$stmt->fetch();
$stmt->close();

$maskedCard = "****" . substr($account_number, -4);  

$check = $conn->prepare("SELECT id FROM card_management WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $hashed_pin = password_hash(rand(1000, 9999), PASSWORD_DEFAULT);
    $insert = $conn->prepare("INSERT INTO card_management (email, card_number, pin) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $email, $account_number, $hashed_pin);
    $insert->execute();
    $insert->close();
}
$check->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        header("Content-Type: application/json");
        $response = ["success" => false];
        if ($_POST['action'] == 'toggle_lock') {
            $lock = $_POST['state'] === 'true' ? 1 : 0;
            $stmt = $conn->prepare("UPDATE card_management SET lock_card = ? WHERE email = ?");
            $stmt->bind_param("is", $lock, $email);
            $stmt->execute();
            $response["success"] = true;
            $response["message"] = "Lock Card updated.";
        } elseif ($_POST['action'] == 'toggle_intl') {
            $intl = $_POST['state'] === 'true' ? 1 : 0;
            $stmt = $conn->prepare("UPDATE card_management SET international_use = ? WHERE email = ?");
            $stmt->bind_param("is", $intl, $email);
            $stmt->execute();
            $response["success"] = true;
            $response["message"] = "International Use updated.";
        } elseif ($_POST['action'] == 'report') {
            $stmt = $conn->prepare("UPDATE card_management SET report_status = 'Reported stolen' WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $response["success"] = true;
            $response["message"] = "Card reported stolen.";
        } elseif ($_POST['action'] == 'change_pin') {
            $newPin = $_POST['pin'];
           if (ctype_digit($newPin) && strlen($newPin) === 4)
 {
                $hashedPin = password_hash($newPin, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE card_management SET pin = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashedPin, $email);
                $stmt->execute();
                $response["success"] = true;
                $response["message"] = "PIN updated.";
            } else {
                $response["message"] = "Invalid PIN format.";
            }
        }
        echo json_encode($response);
        exit();
    } elseif (isset($_POST['go_to_atm'])) {
        header("Location: atmloc.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Management</title>
    <style>
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

        <div class="card">VISA <?php echo htmlspecialchars($maskedCard); ?></div>

        <div class="controls">
            <div class="control-item">
                <span class="switch" id="lockSwitch" onclick="toggleSwitch('lockSwitch', 'Lock Card', 'toggle_lock')"></span>
                <span>Lock Card</span>
            </div>

            <div class="control-item">
                <span class="switch" id="intlSwitch" onclick="toggleSwitch('intlSwitch', 'International Use', 'toggle_intl')"></span>
                <span>International Use</span>
            </div>

            <button onclick="reportLost()">Report Lost/Stolen</button>
            <button onclick="togglePinInput()">Change PIN</button>
            <button onclick="goToATM()">ATMs Near Me</button>

            <div class="pin-input" id="pinInput">
                <input type="text" id="pinField" maxlength="4" placeholder="Enter 4-digit PIN" oninput="validatePIN(this)">
                <button onclick="submitPIN()">Submit PIN</button>
            </div>

            <div class="message" id="messageBox"></div>
        </div>

        <form id="atmForm" method="POST" style="display: none;">
            <input type="hidden" name="go_to_atm" value="1">
        </form>
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

        function toggleSwitch(id, label, action) {
            const element = document.getElementById(id);
            const newState = !element.classList.contains('active');
            element.classList.toggle('active');
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=${action}&state=${newState}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(`${label} ${newState ? "enabled" : "disabled"}`);
                } else {
                    showMessage(data.message || "Error updating setting");
                }
            });
        }

        function reportLost() {
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=report`
            })
            .then(response => response.json())
            .then(data => showMessage(data.message || "Error reporting card"));
        }

        function togglePinInput() {
            const inputBox = document.getElementById('pinInput');
            inputBox.style.display = inputBox.style.display === 'block' ? 'none' : 'block';
        }

        function validatePIN(input) {
            input.value = input.value.replace(/\D/g, '').slice(0, 4);
        }

        function submitPIN() {
            const pin = document.getElementById("pinField").value;
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=change_pin&pin=${pin}`
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message);
                if (data.success) document.getElementById("pinField").value = "";
            });
        }

        function goToATM() {
            document.getElementById('atmForm').submit();
        }
    </script>
</body>
</html>
