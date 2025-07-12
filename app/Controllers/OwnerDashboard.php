<?php

namespace App\Controllers;

use App\Models\OwnerModel;
use App\Models\StaffModel;
use CodeIgniter\Controller;

class OwnerDashboard extends BaseOwnerController // Make sure it extends your BaseOwnerController
{
    public function index()
    {
        $ownerModel = new OwnerModel();
        $staffModel = new StaffModel();

        // Get the logged-in owner's ID from the session
        $ownerId = session()->get('owner_id');

        // Fetch the owner's details, including the system key
        $data['owner'] = $ownerModel->find($ownerId);

        // Fetch all staff members with a 'pending' status
        $data['pending_staff'] = $staffModel->where('staff_status', 'pending')->findAll();

        return view('owner_dashboard', $data);
    }

    /**
     * Approve a staff member's registration.
     *
     * @param int $staffId The ID of the staff to approve.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function approveStaff($staffId)
    {
        $staffModel = new StaffModel();

        // Find the staff member by their ID
        $staff = $staffModel->find($staffId);

        if ($staff) {
            // Update the staff's status to 'approved'
            $staffModel->update($staffId, ['staff_status' => 'approved']);
            return redirect()->to('owner_dashboard')->with('success', 'Staff member has been approved.');
        }

        return redirect()->to('owner_dashboard')->with('error', 'Staff member not found.');
    }

    /**
     * Decline a staff member's registration.
     *
     * @param int $staffId The ID of the staff to decline.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function declineStaff($staffId)
    {
        $staffModel = new StaffModel();

        // Find the staff member by their ID
        $staff = $staffModel->find($staffId);

        if ($staff) {
            // Update the staff's status to 'declined'
            $staffModel->update($staffId, ['staff_status' => 'declined']);
            return redirect()->to('owner_dashboard')->with('success', 'Staff member has been declined.');
        }

        return redirect()->to('owner_dashboard')->with('error', 'Staff member not found.');
    }
}
