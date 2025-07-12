<?php

namespace App\Controllers;

use App\Models\StaffModel;

class StaffManagement extends BaseOwnerController
{
    /**
     * Displays the main staff management page with a list of approved staff.
     */
    public function index()
    {
        $staffModel = new StaffModel();
        $data['approved_staff'] = $staffModel->where('staff_status', 'approved')->findAll();
        return view('staff_management', $data);
    }

    /**
     * Deletes a staff member from the database and redirects back.
     *
     * @param int $staffId The ID of the staff to delete.
     */
    public function deleteStaff($staffId)
    {
        $staffModel = new StaffModel();

        // Check if the staff member exists before trying to delete.
        if ($staffModel->find($staffId) === null) {
            return redirect()->to('/owner/staff-management')->with('error', 'Staff member not found.');
        }

        // Attempt to delete the staff member.
        if ($staffModel->delete($staffId)) {
            return redirect()->to('/owner/staff-management')->with('success', 'Staff member deleted successfully.');
        } else {
            // This might happen if there's a database error.
            return redirect()->to('/owner/staff-management')->with('error', 'Could not delete the staff member.');
        }
    }
}
