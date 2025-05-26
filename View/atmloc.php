<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ATM Locator</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        .atm {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .atm h3 {
            margin: 0 0 5px;
            color: #0066cc;
        }

        .atm p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }

        .atm button {
            margin-top: 10px;
            padding: 8px 14px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .atm button:hover {
            background-color: #004999;
        }
    </style>
</head>
<body>

    <h1>ATM Locator</h1>

    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14603.203371116706!2d90.4264!3d23.8213!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7a85011f6fb%3A0x92b989a885509730!2sKuril%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1683367691220!5m2!1sen!2sbd"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <div class="atm">
        <h3>BRAC Bank ATM</h3>
        <p>300 meters away</p>
        <p>Kuril Flyover, Dhaka 1229</p>
        <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=BRAC+Bank+ATM+Kuril+Dhaka')">Directions</button>
    </div>

    <div class="atm">
        <h3>Dutch-Bangla Bank ATM</h3>
        <p>500 meters away</p>
        <p>Kuril Bishwa Road, Dhaka 1229</p>
        <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=Dutch+Bangla+Bank+ATM+Kuril+Dhaka')">Directions</button>
    </div>

</body>
</html>
