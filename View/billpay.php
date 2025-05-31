<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bill Pay</title>
  <link rel="stylesheet" href="../Asset/billpay.css">

</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($email) ?>!</h1>

 
  <section>
    <h2>Biller Directory</h2>
    <ul id="billerList"></ul>
  </section>


  <section>
    <h2>Schedule a New Bill</h2>
    <form id="billForm">
      <label>Biller:
        <select name="biller_id" id="billerSelect" required></select>
      </label><br><br>
      <label>Amount: <input type="number" name="amount" step="0.01" required></label><br><br>
      <label>Due Date: <input type="date" name="due_date" required></label><br><br>
      <label>Recurring:
        <select name="recurring">
          <option value="none">None</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="yearly">Yearly</option>
        </select>
      </label><br><br>
      <label>e-Bill Message (optional):<br>
        <textarea name="e_bill" rows="3" cols="40"></textarea>
      </label><br><br>
      <button type="submit">Add Bill</button>
    </form>
  </section>

  
  <section>
    <h2>Upcoming Bills</h2>
    <table id="billTable">
      <thead>
        <tr><th>Biller</th><th>Amount</th><th>Due Date</th><th>Recurring</th><th>e-Bill</th><th>Action</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  
  <section>
    <h2>Payment Calendar</h2>
    <ul id="calendarList"></ul>
  </section>

  
  <section>
    <h2>e-Bill Inbox</h2>
    <ul id="inboxList"></ul>
  </section>


<script>
const controller = '../Controller/BillController.php';

function fetchBillers() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `${controller}?action=billers`, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      const billerList = document.getElementById('billerList');
      const billerSelect = document.getElementById('billerSelect');
      billerList.innerHTML = '';
      billerSelect.innerHTML = '';
      data.forEach(biller => {
        billerList.innerHTML += `<li>${biller.name} (${biller.category})</li>`;
        billerSelect.innerHTML += `<option value="${biller.id}">${biller.name}</option>`;
      });
    }
  };
  xhr.send();
}

function fetchBills() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', controller, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      const tbody = document.querySelector('#billTable tbody');
      tbody.innerHTML = '';
      data.forEach(bill => {
        tbody.innerHTML += `
          <tr>
            <td>${bill.biller_name}</td>
            <td>${bill.amount}</td>
            <td>${bill.due_date}</td>
            <td>${bill.recurring}</td>
            <td>${bill.e_bill || '-'}</td>
            <td><button onclick="deleteBill(${bill.id})">❌</button></td>
          </tr>`;
      });
    }
  };
  xhr.send();
}

function fetchCalendar() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `${controller}?action=calendar`, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      const list = document.getElementById('calendarList');
      list.innerHTML = '';
      data.forEach(item => {
        list.innerHTML += `<li>${item.due_date}: ${item.count} bill(s)</li>`;
      });
    }
  };
  xhr.send();
}

function fetchInbox() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `${controller}?action=inbox`, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      const list = document.getElementById('inboxList');
      list.innerHTML = '';
      data.forEach(item => {
        list.innerHTML += `<li><strong>Due:</strong> ${item.due_date} <br> <strong>e-Bill:</strong> ${item.e_bill}</li>`;
      });
    }
  };
  xhr.send();
}

function deleteBill(id) {
  const xhr = new XMLHttpRequest();
  xhr.open('DELETE', controller, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      fetchBills();
      fetchCalendar();
      fetchInbox();
    }
  };
  xhr.send(`id=${encodeURIComponent(id)}`);
}

document.getElementById('billForm').addEventListener('submit', function (e) {
  e.preventDefault();

  
  const biller_id = document.getElementById('billerSelect').value;
  const amount = document.getElementById('amount').value.trim();
  const due_date = document.getElementById('due_date').value.trim();
  const recurring = document.getElementById('recurring').value.trim();
  const e_bill = document.getElementById('e_bill').value.trim();

  if (!biller_id || isNaN(parseInt(biller_id))) {
    alert("Please select a valid biller.");
    return;
  }

  if (!amount || isNaN(parseFloat(amount)) || parseFloat(amount) <= 0) {
    alert("Please enter a valid positive amount.");
    return;
  }

  if (!due_date) {
    alert("Please select a due date.");
    return;
  }

  if (!recurring) {
    alert("Please select recurring option.");
    return;
  }

  
  const form = new FormData(this);
  const xhr = new XMLHttpRequest();
  xhr.open('POST', controller, true);
  xhr.onload = () => {
    if (xhr.status === 200) {
      this.reset();
      fetchBills();
      fetchCalendar();
      fetchInbox();
    } else {
      alert("Submission failed. Please try again.");
    }
  };
  xhr.send(form);
});

fetchBillers();
fetchBills();
fetchCalendar();
fetchInbox();
</script>

</body>
</html>
