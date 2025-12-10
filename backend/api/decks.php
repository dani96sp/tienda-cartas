<?php
// backend/api/decks.php
require_once '../auth.php';
require_once '../db.php';

header('Content-Type: application/json');

$auth = new Auth();
$user = $auth->getCurrentUser();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$db = (new Database())->connect();
$method = $_SERVER['REQUEST_METHOD'];

// Helper to get input data
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    // List decks
    try {
        $stmt = $db->prepare("
            SELECT d.*, 
                   (SELECT image_url FROM deck_cards dc WHERE dc.deck_id = d.id LIMIT 1) as cover_image 
            FROM decks d 
            WHERE d.user_id = ? 
            ORDER BY d.created_at DESC
        ");
        $stmt->execute([$user['id']]);
        $decks = $stmt->fetchAll();
        echo json_encode(['success' => true, 'decks' => $decks]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Create new deck
    if (!isset($input['name']) || empty($input['name'])) {
        echo json_encode(['success' => false, 'message' => 'Deck name is required']);
        exit;
    }

    try {
        $db->beginTransaction();

        // 1. Insert Deck
        $stmt = $db->prepare("INSERT INTO decks (user_id, name, description, total_cards) VALUES (?, ?, ?, ?) RETURNING id");
        $stmt->execute([
            $user['id'],
            $input['name'],
            $input['description'] ?? '',
            count($input['cards'])
        ]);
        $deckId = $stmt->fetchColumn();

        // 2. Insert Cards
        if (isset($input['cards']) && is_array($input['cards'])) {
            $cardStmt = $db->prepare("INSERT INTO deck_cards (deck_id, api_card_id, name, image_url, supertype, types, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($input['cards'] as $card) {
                $cardStmt->execute([
                    $deckId,
                    $card['id'], // API ID
                    $card['name'],
                    $card['images']['small'] ?? '',
                    $card['supertype'],
                    json_encode($card['types'] ?? []),
                    $card['quantity'] ?? 1
                ]);
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Deck created successfully', 'deck_id' => $deckId]);

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to create deck: ' . $e->getMessage()]);
    }
} elseif ($method === 'DELETE') {
    // Delete deck
    // We assume ID is passed via query param ?id=123
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID required']);
        exit;
    }

    try {
        $stmt = $db->prepare("DELETE FROM decks WHERE id = ? AND user_id = ?");
        $stmt->execute([$_GET['id'], $user['id']]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Deck deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Deck not found or access denied']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
