<?php
require_once 'backend/auth.php';
if (Auth::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokéDeck Manager | Premium</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container flex justify-between items-center">
            <a href="#" class="nav-brand">PokéDeck</a>
            <div>
                <!-- Login/Register Toggle (Simple implementation) -->
            </div>
        </div>
    </nav>

    <main class="container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
        <div class="flex gap-4" style="flex-wrap: wrap; justify-content: center; width: 100%;">
            
            <!-- Hero Section -->
            <div style="flex: 1; min-width: 300px; max-width: 500px;">
                <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; font-weight: 800; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; color: transparent;">
                    Master Your Deck Strategy
                </h1>
                <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: 2rem;">
                    Build, analyze, and compare your Pokémon TCG decks with our premium tools. 
                    Discover synergies and dominate the meta.
                </p>
                <div class="flex gap-4">
                    <span style="color: var(--primary);">✦ Real-time API Data</span>
                    <span style="color: var(--secondary);">✦ Synergy Analysis</span>
                </div>
            </div>

            <!-- Auth Box -->
            <div class="card" style="flex: 1; max-width: 400px; min-width: 300px;">
                <div id="auth-forms">
                    <!-- Tabs -->
                    <div class="flex gap-4" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">
                        <button class="btn-outline" style="border:none; padding: 0.5rem; color: white;" onclick="document.getElementById('login-section').classList.remove('hidden'); document.getElementById('register-section').classList.add('hidden');">Login</button>
                        <button class="btn-outline" style="border:none; padding: 0.5rem; color: var(--text-muted);" onclick="document.getElementById('login-section').classList.add('hidden'); document.getElementById('register-section').classList.remove('hidden');">Register</button>
                    </div>

                    <!-- Login -->
                    <div id="login-section">
                        <h2 style="margin-bottom: 1rem;">Welcome Back</h2>
                        <form id="loginForm">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-input" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                        </form>
                    </div>

                    <!-- Register -->
                    <div id="register-section" class="hidden">
                        <h2 style="margin-bottom: 1rem;">Create Account</h2>
                        <form id="registerForm">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-input" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/toast.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
