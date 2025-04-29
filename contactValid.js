
    let captchaAnswer;


    function submitContactForm(e) {
      e.preventDefault();
      const userAnswer = parseInt(document.getElementById('captchaInput').value);

      if (userAnswer !== captchaAnswer) {
        alert("Incorrect CAPTCHA. Please try again.");
        generateCaptcha();
        return;
      }

      // Simulate form submission
      document.getElementById('contactFormPage').classList.remove('active');
      document.getElementById('confirmationPage').classList.add('active');
    }

    function goBack() {
      document.getElementById('contactForm').reset();
      document.getElementById('confirmationPage').classList.remove('active');
      document.getElementById('contactFormPage').classList.add('active');
      generateCaptcha();
    }

    
 
  