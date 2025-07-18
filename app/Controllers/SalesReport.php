<?php

namespace App\Controllers;

use App\Models\SaleModel;
// We no longer need the Time library since we are using standard PHP date functions
// use CodeIgniter\I18n\Time;

class SalesReport extends BaseOwnerController
{
    /**
     * Displays the enhanced sales report page.
     * This version uses standard PHP date functions for maximum compatibility.
     */
    public function index()
    {
        $saleModel = new SaleModel();

        // --- 1. Calculate Key Metrics using Standard PHP ---

        // [THE FIX] Replaced all CodeIgniter Time methods with universal date() and strtotime()
        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        $data['todays_sales'] = $saleModel->getTotalSalesForPeriod($today_start, $today_end);

        // Note: 'this week' can be ambiguous depending on server settings. 'last monday' is more reliable.
        $week_start = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $week_end = date('Y-m-d 23:59:59', strtotime('sunday this week'));
        $data['this_weeks_sales'] = $saleModel->getTotalSalesForPeriod($week_start, $week_end);

        $month_start = date('Y-m-01 00:00:00');
        $month_end = date('Y-m-t 23:59:59'); // 't' gets the last day of the month
        $data['this_months_sales'] = $saleModel->getTotalSalesForPeriod($month_start, $month_end);

        $year_start = date('Y-01-01 00:00:00');
        $year_end = date('Y-12-31 23:59:59');
        $data['this_years_sales'] = $saleModel->getTotalSalesForPeriod($year_start, $year_end);


        // --- 2. Handle Filtering ---
        $filter = $this->request->getGet('filter');
        $start_date_filter = null;
        $end_date_filter = null;
        $report_title = 'Sales Report';

        switch ($filter) {
            case 'today':
                $start_date_filter = $today_start;
                $end_date_filter = $today_end;
                $report_title = "Today's Sales Report";
                break;
            case 'week':
                $start_date_filter = $week_start;
                $end_date_filter = $week_end;
                $report_title = "This Week's Sales Report";
                break;
            case 'month':
                $start_date_filter = $month_start;
                $end_date_filter = $month_end;
                $report_title = "This Month's Sales Report";
                break;
            case 'year':
                $start_date_filter = $year_start;
                $end_date_filter = $year_end;
                $report_title = "This Year's Sales Report";
                break;
            default:
                if ($this->request->getGet('start_date') && $this->request->getGet('end_date')) {
                    $start_date_filter = $this->request->getGet('start_date') . ' 00:00:00';
                    $end_date_filter = $this->request->getGet('end_date') . ' 23:59:59';
                    $report_title = "Custom Date Range Report";
                }
        }

        // --- 3. Fetch Detailed Sales Data ---
        $sales_data = [];
        if ($start_date_filter && $end_date_filter) {
            $sales_data = $saleModel->getDetailedSalesReport($start_date_filter, $end_date_filter);
        }

        $data['sales_data'] = $sales_data;
        $data['report_title'] = $report_title;
        $data['total_sales_for_period'] = $saleModel->getTotalSalesForPeriod($start_date_filter, $end_date_filter);
        $data['start_date'] = $this->request->getGet('start_date') ?? '';
        $data['end_date'] = $this->request->getGet('end_date') ?? '';

        return view('sales_report', $data);
    }

    /**
     * Displays a printable receipt for a single sale.
     * This remains unchanged.
     */
    public function viewReceipt($sale_id)
    {
        $saleModel = new \App\Models\SaleModel();
        $saleDetails = $saleModel->getSaleDetails($sale_id);

        if (empty($saleDetails)) {
            return redirect()->to('owner/sales-report')->with('error', 'Sale not found.');
        }

        $data = [
            'sale_items' => $saleDetails['items'],
            'sale'       => $saleDetails
        ];
        unset($data['sale']['items']);

        return view('receipt', $data);
    }
}