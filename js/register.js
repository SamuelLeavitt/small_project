const authUrlBase = "backend/routes/Auth";
const authExtension = "php";

const registerForm = document.getElementById("loginForm");
const regUsername = document.getElementById("username");
const regEmail = document.getElementById("email");
const regPassword = document.getElementById("password");
const regConfirmPassword = document.getElementById("confirmPassword");
const registerMessage = document.getElementById("registerMessage");

if (registerForm) {
  registerForm.onsubmit = (e) => {
    e.preventDefault();

    const username = regUsername.value.trim();
    const email = regEmail ? regEmail.value.trim() : "";
    const password = regPassword.value;
    const confirmPassword = regConfirmPassword ? regConfirmPassword.value : "";

    if (!username || !email || !password || !confirmPassword) {
      if (registerMessage) registerMessage.textContent = "Please fill out all fields.";
      return;
    }

    if (password !== confirmPassword) {
      if (registerMessage) registerMessage.textContent = "Passwords do not match.";
      return;
    }

    const xhr = new XMLHttpRequest();
    const url = `${authUrlBase}/Register.${authExtension}`;

    xhr.open("POST", url, true);
    xhr.withCredentials = true;
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      if (xhr.status !== 200) {
        console.error("[Register] HTTP", xhr.status);
        console.error(xhr.responseText);
        if (registerMessage) registerMessage.textContent = "Server error. Please try again.";
        return;
      }

      let json = null;
      try {
        json = JSON.parse(xhr.responseText);
      } catch {
        json = null;
      }

      if (json && json.success) {
        // Registered and cookie set; go to contacts.
        window.location.href = "contacts.html";
      } else {
        const msg = (json && (json.error || json.message)) || "Registration failed.";
        if (registerMessage) registerMessage.textContent = msg;
      }
    };

    xhr.send(JSON.stringify({ username, email, password }));
  };
}
