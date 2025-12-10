// assets/js/deck-builder.js

const API_URL = 'backend/api/proxy_cards.php';
let currentDeck = [];

document.addEventListener('DOMContentLoaded', () => {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('searchResults');
    const saveBtn = document.getElementById('saveDeckBtn');

    // Search Handler
    const handleSearch = async () => {
        const query = searchInput.value.trim();
        if (!query) return;

        resultsContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center;">Searching...</p>';

        try {
            // Using a loose search query
            const res = await fetch(`${API_URL}?q=name:"${query}*"&pageSize=20`);
            const data = await res.json();

            renderResults(data.data);
        } catch (err) {
            console.error(err);
            resultsContainer.innerHTML = '<p style="color:red; grid-column: 1 / -1; text-align: center;">Error fetching cards.</p>';
        }
    };

    searchBtn.addEventListener('click', handleSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') handleSearch();
    });

    // Save Handler
    saveBtn.addEventListener('click', async () => {
        const name = document.getElementById('deckName').value.trim();
        if (!name) {
            alert('Please name your deck.');
            return;
        }
        if (currentDeck.length === 0) {
            alert('Add some cards first!');
            return;
        }

        saveBtn.innerText = 'Saving...';
        saveBtn.disabled = true;

        try {
            const res = await fetch('backend/api/decks.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    name: name,
                    description: 'Created via Builder',
                    cards: currentDeck
                })
            });
            const data = await res.json();

            if (data.success) {
                alert('Deck saved successfully!');
                window.location.href = 'dashboard.php';
            } else {
                alert('Error processing: ' + data.message);
                saveBtn.innerText = 'Save Deck';
                saveBtn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert('System error.');
            saveBtn.innerText = 'Save Deck';
            saveBtn.disabled = false;
        }
    });
});

function renderResults(cards) {
    const container = document.getElementById('searchResults');
    container.innerHTML = '';

    if (!cards || cards.length === 0) {
        container.innerHTML = '<p style="grid-column: 1 / -1; text-align: center;">No cards found.</p>';
        return;
    }

    cards.forEach(card => {
        const el = document.createElement('div');
        el.className = 'card-item';
        el.innerHTML = `<img src="${card.images.small}" loading="lazy" alt="${card.name}">`;

        el.addEventListener('click', () => addToDeck(card));

        container.appendChild(el);
    });
}

function addToDeck(card) {
    // Check if card exists in deck
    const existing = currentDeck.find(c => c.id === card.id);
    if (existing) {
        existing.quantity = (existing.quantity || 1) + 1;
    } else {
        // Flatten types for simpler storage
        card.quantity = 1;
        currentDeck.push(card);
    }
    updateDeckList();
}

function removeFromDeck(cardId) {
    const idx = currentDeck.findIndex(c => c.id === cardId);
    if (idx > -1) {
        if (currentDeck[idx].quantity > 1) {
            currentDeck[idx].quantity--;
        } else {
            currentDeck.splice(idx, 1);
        }
        updateDeckList();
    }
}

function updateDeckList() {
    const list = document.getElementById('deckList');
    const countEl = document.getElementById('cardCount');

    list.innerHTML = '';

    let total = 0;

    currentDeck.forEach(card => {
        total += card.quantity;

        const item = document.createElement('div');
        item.className = 'deck-list-item';
        item.innerHTML = `
            <div class="flex items-center gap-4">
                <img src="${card.images.small}" style="width: 30px; height: 42px;">
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">${card.name}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">x${card.quantity}</div>
                </div>
            </div>
            <button class="btn-outline" style="border:none; color: #ef4444;"><i class="fas fa-minus"></i></button>
        `;

        item.querySelector('button').addEventListener('click', () => removeFromDeck(card.id));
        list.appendChild(item);
    });

    countEl.innerText = total;

    if (total === 0) {
        list.innerHTML = '<p style="color: var(--text-muted); text-align: center; margin-top: 2rem;">Deck is empty.<br>Select cards to add.</p>';
    }
}
