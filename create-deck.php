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
    <title>Builder | PokéDeck</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .builder-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 900px) {
            .builder-layout { grid-template-columns: 1fr; }
        }

        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .card-item {
            position: relative;
            cursor: pointer;
            transition: transform 0.1s;
        }
        .card-item:hover { transform: scale(1.05); z-index: 10; }
        .card-item img { width: 100%; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }

        .deck-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            background: rgba(255,255,255,0.05);
            margin-bottom: 0.5rem;
            border-radius: 4px;
        }
        .scroll-custom::-webkit-scrollbar { width: 8px; }
        .scroll-custom::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .scroll-custom::-webkit-scrollbar-thumb { background: var(--border-glass); border-radius: 4px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container flex justify-between items-center">
            <a href="dashboard.php" class="nav-brand">PokéDeck</a>
            <div class="flex gap-4 items-center">
                <a href="dashboard.php" class="btn btn-outline" style="font-size: 0.9rem;">Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        <div class="builder-layout">
            
            <!-- Sidebar: Deck Info -->
            <div class="card" style="position: sticky; top: 100px;">
                <div class="form-group">
                    <label class="form-label">Deck Name</label>
                    <input type="text" id="deckName" class="form-input" placeholder="e.g. Electric Spark">
                </div>
                
                <div class="flex justify-between items-center" style="margin-bottom: 1rem;">
                    <h3>Cards (<span id="cardCount">0</span>)</h3>
                    <button id="saveDeckBtn" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Save Deck</button>
                </div>

                <div id="deckList" class="scroll-custom" style="max-height: 50vh; overflow-y: auto;">
                    <p style="color: var(--text-muted); text-align: center; margin-top: 2rem;">Deck is empty.<br>Select cards to add.</p>
                </div>
            </div>

            <!-- Main: Card Search -->
            <div>
                <div class="card" style="margin-bottom: 2rem;">
                    <div class="flex gap-4">
                        <input type="text" id="searchInput" class="form-input" placeholder="Search Pokémon (e.g. Pikachu)...">
                        <button id="searchBtn" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div id="searchResults" class="search-results scroll-custom">
                    <!-- Results injection -->
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 4rem;">
                        <i class="fas fa-search" style="font-size: 3rem; opacity: 0.5; margin-bottom: 1rem;"></i>
                        <p>Search for cards to begin building.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/toast.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/deck-builder.js"></script>
</body>
</html>
