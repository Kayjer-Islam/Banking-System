
const fraudAlerts = [
    "Unrecognized device login attempt - Dhaka, Bangladesh",
    "Multiple failed login attempts - IP: 192.168.1.9",
    "Suspicious transaction detected - ৳50,000 withdrawal"
  ];
  
  const loginHistory = [
    { time: '2025-05-06 10:45', ip: '192.168.0.1', status: 'Success' },
    { time: '2025-05-06 09:30', ip: '192.168.0.2', status: 'Failed' },
    { time: '2025-05-05 21:12', ip: '203.0.113.55', status: 'Failed' }
  ];
  
  
  const fraudList = document.getElementById('fraudNotifications');
  fraudAlerts.forEach(alert => {
    const li = document.createElement('li');
    li.textContent = alert;
    fraudList.appendChild(li);
  });
  
 
  const loginTable = document.getElementById('loginAttempts');
  loginHistory.forEach(entry => {
    const row = `<tr>
      <td>${entry.time}</td>
      <td>${entry.ip}</td>
      <td>${entry.status}</td>
    </tr>`;
    loginTable.innerHTML += row;
  });
  

  function submitReport() {
    const report = document.getElementById('reportText').value;
    const status = document.getElementById('reportStatus');
  
    if (!report.trim()) {
      status.textContent = "Please describe the activity before submitting.";
      status.style.color = "red";
    } else {
      status.textContent = "Report submitted successfully. Our team will review it shortly.";
      status.style.color = "green";
      document.getElementById('reportText').value = "";
    }
  }
  