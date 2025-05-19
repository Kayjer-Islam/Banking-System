
function generateCaptcha() {
  const a = Math.floor(Math.random() * 10 + 1);
  const b = Math.floor(Math.random() * 10 + 1);
  const answer = a + b;

  document.getElementById('captchaLabel').textContent = `What is ${a} + ${b}?`;
  document.getElementById('captchaAnswer').value = answer;
}

window.onload = generateCaptcha;
