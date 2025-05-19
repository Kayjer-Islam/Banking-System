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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - <?php echo $_SESSION['email']; ?></title>
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2c3e50;
      --danger-color: #e74c3c;
      --success-color: #2ecc71;
      --light-color: #ecf0f1;
      --dark-color: #34495e;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      color: #333;
      min-height: 100vh;
      padding: 20px;
    }
    
    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    
    .logout-btn {
      background-color: var(--danger-color);
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin-left: 15px;
    }
    
    .logout-btn:hover {
      background-color: #c0392b;
    }
    
    .quick-nav {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .nav-btn {
      padding: 10px 15px;
      background-color: white;
      border-radius: 4px;
      text-decoration: none;
      color: var(--secondary-color);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: all 0.3s;
    }
    
    .nav-btn:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }
    
    .widget {
      background-color: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .widget:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .widget-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }
    
    .widget-title {
      font-size: 1.2rem;
      color: var(--secondary-color);
    }
    
    .widget-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--light-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-color);
      font-weight: bold;
    }
    
    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: var(--secondary-color);
      margin: 10px 0;
    }
    
    .stat-label {
      color: #7f8c8d;
      font-size: 0.9rem;
    }
    
    .action-buttons {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-top: 20px;
    }
    
    .action-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 15px;
      background-color: var(--light-color);
      border-radius: 8px;
      text-decoration: none;
      color: var(--secondary-color);
      transition: background-color 0.3s;
      text-align: center;
    }
    
    .action-btn:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    .action-btn span {
      font-size: 0.9rem;
      margin-top: 8px;
    }
    
    .transaction {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }
    
    .transaction:last-child {
      border-bottom: none;
    }
    
    .debit {
      color: var(--danger-color);
    }
    
    .credit {
      color: var(--success-color);
    }
    
    @media (max-width: 768px) {
      .action-buttons {
        grid-template-columns: 1fr;
      }
      
      .quick-nav {
        flex-wrap: wrap;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <header class="header">
      <h2>Dashboard</h2>
      <div class="user-profile">
        <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?></div>
        <span><?php echo $_SESSION['email']; ?></span>
        <button class="logout-btn" onclick="window.location.href='../Controller/logout.php'">Logout</button>
      </div>
    </header>
    
    <div class="quick-nav">
      <a href="profile.php" class="nav-btn">My Profile</a>
      <a href="transactionHistory.php" class="nav-btn">Transactions</a>
      <a href="billpay.php" class="nav-btn">Bill Pay</a>
      <a href="contact.php" class="nav-btn">Contact Us</a>
      <a href="search.php" class="nav-btn">Search & Filter</a>
      <a href="security-alerts.php" class="nav-btn">Security Alerts</a>
      <a href="contact.php" class="nav-btn">Contact Us</a>

      <a href="../View/cardmanage.php" class="nav-btn">Card</a>
    </div>
    
    <div class="dashboard-grid">
      <div class="widget">
        <div class="widget-header">
          <h3 class="widget-title">Account Summary</h3>
          <div class="widget-icon">$</div>
        </div>
        <div class="stat-value">$12,458.50</div>
        <p class="stat-label">Available Balance</p>
        <div class="action-buttons">
          <a href="../Controller/fundtransfer.php" class="action-btn">
            <span>Transfer</span>
          </a>
          <a href="../Controller/checkdepo.php" class="action-btn">
            <span>Deposit</span>
          </a>
        </div>
      </div>
      
      <div class="widget">
        <div class="widget-header">
          <h3 class="widget-title">Quick Actions</h3>
          <div class="widget-icon">⚡</div>
        </div>
        <div class="action-buttons">
          <a href="billpay.php" class="action-btn">
            <span>Pay Bills</span>
          </a>
          <a href="interest.php" class="action-btn">
            <span>Interest Calculator</span>
          </a>
          <a href="transactionHistory.php" class="action-btn">
            <span>Transaction History</span>
          </a>
          <a href="loan.php" class="action-btn">
            <span>Loan Application</span>
          </a>
        </div>
      </div>
      
      <div class="widget">
        <div class="widget-header">
          <h3 class="widget-title">Recent Transactions</h3>
          <div class="widget-icon">↔</div>
        </div>
        <div style="margin-top: 15px;">
          <div class="transaction">
            <span>Amazon Purchase</span>
            <span class="debit">-$45.99</span>
          </div>
          <div class="transaction">
            <span>Salary Deposit</span>
            <span class="credit">+$3,500.00</span>
          </div>
          <div class="transaction">
            <span>Utility Bill</span>
            <span class="debit">-$120.75</span>
          </div>
          <div class="transaction">
            <span>Transfer to Savings</span>
            <span class="debit">-$500.00</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
