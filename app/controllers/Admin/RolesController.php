<?php
/**
 * ClinixPro - Hospital Management System
 * Admin Roles Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Core\Database;

class RolesController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $roles = Database::fetchAll("SELECT * FROM roles ORDER BY id");
        
        $this->view('admin/roles/index', [
            'title' => 'Manage Roles - ClinixPro',
            'roles' => $roles
        ]);
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $role = Database::fetchOne("SELECT * FROM roles WHERE id = ?", [$id]);
        
        if (!$role) {
            $this->view('errors/404', ['message' => 'Role not found']);
            return;
        }
        
        $this->view('admin/roles/show', [
            'title' => 'Role Details - ClinixPro',
            'role' => $role
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $role = Database::fetchOne("SELECT * FROM roles WHERE id = ?", [$id]);
        
        if (!$role) {
            $this->view('errors/404', ['message' => 'Role not found']);
            return;
        }
        
        $this->view('admin/roles/edit', [
            'title' => 'Edit Role - ClinixPro',
            'role' => $role,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/admin/roles/' . $id);
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/admin/roles/' . $id . '/edit');
        }
        
        $name = $this->post('name');
        $description = $this->post('description');
        $requiresApproval = $this->post('requires_approval') === '1';
        $autoApprove = $this->post('auto_approve') === '1';
        
        // Parse can_approve_roles array
        $canApproveRoles = $this->post('can_approve_roles');
        if (is_array($canApproveRoles)) {
            $canApproveRoles = array_map('intval', $canApproveRoles);
        } else {
            $canApproveRoles = [];
        }
        
        // Parse permissions JSON
        $permissions = $this->post('permissions');
        $permissionsJson = is_array($permissions) ? json_encode($permissions) : '{}';
        
        try {
            Database::update(
                'roles',
                [
                    'name' => $name,
                    'description' => $description,
                    'permissions' => $permissionsJson,
                    'can_approve_roles' => $canApproveRoles,
                    'requires_approval' => $requiresApproval,
                    'auto_approve' => $autoApprove
                ],
                'id = ?',
                [$id]
            );
            
            Session::flash('success', 'Role updated successfully');
            $this->redirect('/admin/roles/' . $id);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update role: ' . $e->getMessage());
            $this->redirect('/admin/roles/' . $id . '/edit');
        }
    }
}
