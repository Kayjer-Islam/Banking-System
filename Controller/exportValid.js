function exportCSV() {
  const rows = document.querySelectorAll("table tr");
  const csv = [];

  rows.forEach(row => {
    const cols = row.querySelectorAll("th, td");
    const rowData = Array.from(cols).map(col => `"${col.innerText}"`).join(",");
    csv.push(rowData);
  });

  const csvString = csv.join("\n");
  const blob = new Blob([csvString], { type: "text/csv" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "transactions.csv";
  link.click();
}

function exportPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  doc.text("Transaction History", 20, 10);

  let y = 20;
  const rows = document.querySelectorAll("table tr");

  rows.forEach((row, index) => {
    const cols = row.querySelectorAll("th, td");
    const rowData = Array.from(cols).map(col => col.innerText).join(" | ");
    doc.text(rowData, 10, y);
    y += 10;
  });

  doc.save("transactions.pdf");
}
