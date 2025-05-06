const items = [
    { name: "Savings Account", category: "Account" },
    { name: "Current Account", category: "Account" },
    { name: "Home Loan", category: "Loan" },
    { name: "Personal Loan", category: "Loan" },
    { name: "Platinum Credit Card", category: "CreditCard" },
    { name: "Student Credit Card", category: "CreditCard" },
    { name: "Online Banking", category: "Service" },
    { name: "Mobile Banking App", category: "Service" }
  ];
  
  // Render data in the table
  const tableBody = document.getElementById("dataTable");
  
  items.forEach(item => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${item.name}</td>
      <td>${item.category}</td>
    `;
    tableBody.appendChild(row);
  });
  
  // Export data as CSV
  function exportToCSV() {
    const csvHeader = "Name,Category\n";
    const csvRows = items.map(item => 
      `"${item.name}","${item.category}"`
    );
    const csvContent = csvHeader + csvRows.join("\n");
  
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "banking_data.csv";
    link.click();
  }
  