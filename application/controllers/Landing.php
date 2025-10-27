<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }
    
    /**
     * Get landing page content
     */
    public function get_content() {
        try {
            $this->load->database();
            
            $query = $this->db->get_where('landing_content', ['content_key' => 'homepage_content']);
            $result = $query->row();
            
            if ($result) {
                $content = json_decode($result->content_value, true);
                echo json_encode([
                    'success' => true,
                    'data' => $content
                ]);
            } else {
                // Return default content if not found
                $default_content = $this->get_default_content();
                echo json_encode([
                    'success' => true,
                    'data' => $default_content
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Save landing page content
     */
    public function save_content() {
        try {
            $this->load->database();
            
            $input = json_decode(file_get_contents('php://input'), true);
            $content_data = json_encode($input['content']);
            
            // Check if content exists
            $existing = $this->db->get_where('landing_content', ['content_key' => 'homepage_content'])->row();
            
            if ($existing) {
                // Update existing
                $this->db->where('content_key', 'homepage_content');
                $this->db->update('landing_content', [
                    'content_value' => $content_data,
                    'last_updated' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Insert new
                $this->db->insert('landing_content', [
                    'content_key' => 'homepage_content',
                    'content_value' => $content_data,
                    'last_updated' => date('Y-m-d H:i:s')
                ]);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Content saved successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get default content structure
     */
    private function get_default_content() {
        return [
            'logo' => '../assets/images/logo.png',
            'favicon' => '../assets/images/favicon.ico',
            'hero' => [
                'title' => 'Grow Your Social Media Smarter, Faster, Cheaper',
                'subtitle' => '🚀 World\'s #1 SMM Panel • Lightning Fast Delivery • Best Prices Guaranteed',
                'cta' => 'Start Free Trial'
            ],
            'features' => [
                [
                    'icon' => 'fas fa-dollar-sign',
                    'title' => 'Cheapest SMM Panel',
                    'description' => 'Our prices are the cheapest in the market, starting at 0.01$.'
                ],
                [
                    'icon' => 'fas fa-bolt',
                    'title' => 'Fastest SMM Panel',
                    'description' => 'We promise to deliver your order quickly, any time of day or night.'
                ],
                [
                    'icon' => 'fas fa-shield-alt',
                    'title' => 'Secure & Safe',
                    'description' => 'Your data is protected with advanced security measures.'
                ]
            ],
            'services' => [
                [
                    'icon' => 'fab fa-instagram',
                    'title' => 'Instagram',
                    'description' => 'Followers, Likes, Views, Comments, Story Views, IGTV, Reels'
                ],
                [
                    'icon' => 'fab fa-facebook',
                    'title' => 'Facebook',
                    'description' => 'Likes, Followers, Shares, Comments, Page Likes, Post Likes'
                ],
                [
                    'icon' => 'fab fa-youtube',
                    'title' => 'YouTube',
                    'description' => 'Subscribers, Views, Likes, Comments, Watch Time'
                ]
            ],
            'stats' => [
                [
                    'icon' => 'fas fa-clock',
                    'value' => '0.3 Sec',
                    'label' => 'An Order is made every'
                ],
                [
                    'icon' => 'fas fa-check-circle',
                    'value' => '56,720,621',
                    'label' => 'Orders Completed'
                ],
                [
                    'icon' => 'fas fa-dollar-sign',
                    'value' => '$0.001/1K',
                    'label' => 'Prices Starting From'
                ]
            ],
            'testimonials' => [],
            'faq' => [
                [
                    'question' => 'What are SMM panels?',
                    'answer' => 'SMM (Social Media Marketing) panels are platforms that provide social media services like followers, likes, views, and comments across various platforms such as Instagram, Facebook, Twitter, YouTube, and more.'
                ]
            ],
            'contact' => [
                'email' => 'contact@smmturk.com',
                'phone' => '+1 234 567 8900',
                'address' => '123 Main St, City, Country',
                'hours' => '24/7'
            ],
            'social' => [
                'twitter' => 'https://twitter.com/smmturk',
                'facebook' => 'https://facebook.com/smmturk',
                'instagram' => 'https://instagram.com/smmturk',
                'telegram' => 'https://t.me/smmturk'
            ],
            'seo' => [
                'title' => 'SMM Turk - Best SMM Panel',
                'description' => 'Best SMM panel for social media services with fast delivery and competitive prices.',
                'keywords' => 'smm panel, social media marketing, instagram followers'
            ],
            'footer' => [
                'copyright' => '&copy; Copyright 2025. All Rights Reserved by SMM Turk.',
                'description' => 'World\'s Best Cheap & Easy SMM Panel. Your partner in business expansion for the last 8 years.',
                'logo' => '../assets/images/logo.png',
                'bgColor' => '#1a1a2e'
            ]
        ];
    }
}
