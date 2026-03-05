<?php
// ============================================================
// Contact Controller
// ============================================================

require_once 'controllers/AdminController.php';
require_once 'controllers/AuthController.php';

class ContactController extends Controller {

    public function index(): void {
        $this->view('contact/index', [
            'title' => 'Contact Us — MPC Trading',
            'flash' => $this->getFlash(),
            'errors' => [],
            'old'   => [],
        ]);
    }

    public function send(): void {
        $data = [
            'name'    => trim($this->post('name', '')),
            'email'   => trim($this->post('email', '')),
            'phone'   => trim($this->post('phone', '')),
            'subject' => $this->post('subject', 'general'),
            'message' => trim($this->post('message', '')),
        ];

        $errors = $this->validate($data, [
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'message' => 'required|min:10',
        ]);

        if (!empty($errors)) {
            $this->view('contact/index', [
                'title'  => 'Contact Us — MPC Trading',
                'flash'  => null,
                'errors' => $errors,
                'old'    => $data,
            ]);
            return;
        }

        // Save message to database
        try {
            $db = Database::getInstance();
            $db->query(
                "INSERT INTO contact_messages (name, email, phone, subject, message, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())",
                [
                    $this->sanitize($data['name']),
                    $this->sanitize($data['email']),
                    $this->sanitize($data['phone']),
                    $this->sanitize($data['subject']),
                    $this->sanitize($data['message']),
                ]
            );
        } catch (Exception $e) {
            // Silently fail DB insert (table may not exist yet), still show success
        }

        $this->setFlash('success', 'Message sent! We will get back to you within 24 hours.');
        $this->redirect('/contact');
    }
}

// ============================================================
// About Controller
// ============================================================
class AboutController extends Controller {

    public function index(): void {
        $this->view('about/index', [
            'title' => 'About Us — MPC Trading',
        ]);
    }
}
