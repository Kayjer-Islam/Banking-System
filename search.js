// Sample banking services dataset
const items = [
    { name: "Savings Account", category: "Account" },
    { name: "Current Account", category: "Account" },
    { name: "Home Loan", category: "Loan"},
    { name: "Personal Loan", category: "Loan" },
    { name: "Platinum Credit Card", category: "CreditCard"},
    { name: "Student Credit Card", category: "CreditCard" },
    { name: "Online Banking", category: "Service"},
    { name: "Mobile Banking App", category: "Service" }
  ];
  
  const resultsContainer = document.getElementById("results");
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  
  // Display items on page load
  window.onload = filterItems;
  
  // Function to filter and render items
  function filterItems() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value;
  
    const filteredItems = items.filter(item => {
      const matchesSearch = item.name.toLowerCase().includes(searchTerm) ||
                            item.description.toLowerCase().includes(searchTerm);
      const matchesCategory = selectedCategory === "All" || item.category === selectedCategory;
      return matchesSearch && matchesCategory;
    });
  
    renderItems(filteredItems);
  }
  
  // Render the filtered items to the page
  function renderItems(filteredItems) {
    resultsContainer.innerHTML = "";
  
    if (filteredItems.length === 0) {
      resultsContainer.innerHTML = "<p>No results found.</p>";
      return;
    }
  
    filteredItems.forEach(item => {
      const div = document.createElement("div");
      div.className = "result-item";
      div.innerHTML = `
        <h3>${item.name}</h3>
        <p>${item.description}</p>
      `;
      resultsContainer.appendChild(div);
    });
  }
  