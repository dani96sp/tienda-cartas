-- Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Decks table
CREATE TABLE IF NOT EXISTS decks (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    total_cards INTEGER DEFAULT 0,
    attributes JSONB, -- Stores aggregated stats if needed
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Deck Cards table (Stores local copy of necessary API data)
CREATE TABLE IF NOT EXISTS deck_cards (
    id SERIAL PRIMARY KEY,
    deck_id INTEGER REFERENCES decks(id) ON DELETE CASCADE,
    api_card_id VARCHAR(50) NOT NULL, -- ID from pokemontcg.io
    name VARCHAR(100) NOT NULL,
    image_url TEXT,
    supertype VARCHAR(50), -- Pokémon, Trainer, Energy
    types JSONB, -- Array of types e.g. ["Fire", "Flying"]
    quantity INTEGER DEFAULT 1
);
