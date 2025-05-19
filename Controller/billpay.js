document.getElementById('billForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const inputs = this.querySelectorAll('input');
    const biller = inputs[0].value;
    const amount = inputs[1].value;
    const date = inputs[2].value;
  
    const li = document.createElement('li');
    li.textContent = `${biller} - $${amount} on ${date}`;
    document.getElementById('billList').appendChild(li);
    this.reset();
  });
  