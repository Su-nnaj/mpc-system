<?php

namespace App\Controllers;

class ChatController {
    protected $sessions = [];

    public function startSession($userId) {
        $sessionId = uniqid('session_');
        $this->sessions[$sessionId] = ['userId' => $userId, 'messages' => []];
        return $sessionId;
    }

    public function sendMessage($sessionId, $message) {
        if (isset($this->sessions[$sessionId])) {
            $this->sessions[$sessionId]['messages'][] = $message;
            return true;
        }
        return false;
    }

    public function getMessages($sessionId) {
        if (isset($this->sessions[$sessionId])) {
            return $this->sessions[$sessionId]['messages'];
        }
        return [];
    }
}

?>