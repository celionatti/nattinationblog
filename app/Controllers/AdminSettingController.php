<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Setting Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities.
| It includes methods for managing users, content, and site settings.
*/

use App\Models\Setting;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminSettingController extends Controller
{
    public function manage(): Response
    {
        $data = [];
        return $this->view('admin.settings.manage', $data);
    }

    /**
     * Get settings by group
     */
    public function getSettings(Request $request): Response
    {
        $queryParams = $request->getQueryParams();
        $group = $queryParams['group'] ?? 'general';
        
        $settings = Setting::getByGroup($group);
        
        return $this->json([
            'success' => true,
            'data' => $settings
        ], 200);
    }

    /**
     * Save settings
     */
    public function saveSettings(Request $request, Response $response): Response
    {
        try {
            // Parse JSON body for POST requests
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true) ?? [];
            
            $group = $data['group'] ?? 'general';
            $settings = $data['settings'] ?? [];

            if (empty($settings)) {
                return $this->json([
                    'success' => false,
                    'message' => 'No settings provided'
                ], $response->getStatusCode() ?? 400);
            }

            $result = Setting::updateMultiple($settings);

            if ($result) {
                return $this->json([
                    'success' => true,
                    'message' => 'Settings saved successfully'
                ], $response->getStatusCode());
            }

            return $this->json([
                'success' => false,
                'message' => 'Failed to save settings'
            ], $response->getStatusCode() ?? 500);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], $response->getStatusCode() ?? 500);
        }
    }

    /**
     * Get all settings (for admin)
     */
    public function getAllSettings(Request $request): Response
    {
        $settings = Setting::getAllSettings();
        
        return $this->json([
            'success' => true,
            'data' => $settings
        ], 200);
    }

    /**
     * Reset settings to defaults
     */
    public function resetToDefaults(Request $request, Response $response): Response
    {
        try {
            // Delete all existing settings
            Setting::query()->delete();
            
            // Initialize defaults
            Setting::initializeDefaults();
            
            return $this->json([
                'success' => true,
                'message' => 'Settings reset to defaults successfully'
            ], $response->getStatusCode());
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error resetting settings: ' . $e->getMessage()
            ], $response->getStatusCode() ?? 500);
        }
    }

    /**
     * Get a specific setting value
     */
    public function getSettingByKey(Request $request): Response
    {
        // $key = $key ?? '';
        $key = $request->getAttribute('key') ?? '';
        
        if (empty($key)) {
            return $this->json([
                'success' => false,
                'message' => 'Setting key is required'
            ], 400);
        }

        $value = Setting::getValue($key);
        
        return $this->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $value
            ]
        ], 200);
    }

    /**
     * Update a specific setting
     */
    public function updateSetting(Request $request, Response $response, array $args): Response
    {
        try {
            $key = $args['key'] ?? '';
            
            if (empty($key)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Setting key is required'
                ], $response->getStatusCode() ?? 400);
            }

            $body = $request->getBody()->getContents();
            $data = json_decode($body, true) ?? [];
            
            $value = $data['value'] ?? null;
            $group = $data['group'] ?? null;

            if ($value === null) {
                return $this->json([
                    'success' => false,
                    'message' => 'Setting value is required'
                ], $response->getStatusCode() ?? 400);
            }

            $result = Setting::setValue($key, $value, $group);

            if ($result) {
                return $this->json([
                    'success' => true,
                    'message' => 'Setting updated successfully'
                ], $response->getStatusCode());
            }

            return $this->json([
                'success' => false,
                'message' => 'Failed to update setting'
            ], $response->getStatusCode() ?? 500);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], $response->getStatusCode() ?? 500);
        }
    }
}