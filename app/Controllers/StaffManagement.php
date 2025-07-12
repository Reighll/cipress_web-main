<?php

namespace App\Controllers;

use App\Models\StaffModel;
use CodeIgniter\API\ResponseTrait;

class StaffManagement extends BaseOwnerController
{
    use ResponseTrait;

    public function index()
    {
        $staffModel = new StaffModel();
        $data['approved_staff'] = $staffModel->where('staff_status', 'approved')->findAll();
        return view('staff_management', $data);
    }

    public function deleteStaff()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('Direct access is not allowed.');
        }

        $staffModel = new StaffModel();
        $staffId = $this->request->getJSON()->id ?? null;

        if ($staffId === null) {
            return $this->failBadRequest('Staff ID is required.');
        }

        if ($staffModel->find($staffId) === null) {
            return $this->failNotFound('Staff member not found.');
        }

        if ($staffModel->delete($staffId)) {
            return $this->respondDeleted(['success' => true, 'message' => 'Staff member deleted successfully.']);
        } else {
            return $this->failServerError('Could not delete the staff member.');
        }
    }
}