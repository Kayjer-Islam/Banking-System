<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Application Portal</title>
    <style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        margin: 2rem;
        background: #f4f8fd;
        color: #222f4f;
    }
    h1, h2 {
        color: #1657b7;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        background: #fff;
        box-shadow: 0 2px 10px #d4e3fc80;
    }
    th, td {
        border: 1px solid #b3cef6;
        padding: 10px;
        text-align: left;
    }
    th {
        background: #e8f0fb;
        color: #1657b7;
    }
    tr:nth-child(even) { background: #f7fbfe; }
    .box {
        background: #fff;
        border: 1px solid #b3cef6;
        box-shadow: 0 2px 8px #d4e3fc50;
        padding: 18px;
        margin-bottom: 28px;
        border-radius: 12px;
    }
    select, input[type="file"], button {
        font-size: 1rem;
        padding: 6px 12px;
        margin-top: 4px;
        border: 1px solid #acd0fa;
        border-radius: 6px;
        background: #f2f8ff;
    }
    button {
        background: linear-gradient(90deg, #409efb 0%, #2366d1 100%);
        color: #fff;
        border: none;
        box-shadow: 0 2px 6px #acd0fa30;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    button:hover {
        background: linear-gradient(90deg, #2366d1 0%, #409efb 100%);
    }
    a {
        color: #2366d1;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    #application-result {
        margin-top: 12px;
        font-weight: bold;
        color: #2366d1;
    }
</style>
</head>
<body>
    <h1>Loan Application Portal</h1>
    <div class="box">
        <h2>Compare Loan Products</h2>
        <div id="product-list"><i>Loading...</i></div>
    </div>
    <div class="box">
        <h2>Apply & Submit Income Proof</h2>
        <form id="loan-application-form" enctype="multipart/form-data">
            <label>Loan Product:
                <select name="loan_product_id" id="loan-product-select" required></select>
            </label>
            <br><br>
            <label>Income Proof:
                <input type="file" name="income_proof" id="income-proof" required>
            </label>
            <br><br>
            <button type="submit">Submit Application</button>
        </form>
        <div id="application-result"></div>
    </div>
    <div class="box">
        <h2>Your Applications & E-sign</h2>
        <div id="application-status-list"><i>Loading...</i></div>
    </div>
    <script src="../controller/loanApp.js"></script>
</body>
</html>