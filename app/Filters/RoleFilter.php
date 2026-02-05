<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to continue');
        }

        // Check role if specified
        if ($arguments) {
            $requiredRole = $arguments[0];
            $userRoleId = $session->get('role_id');
            $isInstructor = $session->get('is_instructor');

            // Admin check
            if ($requiredRole === 'admin' && $userRoleId != 1) {
                return redirect()->to('/')->with('error', 'Access denied. Admin privileges required.');
            }

            // Instructor check
            if ($requiredRole === 'instructor' && $isInstructor != 1) {
                return redirect()->to('/')->with('error', 'Access denied. Instructor privileges required.');
            }

            // Student check
            if ($requiredRole === 'student' && ($userRoleId != 2 || $isInstructor == 1)) {
                return redirect()->to('/')->with('error', 'Access denied. Student account required.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
