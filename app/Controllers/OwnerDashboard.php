<?php

namespace App\Controllers;

use App\Models\OwnerModel;
use App\Models\StaffModel;
use CodeIgniter\Controller;

// Ensure this controller extends your BaseOwnerController to be protected by the auth filter.
class OwnerDashboard extends BaseOwnerController
{
    /**
     * Displays the main owner dashboard page.
     */
    public function index()
    {
        $ownerModel = new OwnerModel();
        $staffModel = new StaffModel();

        // Get the logged-in owner's ID from the session.
        $ownerId = session()->get('owner_id');

        // Fetch the owner's details to get their system key.
        $data['owner'] = $ownerModel->find($ownerId);

        // Fetch all staff members with a 'pending' or 'declined' status for review.
        $data['staff_for_review'] = $staffModel
            ->whereIn('staff_status', ['pending', 'declined'])
            ->findAll();

        return view('owner_dashboard', $data);
    }

    /**
     * Approves a staff member's registration.
     *
     * @param int $staffId The ID of the staff to approve.
     */
    public function approveStaff($staffId)
    {
        $staffModel = new StaffModel();

        // Find the staff member to ensure they exist.
        $staff = $staffModel->find($staffId);

        if ($staff) {
            // Update the staff's status to 'approved'.
            $staffModel->update($staffId, ['staff_status' => 'approved']);
            // Redirect back to the dashboard with a success message.
            return redirect()->to('/owner/dashboard')->with('success', 'Staff member has been approved.');
        }

        return redirect()->to('/owner/dashboard')->with('error', 'Staff member not found.');
    }

    /**
     * Declines a staff member's registration.
     *
     * @param int $staffId The ID of the staff to decline.
     */
    public function declineStaff($staffId)
    {
        $staffModel = new StaffModel();

        // Find the staff member to ensure they exist.
        $staff = $staffModel->find($staffId);

        if ($staff) {
            // Update the staff's status to 'declined'.
            $staffModel->update($staffId, ['staff_status' => 'declined']);
            // Redirect back to the dashboard with a success message.
            return redirect()->to('/owner/dashboard')->with('success', 'Staff member has been declined.');
        }

        return redirect()->to('/owner/dashboard')->with('error', 'Staff member not found.');
    }
}
