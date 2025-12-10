<?php
require_once 'backend/auth.php';
if (!Auth::isLoggedIn()) {
    header('Location: index.php');
    exit;
}
$user = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PokéDeck</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body>
    <nav class="navbar">
        <div class="container flex justify-between items-center">
            <a href="dashboard.php" class="nav-brand">PokéDeck</a>
            
            <div class="user-profile">
                <!-- User Initials Avatar -->
                <div class="avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                </div>
                <span><?php echo htmlspecialchars($user['username']); ?></span>
                <a href="#" id="logoutBtn" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.8rem; margin-left: 1rem;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <main class="container" style="padding-top: 3rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h1 style="font-size: 2rem;">My Decks</h1>
            <a href="create-deck.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Deck
            </a>
        </div>

        <!-- Decks Grid -->
        <div id="decks-container" class="deck-grid">
            <!-- Loaded via JS -->
            <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 3rem;">
                Loading your decks...
            </div>
        </div>
    </main>

    <script src="assets/js/toast.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        // Inline script to load decks for simplicity
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const res = await fetch('backend/api/decks.php');
                const data = await res.json();
                
                const container = document.getElementById('decks-container');
                container.innerHTML = '';

                if (data.success && data.decks.length > 0) {
                    data.decks.forEach(deck => {
                        const bgImage = deck.cover_image ? `background-image: url('${deck.cover_image}')` : '';
                        
                        const html = `
                            <div class="deck-card">
                                <div class="deck-image" style="${bgImage}">
                                    ${!deck.cover_image ? '<div class="flex items-center justify-center" style="height:100%; color: var(--text-muted);">No Image</div>' : ''}
                                </div>
                                <div class="deck-info">
                                    <h3 class="deck-title">${deck.name}</h3>
                                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                                        ${deck.total_cards} Cards
                                    </p>
                                    <div class="flex gap-4">
                                        <button class="btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;" onclick="deleteDeck(${deck.id})">Delete</button>
                                        <!-- Future: View/Edit -->
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                } else {
                    container.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; border: 2px dashed var(--glass-border); border-radius: 1rem;">
                            <h3 style="margin-bottom: 1rem; color: var(--text-muted);">No decks found</h3>
                            <a href="create-deck.php" class="btn btn-primary">Create your first deck</a>
                        </div>
                    `;
                }
            } catch (err) {
                console.error('Failed to load decks', err);
            }
        });

        async function deleteDeck(id) {
            if(!confirm('Are you sure you want to delete this deck?')) return;
            try {
                const res = await fetch(`backend/api/decks.php?id=${id}`, { method: 'DELETE' });
                const data = await res.json();
                if(data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            } catch(e) { console.error(e); }
        }
    </script>
</body>
</html>
