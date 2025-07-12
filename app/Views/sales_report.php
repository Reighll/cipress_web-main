<?= $this->extend('layouts/owner_layout') ?>

<?= $this->section('title') ?>
    Sales Report
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">SALES REPORT</h2>

            <ul class="nav nav-tabs" id="sales-report-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="today-tab" data-period="today" href="#" role="tab">Today's Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="week-tab" data-period="week" href="#" role="tab">This Week</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="month-tab" data-period="month" href="#" role="tab">Monthly Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="annual-tab" data-period="year" href="#" role="tab">Annual Sales</a>
                </li>
            </ul>

            <div class="mt-4">
                <h3 id="report-title">TODAY'S REPORT</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th>DATE</th>
                            <th>ITEM</th>
                            <th class="text-right">PRICE</th>
                            <th class="text-center">QTY</th>
                            <th class="text-right">SUBTOTAL</th>
                        </tr>
                        </thead>
                        <tbody id="sales-tbody">
                        </tbody>
                    </table>
                    <div id="loading-indicator" class="text-center my-4" style="display: none;">
                        <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                </div>
                <div class="text-right mt-3">
                    <h4>
                        <span id="total-sales-label">TOTAL SALES TODAY</span> :
                        <strong id="total-sales-value">₱0.00</strong>
                    </h4>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesTabsContainer = document.getElementById('sales-report-tabs');
            const salesTbody = document.getElementById('sales-tbody');
            const reportTitle = document.getElementById('report-title');
            const totalSalesLabel = document.getElementById('total-sales-label');
            const totalSalesValue = document.getElementById('total-sales-value');
            const loadingIndicator = document.getElementById('loading-indicator');

            /**
             * Fetches sales report data for a given period and updates the UI.
             * @param {string} period The reporting period ('today', 'week', 'month', 'year').
             */
            async function fetchSalesReport(period = 'today') {
                loadingIndicator.style.display = 'block';
                salesTbody.innerHTML = '';
                totalSalesValue.textContent = '...';

                try {
                    // This is the single, flexible backend endpoint you need to create.
                    const response = await fetch(`/api/owner/sales-report?period=${period}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const reportData = await response.json();
                    renderReport(reportData, period);

                } catch (error) {
                    console.error('Failed to fetch sales report:', error);
                    salesTbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading report data.</td></tr>';
                    totalSalesValue.textContent = 'Error';
                } finally {
                    loadingIndicator.style.display = 'none';
                }
            }

            /**
             * Renders the fetched report data into the DOM.
             * @param {object} data The report data from the server.
             * @param {string} period The reporting period.
             */
            function renderReport(data, period) {
                // Update titles
                const periodTitle = period.charAt(0).toUpperCase() + period.slice(1);
                reportTitle.textContent = `${periodTitle.replace('Today', "Today's").toUpperCase()}'S REPORT`;
                totalSalesLabel.textContent = `TOTAL SALES FOR ${period.toUpperCase()}`;
                if (period === 'today') {
                    totalSalesLabel.textContent = `TOTAL SALES TODAY`;
                }


                // Update total sales value
                const formattedTotal = parseFloat(data.total_sales || 0).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
                totalSalesValue.textContent = formattedTotal;

                // Populate table
                if (!data.sales || data.sales.length === 0) {
                    salesTbody.innerHTML = '<tr><td colspan="5" class="text-center">No sales recorded for this period.</td></tr>';
                    return;
                }

                data.sales.forEach(sale => {
                    const saleDate = new Date(sale.date).toLocaleDateString('en-CA'); // YYYY-MM-DD format
                    const price = parseFloat(sale.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                    const subtotal = parseFloat(sale.subtotal).toLocaleString('en-PH', { minimumFractionDigits: 2 });

                    const row = `
                <tr>
                    <td>${saleDate}</td>
                    <td>${sale.item_name}</td>
                    <td class="text-right">₱${price}</td>
                    <td class="text-center">${sale.quantity}</td>
                    <td class="text-right">₱${subtotal}</td>
                </tr>
            `;
                    salesTbody.insertAdjacentHTML('beforeend', row);
                });
            }

            // Event listener for tab clicks
            salesTabsContainer.addEventListener('click', function(event) {
                event.preventDefault();
                const clickedTab = event.target;

                if (clickedTab.tagName === 'A' && !clickedTab.classList.contains('active')) {
                    // Update active state for tabs
                    document.querySelector('#sales-report-tabs .nav-link.active').classList.remove('active');
                    clickedTab.classList.add('active');

                    const period = clickedTab.dataset.period;
                    fetchSalesReport(period);
                }
            });

            // Initial load for today's report
            fetchSalesReport('today');
        });
    </script>
<?= $this->endSection() ?>