

# Lostify Project

Lostify is a platform that allows users to report lost and found documents in a secure and real-time manner. Users can register and log in via **Google Authentication**. Once logged in, users can report **lost documents** and search for **found documents**. Real-time notifications are provided when new reports are made. WebSockets are used to provide these notifications to users immediately after a new report is added.

## Features

- **User Registration & Login**: Supports Google Authentication via OAuth 2.0.
- **Lost Document Reports**: Users can submit reports of lost documents with detailed information, including document type, category, location, description, and an uploaded image of the document.
- **Found Document Reports**: Users can submit reports of found documents in a similar format.
- **Real-time Notifications**: Notifies users instantly about new document reports using WebSockets.
- **File Upload**: Supports uploading document images (JPG, PNG, PDF) that are stored in the `uploads` directory.
- **Location Information**: Users can select locations (Province, District, Sector) based on data fetched from an external API.
- **User Profiles**: Each report is associated with the user's ID.

## Technologies Used

- **Frontend**: HTML, CSS (Tailwind), JavaScript (AJAX, WebSocket)
- **Backend**: PHP, MySQL, JWT for authentication
- **Real-Time Notifications**: WebSockets with `Ratchet` for PHP
- **Authentication**: Google OAuth 2.0 Integration
- **Database**: MySQL

## Setup

### 1. Clone the Repository

Clone the repository to your local machine:
```bash
git clone https://github.com/aine1100/lostify.git
cd lostify
```

### 2. Set up the Backend (PHP + WebSocket)

#### Step 1: Install Composer Dependencies

Make sure **Composer** is installed on your machine. If not, you can download it [here](https://getcomposer.org/).

Install the required dependencies:
```bash
composer install
```

#### Step 2: Configure Database

1. **Database Setup:**
    - Create a MySQL database named `lostify`.
    - Add your MySQL credentials to the `config/config.php` file:

    ```php
    <?php
    // config.php

    define('DB_HOST', getenv(DB_HOST));
    define('DB_USER', getenv(DB_USER));
    define('DB_PASS', getenv(DB_PASS));
    define('DB_NAME', getenv(DB_DATABASE));
    ?>
    ```

2. **Run Database Setup:**

    Create the following tables: `lost_documents`, `found_documents`, and `users`. Here is the SQL schema:

    ```sql
    CREATE TABLE `users` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `google_id` VARCHAR(255),
      `email` VARCHAR(255),
      `name` VARCHAR(255),
      PRIMARY KEY (`id`)
    );

    CREATE TABLE `lost_documents` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `user_id` INT NOT NULL,
      `document_type` VARCHAR(50),
      `category` VARCHAR(50),
      `province` VARCHAR(100),
      `district` VARCHAR(100),
      `sector` VARCHAR(100),
      `incident_date` DATE,
      `incident_time` TIME,
      `specific_location` VARCHAR(255),
      `description` TEXT,
      `document_image` VARCHAR(255),
      PRIMARY KEY (`id`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    );

    CREATE TABLE `found_documents` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `user_id` INT NOT NULL,
      `document_type` VARCHAR(50),
      `category` VARCHAR(50),
      `province` VARCHAR(100),
      `district` VARCHAR(100),
      `sector` VARCHAR(100),
      `incident_date` DATE,
      `incident_time` TIME,
      `specific_location` VARCHAR(255),
      `description` TEXT,
      `document_image` VARCHAR(255),
      PRIMARY KEY (`id`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    );
    ```

3. **Google Authentication Setup:**

   - To enable Google OAuth authentication, you'll need to set up a Google project and get **Client ID** and **Client Secret**.
   - Follow the [Google OAuth 2.0 guide](https://developers.google.com/identity/protocols/oauth2) to set it up.
   - Add the **Client ID** and **Client Secret** to your configuration file (`config/google_auth.php`).

### 3. WebSocket Server

The WebSocket server provides real-time notifications to users when a new report is added. It uses `Ratchet`, a PHP library for WebSocket communication.

#### Step 1: Install Ratchet Library

Make sure you have **Ratchet** installed via Composer:

```bash
composer require cboden/ratchet
```

#### Step 2: Run WebSocket Server

To start the WebSocket server, execute the following command in the root directory:

```bash
php not_server.php
```

This will start the WebSocket server, listening on port **8080**.

#### Step 3: Start PHP Server

You can run the PHP built-in server by executing the following command:

```bash
php -S localhost:8000
```

Now you can access your application in your browser at **[http://localhost:8000](http://localhost:8000)**.

### 4. WebSocket Notification Integration

The WebSocket notifications will notify users in real-time about new **lost** or **found** document reports.

#### Step 1: Update `server.php`

The WebSocket server is responsible for broadcasting messages to connected clients when a new document is reported:

```php
<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require 'vendor/autoload.php';

class NotificationServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Broadcast the message to all connected clients
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

$server = IoServer::factory(new HttpServer(new WsServer(new NotificationServer())), 8080);
$server->run();
?>
```

#### Step 2: Client-Side WebSocket Integration

You need to add a WebSocket client on the frontend to listen for notifications.

Here’s an example of the **notification.js** file:

```javascript
let socket = new WebSocket('ws://localhost:8080');

// When connected
socket.onopen = function(event) {
    console.log("Connected to the WebSocket server.");
};

// When receiving a message
socket.onmessage = function(event) {
    let notificationData = JSON.parse(event.data);
    displayNotification(notificationData);
};

// When an error occurs
socket.onerror = function(error) {
    console.log("WebSocket Error: ", error);
};

// When closed
socket.onclose = function(event) {
    console.log("Disconnected from WebSocket server.");
};

// Function to display notification
function displayNotification(data) {
    let notificationContainer = document.getElementById("notifications");
    let notification = document.createElement("div");
    notification.classList.add("notification");
    notification.innerText = `${data.user_name} reported a new document: ${data.document_type}`;
    notificationContainer.appendChild(notification);
}
```

### 5. Sending Notifications After Document Submission

When a user submits a **lost document report**, a WebSocket message is broadcasted to all connected clients.

Example:

```php
// Inside your document submission handler (after inserting data into the DB)
$notificationData = [
    'user_name' => $_SESSION['username'],
    'document_type' => $_POST['category']
];

// Send message to WebSocket server
$ws = new WebSocket\Client("ws://localhost:8080");
$ws->send(json_encode($notificationData));
```

### 6. Notifications Page

In your frontend, create a **Notifications page** where users can see all the incoming notifications.

Here’s a basic example in **notifications.php**:

```html
<div id="notifications">
    <!-- Notifications will be appended here -->
</div>

<script src="your notification file"></script>
```

### 7. Authentication Flow (Google OAuth)

1. **User Login:** When the user logs in using Google OAuth, their information is saved in the `users` table.
2. **JWT Authentication:** A JSON Web Token (JWT) is generated for the user, which is stored in a cookie. This token is used for subsequent requests to ensure the user is authenticated.

## Contributing

1. Fork the repo.
2. Create your feature branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-name`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

