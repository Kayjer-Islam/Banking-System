document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
    setupForm();
    fetchStatus();
});

// Load loan products and fill product select
function fetchProducts() {
    fetch('../controller/loanController.php?action=products')
        .then(res => res.json())
        .then(products => {
            let listHTML = products.map(prod =>
                `<div>
                    <b>${prod.name}</b><ul>
                        <li>Interest: ${prod.interest_rate}%</li>
                        <li>Max Amount: $${prod.max_amount}</li>
                        <li>Duration: ${prod.duration_months} months</li>
                        <li>${prod.description ?? ''}</li>
                    </ul>
                </div>`).join('');
            document.getElementById('product-list').innerHTML = listHTML;
            let sel = document.getElementById('loan-product-select');
            sel.innerHTML = '';
            products.forEach(prod => {
                let opt = document.createElement('option');
                opt.value = prod.id;
                opt.textContent = prod.name;
                sel.appendChild(opt);
            });
        });
}

function setupForm() {
    document.getElementById('loan-application-form').onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('../controller/loanController.php?action=apply', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('application-result').textContent = data.success ? 'Application submitted!' : 'Failed!';
            fetchStatus();
        });
    };
}

function fetchStatus() {
    fetch('../controller/loanController.php?action=status')
        .then(res => res.json())
        .then(apps => {
            let html = `<table border="1" width="100%"><tr>
                <th>Product</th><th>Status</th><th>Submitted</th><th>Proof</th></tr>`;
            for (const app of apps) {
                html += `<tr>
                    <td>${app.product_name}</td>
                    <td>${app.status}</td>
                    <td>${app.submission_date}</td>
                    <td>${app.income_proof ? '<a href="' + app.income_proof + '" target="_blank">View</a>' : ''}</td>
                 </tr>`;
            }
            html += '</table>';
            document.getElementById('application-status-list').innerHTML = html;
        });
}