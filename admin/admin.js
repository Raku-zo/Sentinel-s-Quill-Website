/* ============================================================
   login.js — Login logic for html/admin-login.html
   Place this file in: js/login.js
   The Sentinel's Quill | Army's Angels Integrated School
   ============================================================ */

const loginBtn = document.getElementById('login-btn');
const errorMsg = document.getElementById('error-msg');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');

/** Show an error message inside the card */
function showError(message) {
  errorMsg.textContent = message;
  errorMsg.classList.add('visible');
}

/** Hide the error message */
function clearError() {
  errorMsg.textContent = '';
  errorMsg.classList.remove('visible');
}

/** Toggle loading state on the button */
function setLoading(loading) {
  loginBtn.disabled = loading;
  loginBtn.textContent = loading ? 'Logging in...' : 'Login';
}

/** Show a success message then redirect to the publisher dashboard */
function redirectToDashboard() {
  loginBtn.disabled = true;
  loginBtn.textContent = '✓ Login successful! Redirecting...';
  loginBtn.style.background = '#4caf50';

  setTimeout(() => {
    window.location.href = '../admin/index.html';
  }, 1000);
}

/** Main login handler */
async function handleLogin() {
  clearError();

  const username = usernameInput.value.trim();
  const password = passwordInput.value.trim();

  if (!username || !password) {
    showError('Please enter both username and password.');
    return;
  }

  setLoading(true);

  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username, password }),
    });

    const data = await response.json();

    if (data.success) {
      redirectToDashboard();
    } else {
      showError(data.message || 'Invalid username or password.');
      setLoading(false);
    }
  } catch {
    // ── Demo fallback (remove this block once backend is ready) ──
    if (username === 'admin' && password === 'admin') {
      redirectToDashboard();
    } else {
      showError('Invalid username or password.');
      setLoading(false);
    }
    // ─────────────────────────────────────────────────────────────
  }
}

// ── Event listeners ──────────────────────────────────────────
loginBtn.addEventListener('click', handleLogin);

document.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') handleLogin();
});

usernameInput.addEventListener('input', clearError);
passwordInput.addEventListener('input', clearError);