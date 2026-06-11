<?php
/**
 * ClinixPro - Hospital Management System
 * Rooms Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\Room;
use App\Models\User;

class RoomsController extends Controller
{
    public function index(): void
    {
        $this->requireAnyRole(['administrator', 'nurse', 'receptionist']);
        
        $rooms = Room::all([], 'room_number');
        $stats = Room::getStatistics();
        $user = $this->getCurrentUser();
        
        $this->view('rooms/index', [
            'title' => 'Rooms - ClinixPro',
            'rooms' => $rooms,
            'stats' => $stats,
            'user' => $user
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $this->view('rooms/create', [
            'title' => 'Add Room - ClinixPro',
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/rooms');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/rooms/create');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'room_number' => $this->sanitize($this->post('room_number')),
            'room_type' => $this->post('room_type'),
            'floor' => $this->post('floor'),
            'bed_capacity' => $this->post('bed_capacity'),
            'hourly_rate' => $this->post('hourly_rate'),
            'description' => $this->sanitize($this->post('description')),
            'is_active' => $this->post('is_active') === '1'
        ];
        
        if (empty($data['room_number']) || empty($data['room_type']) || empty($data['bed_capacity'])) {
            Session::flash('error', 'Please fill all required fields');
            $this->redirect('/rooms/create');
        }
        
        try {
            $roomId = Room::create($data);
            
            User::logActivity($currentUser['id'], 'room_created', 'room', $roomId);
            
            Session::flash('success', 'Room created successfully');
            $this->redirect('/rooms');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create room: ' . $e->getMessage());
            $this->redirect('/rooms/create');
        }
    }

    public function show(int $id): void
    {
        $this->requireAnyRole(['administrator', 'nurse', 'receptionist']);
        
        $room = Room::withOccupancy($id);
        
        if (!$room) {
            $this->view('errors/404', ['message' => 'Room not found']);
            return;
        }
        
        $this->view('rooms/show', [
            'title' => 'Room Details - ClinixPro',
            'room' => $room
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        $room = Room::find($id);
        
        if (!$room) {
            $this->view('errors/404', ['message' => 'Room not found']);
            return;
        }
        
        $this->view('rooms/edit', [
            'title' => 'Edit Room - ClinixPro',
            'room' => $room,
            'csrf_token' => Security::generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->requireRole('administrator');
        
        if (!$this->isPost()) {
            $this->redirect('/rooms');
        }
        
        if (!$this->validateCsrf()) {
            Session::flash('error', 'Invalid security token');
            $this->redirect('/rooms/' . $id . '/edit');
        }
        
        $currentUser = $this->getCurrentUser();
        
        $data = [
            'room_type' => $this->post('room_type'),
            'floor' => $this->post('floor'),
            'bed_capacity' => $this->post('bed_capacity'),
            'hourly_rate' => $this->post('hourly_rate'),
            'description' => $this->sanitize($this->post('description')),
            'is_active' => $this->post('is_active') === '1'
        ];
        
        try {
            Room::update($id, $data);
            
            User::logActivity($currentUser['id'], 'room_updated', 'room', $id);
            
            Session::flash('success', 'Room updated successfully');
            $this->redirect('/rooms/' . $id);
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update room: ' . $e->getMessage());
            $this->redirect('/rooms/' . $id . '/edit');
        }
    }
}
