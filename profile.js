document.getElementById('full-profile-form').addEventListener('submit', function (e) {
  e.preventDefault(); // prevent default form submission

  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const phone = document.getElementById('phone').value.trim();
 

  const currentPass = document.getElementById('current-pass').value;
  const newPass = document.getElementById('new-pass').value;
  const confirmPass = document.getElementById('confirm-pass').value;

  // --- Profile validation ---
  if (!name || !email || !phone ) {
    alert('Please fill in all profile fields.');
    return;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert('Invalid email format.');
    return;
  }

  if (!/^\d+$/.test(phone)) {
    alert('Phone number must contain digits only.');
    return;
  }

  // --- Password validation ---
  const passwordFieldsFilled = currentPass || newPass || confirmPass;

  if (passwordFieldsFilled) {
    if (!currentPass || !newPass || !confirmPass) {
      alert('If changing your password, all fields must be filled.');
      return;
    }

    if (newPass.length < 6) {
      alert('New password must be at least 6 characters.');
      return;
    }

    if (newPass !== confirmPass) {
      alert('New password and confirmation do not match.');
      return;
    }

    if (currentPass === newPass) {
      alert('New password must be different from the current password.');
      return;
    }
  }

  // --- All validations passed ---
  alert('Profile updated successfully!');
  // Optional: send to server or submit form
  // this.submit();
});
