const transactions = [
    { date: '2025-05-01 ', amount: 1500, type: 'credit', ref: ' Salary' },
    { date: '2025-05-03 ', amount: 300, type: 'debit', ref: ' Groceries' },
    { date: '2025-05-04 ', amount: 1000, type: 'credit', ref: ' Freelance' },
    { date: '2025-05-05 ', amount: 200, type: 'debit', ref: ' Coffee Shop' },
  ];
  
  function displayTransactions(filtered) {
    const tbody = document.querySelector('#transactionTable tbody');
    tbody.innerHTML = '';
    filtered.forEach(tx => {
      const row = `<tr>
        <td>${tx.date}</td>
        <td>${tx.amount}</td>
        <td>${tx.type}</td>
        <td>${tx.ref}</td>
      </tr>`;
      tbody.innerHTML += row;
    });
  }
  
  function applyFilters() {
    const date = document.getElementById('dateFilter').value;
    const amount = document.getElementById('amountFilter').value;
    const type = document.getElementById('typeFilter').value;
    const ref = document.getElementById('searchRef').value.toLowerCase();
  
    const filtered = transactions.filter(tx => {
      return (!date || tx.date === date) &&
             (!amount || tx.amount == amount) &&
             (!type || tx.type === type) &&
             (!ref || tx.ref.toLowerCase().includes(ref));
    });
  
    displayTransactions(filtered);
  }
  
  function downloadCSV() {
    let csv = 'Date,Amount,Type,Reference\n';
    const rows = document.querySelectorAll('#transactionTable tbody tr');
    rows.forEach(row => {
      const cols = row.querySelectorAll('td');
      const rowData = Array.from(cols).map(col => col.textContent).join(',');
      csv += rowData + '\n';
    });
  
    const blob = new Blob([csv], { type: 'text/csv' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'transactions.csv';
    a.click();
  }
  
  window.onload = () => {
    displayTransactions(transactions);
  };
  