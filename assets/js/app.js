// assets/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Auth State
    const checkAuthState = async () => {
        // Simple check to see if we have a user session (optional, handled by PHP usually)
        // But we can use this to toggle UI elements if we were a SPA.
        // For this MPA, we rely on PHP rendering, but we can verify session via generic call if needed.
    };

    // Logout Handler
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                const formData = new FormData();
                formData.append('action', 'logout');

                const res = await fetch('backend/auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    window.location.href = 'index.php';
                }
            } catch (err) {
                console.error('Logout failed', err);
            }
        });
    }

    // Login Form Handler (if present)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            formData.append('action', 'login');

            try {
                const res = await fetch('backend/auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.href = 'dashboard.php', 1000);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                console.error('Login error', err);
                showToast('Login failed', 'error');
            }
        });
    }

    // Register Form Handler
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            formData.append('action', 'register');

            try {
                const res = await fetch('backend/auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    // Switch to login view or reload
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                console.error('Registration error', err);
                showToast('Registration failed', 'error');
            }
        });
    }
});
