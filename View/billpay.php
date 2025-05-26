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

  <!-- 1. Biller Directory -->
  <section>
    <h2>Biller Directory</h2>
    <ul id="billerList"></ul>
  </section>

  <!-- 2. Add a New Bill -->
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

  <!-- 3. Upcoming Bills -->
  <section>
    <h2>Upcoming Bills</h2>
    <table id="billTable">
      <thead>
        <tr><th>Biller</th><th>Amount</th><th>Due Date</th><th>Recurring</th><th>e-Bill</th><th>Action</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  <!-- 4. Payment Calendar -->
  <section>
    <h2>Payment Calendar</h2>
    <ul id="calendarList"></ul>
  </section>

  <!-- 5. e-Bill Inbox -->
  <section>
    <h2>e-Bill Inbox</h2>
    <ul id="inboxList"></ul>
  </section>

<script>
const controller = '../Controller/BillController.php';

function fetchBillers() {
  fetch(`${controller}?action=billers`)
    .then(res => res.json())
    .then(data => {
      const billerList = document.getElementById('billerList');
      const billerSelect = document.getElementById('billerSelect');
      billerList.innerHTML = '';
      billerSelect.innerHTML = '';
      data.forEach(biller => {
        billerList.innerHTML += `<li>${biller.name} (${biller.category})</li>`;
        billerSelect.innerHTML += `<option value="${biller.id}">${biller.name}</option>`;
      });
    });
}

function fetchBills() {
  fetch(controller)
    .then(res => res.json())
    .then(data => {
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
    });
}

function fetchCalendar() {
  fetch(`${controller}?action=calendar`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('calendarList');
      list.innerHTML = '';
      data.forEach(item => {
        list.innerHTML += `<li>${item.due_date}: ${item.count} bill(s)</li>`;
      });
    });
}

function fetchInbox() {
  fetch(`${controller}?action=inbox`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('inboxList');
      list.innerHTML = '';
      data.forEach(item => {
        list.innerHTML += `<li><strong>Due:</strong> ${item.due_date} <br> <strong>e-Bill:</strong> ${item.e_bill}</li>`;
      });
    });
}

function deleteBill(id) {
  fetch(controller, {
    method: 'DELETE',
    body: new URLSearchParams({ id })
  }).then(() => {
    fetchBills();
    fetchCalendar();
    fetchInbox();
  });
}

document.getElementById('billForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const form = new FormData(this);
  fetch(controller, {
    method: 'POST',
    body: form
  }).then(res => res.json())
    .then(() => {
      this.reset();
      fetchBills();
      fetchCalendar();
      fetchInbox();
    });
});

// Initial load
fetchBillers();
fetchBills();
fetchCalendar();
fetchInbox();
</script>

</body>
</html>
