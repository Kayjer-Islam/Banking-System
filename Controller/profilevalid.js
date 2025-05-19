function validateProfileForm() {
  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const newPass = document.getElementById('new-pass').value;
  const confirmPass = document.getElementById('confirm-pass').value;

  if (name === '' || email === '' || phone === '') {
    alert("Please fill in all required fields.");
    return false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Invalid email format.");
    return false;
  }

  
  if (!/^\d{11}$/.test(phone)) {
    alert("Phone number must be  11 digits.");
    return false;
  }

  if (newPass !== '' && newPass.length < 8) {
    alert("New password must be at least 8 characters long.");
    return false;
  }

  if (newPass !== '' && newPass !== confirmPass) {
    alert("New password and confirm password do not match.");
    return false;
  }

  return true; // Allow form to submit
}
