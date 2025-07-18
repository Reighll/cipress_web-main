<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\StaffModel;
use CodeIgniter\I18n\Time;

class Attendance extends BaseStaffController
{
    /**
     * Displays the clock in/out page.
     */
    public function index()
    {
        $attendanceModel = new AttendanceModel();
        $staffId = session()->get('staff_id');

        // Find the last attendance record to determine current status
        $lastAttendance = $attendanceModel->where('staff_id', $staffId)
            ->orderBy('clock_in', 'DESC')
            ->first();

        // [THE FIX] Find the most recent record that has a clock-out time
        $lastClockOutRecord = $attendanceModel->where('staff_id', $staffId)
            ->where('clock_out IS NOT NULL')
            ->orderBy('clock_out', 'DESC')
            ->first();

        // Determine the current clock-in status
        $data['is_clocked_in'] = ($lastAttendance && $lastAttendance['clock_out'] === null);
        $data['last_clock_in'] = $lastAttendance['clock_in'] ?? null;
        $data['last_clock_out'] = $lastClockOutRecord['clock_out'] ?? null; // Pass the new data to the view

        return view('attendance', $data);
    }

    /**
     * Handles the clock-in action.
     */
    public function clockIn()
    {
        $attendanceModel = new AttendanceModel();
        $staffModel = new StaffModel();
        $staffId = session()->get('staff_id');

        // Record the clock-in time in the attendance table
        $attendanceData = [
            'staff_id' => $staffId,
            'clock_in' => new Time('now'),
        ];
        $attendanceModel->insert($attendanceData);

        // Update the last_clock_in status in the staff table
        $staffModel->update($staffId, ['last_clock_in' => $attendanceData['clock_in']]);

        return redirect()->to('staff/attendance')->with('success', 'You have been successfully clocked in.');
    }

    /**
     * Handles the clock-out action.
     */
    public function clockOut()
    {
        $attendanceModel = new AttendanceModel();
        $staffModel = new StaffModel();
        $staffId = session()->get('staff_id');

        // Find the last open attendance record (the one they are currently clocked in on)
        $lastAttendance = $attendanceModel->where('staff_id', $staffId)
            ->where('clock_out', null)
            ->orderBy('clock_in', 'DESC')
            ->first();

        if ($lastAttendance) {
            $clockOutTime = new Time('now');

            // Update the clock_out time for that record
            $attendanceModel->update($lastAttendance['attendance_id'], ['clock_out' => $clockOutTime]);

            // Update the last_clock_out status in the staff table
            $staffModel->update($staffId, ['last_clock_out' => $clockOutTime]);

            return redirect()->to('staff/attendance')->with('success', 'You have been successfully clocked out.');
        }

        return redirect()->to('staff/attendance')->with('error', 'Could not find an open session to clock out from.');
    }
}