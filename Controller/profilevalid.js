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


function isValidEmail(email) {
  if (!email.includes("@")) return false;

  const parts = email.split("@");
  if (parts.length !== 2) return false;

  const localPart = parts[0];
  const domainPart = parts[1];

  if (localPart.length === 0 || domainPart.length === 0) return false;
  if (!domainPart.includes(".")) return false;

  const domainParts = domainPart.split(".");
  if (domainParts.some(part => part.length === 0)) return false;

  return true;
}


function isValidPhone(phone) {
  if (phone.length !== 11) return false;

  for (let i = 0; i < phone.length; i++) {
    const char = phone[i];
    if (char < '0' || char > '9') return false;
  }

  return true;
}


if (!isValidEmail(email)) {
  alert("Invalid email format.");
  return false;
}

if (!isValidPhone(phone)) {
  alert("Phone number must be 11 digits.");
  return false;
}

  if (newPass !== '') {
    if (newPass.length < 8) {
      alert("New password must be at least 8 characters.");
      return false;
    }
    if (newPass !== confirmPass) {
      alert("New password and confirmation do not match.");
      return false;
    }
  }

  return true; 
}
