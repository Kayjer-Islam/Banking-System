function calculateInterest() {
    const principal = parseFloat(document.getElementById('principal').value);
    const rate = parseFloat(document.getElementById('rate').value);
    const years = parseFloat(document.getElementById('years').value);
    const extraPayment = parseFloat(document.getElementById('extraPayment').value) || 0;
    const output = document.getElementById('output');
  
    if (isNaN(principal) || isNaN(rate) || isNaN(years)) {
      output.textContent = "Please fill in all required fields.";
      return;
    }
  
  
    const futureValue = principal * Math.pow((1 + rate / 100), years);
  
   
    const totalExtraPaid = extraPayment * 12 * years;
    const earlyPayoffSavings = (principal + futureValue * 0.1) - totalExtraPaid;
  
    output.innerHTML = `
      🔹 <strong>Forecasted Account Growth:</strong> ${futureValue.toFixed(2)}<br>
      🔹 <strong>Total Extra Paid with Early Payments:</strong> ${totalExtraPaid.toFixed(2)}<br>
      🔹 <strong>Estimated Early Payoff Savings:</strong> ${earlyPayoffSavings.toFixed(2)}
    `;
  }
  