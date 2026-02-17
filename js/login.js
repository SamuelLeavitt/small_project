const urlBase = "backend/routes/Auth";
const extension = "php";

const loginForm = document.getElementById("loginForm");
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const loginMessage = document.getElementById("loginMessage");

if (loginForm) {
  loginForm.onsubmit = (e) => {
    e.preventDefault();

    const username = usernameInput.value.trim();
    const password = passwordInput.value;

    if (!username || !password) {
      if (loginMessage) loginMessage.textContent = "Please enter username and password.";
      return;
    }

    const xhr = new XMLHttpRequest();
    const url = `${urlBase}/Login.${extension}`;

    xhr.open("POST", url, true);
    xhr.withCredentials = true;
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      if (xhr.status !== 200) {
        console.error("[Login] HTTP", xhr.status);
        console.error(xhr.responseText);
        if (loginMessage) loginMessage.textContent = "Server error. Please try again.";
        return;
      }

      let json = null;
      try {
        json = JSON.parse(xhr.responseText);
      } catch {
        json = null;
      }

      if (json && json.success) {
        // Logged in; backend sets user_id cookie. Go to contacts.
        window.location.href = "contacts.html";
      } else {
        const msg = (json && (json.error || json.message)) || "Invalid username or password.";
        if (loginMessage) loginMessage.textContent = msg;
      }
    };

    xhr.send(JSON.stringify({ username, password }));
  };
}
