function applyLoan() {
    const loanType = document.getElementById('loanType').value;
    const income = parseFloat(document.getElementById('income').value);
    const esign = document.getElementById('esign').checked;
    const output = document.getElementById('loanOutput');
  
    if (isNaN(income) || income <= 0) {
      output.textContent = "Please enter a valid income.";
      return;
    }
  
    if (!esign) {
      output.textContent = "You must agree to e-sign the loan agreement.";
      return;
    }
  
    output.textContent = `You have applied for a ${loanType} loan with an annual income of $${income}. E-signature accepted.`;
  }
  