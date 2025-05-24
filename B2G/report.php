<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <style>
    /* Button */
.btn {
    background-color: #3498db;  /* New beautiful color */
    color: white;
    padding: 15px 30px;   /* Larger padding to make the button bigger */
    font-size: 16px;       /* Slightly larger font for better visibility */
    border: none;
    border-radius: 8px;    /* Softer rounded corners */
    cursor: pointer;
    margin: 20px 0;        /* Margin for spacing above and below the button */
    transition: background-color 0.3s ease;
    min-width: 100px;      /* Ensuring a good width for the button */
    text-align: center;
    display: inline-block;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.btn:hover {
    background-color: #2980b9; /* Darker shade when hovering */
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6); /* Darker background for better contrast */
    transition: opacity 0.3s ease;
}

/* Show modal */
.modal.show {
    display: block;
    opacity: 1;
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 10px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px; /* Slightly reduced max width for better responsiveness */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Slightly larger shadow for more depth */
    transition: transform 0.3s ease-in-out;
    animation: fadeIn 0.5s ease-out; /* Smooth fade-in effect */
}

/* Animation for smooth opening of modal */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Modal Header */
.modal-header {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Modal Footer */
.modal-footer {
    text-align: center;
    margin-top: 30px;
    padding-top: 15px;
}

/* Close, Save, and Update Buttons */
.close-btn, .save-btn, .update-btn {
    padding: 12px 24px;
    background-color: #3498db;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin: 10px;
}

.close-btn:hover, .save-btn:hover, .update-btn:hover {
    background-color: #2980b9;
}

.close-btn {
    background-color: #e74c3c;  /* Red color for close */
}

.close-btn:hover {
    background-color: #c0392b; /* Darker red for close */
}

.save-btn, .update-btn {
    background-color: #2ecc71;  /* Green color for save/update */
}

.save-btn:hover, .update-btn:hover {
    background-color: #27ae60; /* Darker green for hover */
}

/* Input fields inside modal */
input[type="text"], textarea {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus, textarea:focus {
    border-color: #3498db;
    outline: none;
}

textarea {
    resize: vertical;
    min-height: 150px;
}

/* Modal content transition effect */
.modal.show .modal-content {
    transform: scale(1);
}
    </style>
    
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="admin.php" class="sidebar-btn">Dashboard</a></li>
                    <li><a href="usermanagement.php" class="sidebar-btn">User Management</a></li>
                    <li><a href="productlisting.php" class="sidebar-btn">Product Listings</a></li>
                    <li><a href="orders.php" class="sidebar-btn">Orders</a></li>
                    <li><a href="payment.php" class="sidebar-btn">Payments</a></li>
                    <li><a href="report.php" class="sidebar-btn">Reports</a></li>
                    <li><a href="#" class="sidebar-btn">Compliance</a></li>
                    <li><a href="#" class="sidebar-btn">Settings</a></li>
                    <li><a href="#" class="sidebar-btn">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                Report Dashboard
            </header>

            <!-- Reports Section -->
            <section class="table-section">
                <h2>Reports</h2>
                <button class="btn" onclick="openAddReportModal()">Add Report</button>
                <table>
                    <tr>
                        <th>Report Type</th>
                        <th>Generated Date</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>Procurement</td>
                        <td>January 2025</td>
                        <td>
                            <button class="btn" onclick="viewReport('Procurement', 'January 2025', 'Detailed procurement report content goes here...')">View</button>
                            <button class="btn" onclick="openUpdateReportModal('Procurement', 'January 2025', 'Detailed procurement report content goes here...')">Update</button>
                            <button class="btn delete-btn" onclick="deleteReport('Procurement', 'January 2025')">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Financial</td>
                        <td>December 2024</td>
                        <td>
                            <button class="btn" onclick="viewReport('Financial', 'December 2024', 'Detailed financial report content goes here...')">View</button>
                            <button class="btn" onclick="openUpdateReportModal('Financial', 'December 2024', 'Detailed financial report content goes here...')">Update</button>
                            <button class="btn delete-btn" onclick="deleteReport('Financial', 'December 2024')">Delete</button>
                        </td>
                    </tr>
                </table>
            </section>

            <!-- Modal for Viewing Report -->
            <div id="reportModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">Report Details</div>
                    <p><strong>Report Type:</strong> <span id="reportType"></span></p>
                    <p><strong>Generated Date:</strong> <span id="reportDate"></span></p>
                    <p><strong>Report Content:</strong></p>
                    <p id="reportContent"></p>
                    <div class="modal-footer">
                        <button class="close-btn" onclick="closeReport()">Close</button>
                    </div>
                </div>
            </div>

            <!-- Modal for Add/Update Report -->
            <div id="addUpdateReportModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header" id="modalHeader">Add Report</div>
                    <form id="reportForm">
                        <label for="reportTypeInput">Report Type:</label>
                        <input type="text" id="reportTypeInput" name="reportType" required><br><br>
                        <label for="reportDateInput">Generated Date:</label>
                        <input type="text" id="reportDateInput" name="reportDate" required><br><br>
                        <label for="reportContentInput">Report Content:</label><br>
                        <textarea id="reportContentInput" name="reportContent" rows="4" required></textarea><br><br>
                        <div class="modal-footer">
                            <button type="button" class="close-btn" onclick="closeAddUpdateReport()">Close</button>
                            <button type="submit" class="save-btn" id="saveReportBtn">Save</button>
                            <button type="button" class="update-btn" id="updateReportBtn" style="display:none;">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Function to show the report modal with content (viewing report)
function viewReport(reportType, reportDate, content) {
    document.getElementById("reportType").textContent = reportType;
    document.getElementById("reportDate").textContent = reportDate;
    document.getElementById("reportContent").textContent = content;

    // Display the modal
    document.getElementById("reportModal").style.display = "flex";  // Shows the modal when View button is clicked
}

// Function to close the report modal
function closeReport() {
    document.getElementById("reportModal").style.display = "none"; // Hides the modal when Close button is clicked
}

// Function to open the Add Report modal
function openAddReportModal() {
    document.getElementById("addUpdateReportModal").style.display = "flex";
    document.getElementById("modalHeader").textContent = "Add Report";
    document.getElementById("saveReportBtn").style.display = "inline-block";
    document.getElementById("updateReportBtn").style.display = "none";
    document.getElementById("reportForm").reset();
}

// Function to close the Add/Update Report modal
function closeAddUpdateReport() {
    document.getElementById("addUpdateReportModal").style.display = "none"; // Close the modal when the user clicks Close
}

// Function to open the Update Report modal
function openUpdateReportModal(reportType, reportDate, content) {
    document.getElementById("addUpdateReportModal").style.display = "flex";
    document.getElementById("modalHeader").textContent = "Update Report";
    document.getElementById("saveReportBtn").style.display = "none";
    document.getElementById("updateReportBtn").style.display = "inline-block";
    
    // Populate the fields with the current report data
    document.getElementById("reportTypeInput").value = reportType;
    document.getElementById("reportDateInput").value = reportDate;
    document.getElementById("reportContentInput").value = content;
}

// Function to save the report (Add)
document.getElementById("reportForm").addEventListener("submit", function(event) {
    event.preventDefault();
    // Get form data
    const reportType = document.getElementById("reportTypeInput").value;
    const reportDate = document.getElementById("reportDateInput").value;
    const reportContent = document.getElementById("reportContentInput").value;

    // Logic to save the report (e.g., API call, or saving to a database)
    alert(`Report added: ${reportType} - ${reportDate}`);

    closeAddUpdateReport();
});

// Function to update the report
document.getElementById("updateReportBtn").addEventListener("click", function() {
    // Get updated report data
    const reportType = document.getElementById("reportTypeInput").value;
    const reportDate = document.getElementById("reportDateInput").value;
    const reportContent = document.getElementById("reportContentInput").value;

    // Logic to update the report (e.g., API call or database update)
    alert(`Report updated: ${reportType} - ${reportDate}`);

    closeAddUpdateReport();
});

// Function to delete the report
function deleteReport(reportType, reportDate) {
    // Logic to delete the report (e.g., API call or database delete)
    alert(`Report deleted: ${reportType} - ${reportDate}`);
}


        // JavaScript to add the active class on sidebar button click
        const sidebarButtons = document.querySelectorAll('.sidebar-btn');
        sidebarButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                sidebarButtons.forEach(btn => btn.parentElement.classList.remove('active'));
                // Add active class to the clicked button's parent
                this.parentElement.classList.add('active');
            });
        });
    </script>
</body>
</html>
