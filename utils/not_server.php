<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class NotificationServer implements MessageComponentInterface {
    protected $clients;
    private $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->db = new mysqli("localhost", "root", "", "lostify");
        echo "connected to db successfully";

        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Received message: $msg\n"; // Debugging line
        $data = json_decode($msg, true);

        if (isset($data['user_id']) && isset($data['message'])) {
            $user_id = $data['user_id'];
            $message = $data['message'];

            // Save notification in the database
            $stmt = $this->db->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)");
            if ($stmt) {
                $stmt->bind_param("is", $user_id, $message);
                if ($stmt->execute()) {
                    echo "Notification saved for user_id: $user_id\n"; // Debugging line
                } else {
                    echo "Error executing statement: " . $stmt->error . "\n"; // Debugging line
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $this->db->error . "\n"; // Debugging line
            }

            // Send notification to all connected clients
            foreach ($this->clients as $client) {
                $client->send(json_encode(["message" => $message]));
            }
        } else {
            echo "Invalid message format\n"; // Debugging line
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(new WsServer(new NotificationServer())),
    8080
);

$server->run();
?>