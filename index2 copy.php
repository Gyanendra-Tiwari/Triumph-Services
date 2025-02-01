
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPANY DATA</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>

    <style>
        #listBox {
    display: none;
    position: absolute;
    border: 1px solid #ccc;
    background-color: white;
    margin-left: 300px;
    margin-top: 40px;
    width: 400px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
}

.list-item {
    padding: 8px;
    cursor: pointer;
}

.list-item:hover {
    background-color: #f1f1f1;
}

    </style>
</head>
<body>

<div class="header">
    <div class="header-left">
    <span>Welcome Hi: ADMIN</span>
    </div>
    <div class="header-right">
        <span>Chapter list | Plan Expiry Date: 2025-12-10 <a href="logout.php">
            <input type="button" name="logout" value="Logout"style="height:25px; width:100px;"> </a>
        </span>
    </div>
</div>

<div class="main-nav">
    <ul>
       <img src="image/TriumphLogo.ico" alt="Company Logo"style="height: 60px; width:150px; margin:0px;"> 
       <h1>TRIUMPH SERVICES</h1>
         
       <img src="image/petals.ico" alt="Company Logo"style="height: 60px; width:120px; margin:0px;">
    </ul>
</div>
<div class="dump">
    <span>Search Company Report</span>
</div>

<div class="search-container">
  <div class="search-row">
        <select class="search-input" id="impexp">
            <option>Asn Report</option>
            <option>Po Report</option>
            <option>Fixation Pending</option>
            <option>Pending Documents Rep </option>
            <option>Advance Payment Pending </option>
            <option>Advance Paid Report </option>
            <option>Pending Payments </option>
            <option>Omc/V Metals Pending Payments </option>
            <option>Pending Shipment </option>
            <option>Courier Pending Report </option>
            <option>Sales Contract Pending </option>
            <option>Commission Report </option>
         </select>
         <select class="search-input" id="party-type" >
            <option>Select Party Type</option>
            <option>ALL</option>
            <option>BUYER</option>
            <option>SELLER</option>
        </select>
         <!-- Party Name Input with Autocomplete Feature -->
         <input type="text" id="txtPartyName" class="search-input" placeholder="Enter Party Name">
         <div id="listBox" class="list-box"></div>

        <input type="date" class="search-input" id="from-date" >
        <input type="date" class="search-input" id="to-date" >
        <div class="search-inputradio">BY C.N
        <input type="radio" value="YES"></div>
        
        <input type="text" class="search-inputc" placeholder="Contract_Number">
        <input type="text" class="search-inputc" placeholder="Enter Aso no">
       
    </div>

    <button class="search-button">SEARCH</button>
    <button class="reset-button">RESET</button>
    <button class="export-exel-button" style="height:32px; width:120px">EXPORT EXEL</button>
   
</div>

<div class="output"  id="searchResultsContainer">

</div>

<script>
$(document).ready(function() {
    // Trigger AJAX request to load the page content without showing index2.php in the address bar
    $.ajax({
        url: 'index2.php',  // URL of the content to load
        type: 'GET',
        success: function(response) {
            // Inject the content of index2.php into the div
            $('#content').html(response);
            
            // Change the URL without reloading the page (use replaceState to avoid creating a new history entry)
            window.history.replaceState({}, '', 'Petals infotech pvt ltd');
        },
        error: function() {
            alert('Error loading content.');
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // Function to handle Party Name auto-suggestions based on report type and party type
    function fetchPartySuggestions() {
        var partyName = $("#txtPartyName").val().trim();
        var partyType = $("#party-type").val();  // Get Party Type (BUYER/SELLER)

        // Only proceed if the party name is not empty and valid party type is selected
        if (partyName !== "" && (partyType === "BUYER" || partyType === "SELLER")) {
            var targetUrl = '';

            // Determine the target URL based on party type
            if (partyType === "BUYER") {
                targetUrl = 'function2/get_party_suggestions.php'; // Server script for BUYER
            } else if (partyType === "SELLER") {
                targetUrl = 'function2/get_seller_suggestions.php'; // Server script for SELLER
            }

            // Log the data being sent
            console.log("Sending data to " + targetUrl);
            console.log("Data sent: ", { name: partyName });

            // Make AJAX request to get suggestions from the server
            $.ajax({
                url: targetUrl,
                method: 'GET',
                data: { name: partyName },  // Send the party name as query parameter
                success: function(response) {
                    console.log("Response from server:", response); // Log the response to check if it's valid JSON
                    try {
                        // Parse the response assuming it is valid JSON and an array of party names
                        if (response && Array.isArray(response)) {
                            // Clear existing suggestions and show the new list
                            if (response.length > 0) {
                                $("#listBox").empty().show();
                                response.forEach(function(item) {
                                    $("#listBox").append("<div class='list-item'>" + item + "</div>");
                                });

                                // Click event for selecting a suggestion
                                $(".list-item").on("click", function() {
                                    $("#txtPartyName").val($(this).text());
                                    $("#listBox").hide();
                                });
                            } else {
                                $("#listBox").hide(); // Hide the list if no suggestions found
                            }
                        } else {
                            throw new Error("Invalid response format or empty data.");
                        }
                    } catch (e) {
                        console.error("Failed to parse response as JSON:", e);
                        alert("There was an error processing the data from the server.");
                    }
                },
                error: function() {
                    alert("There was an error processing your request.");
                }
            });
        } else {
            $("#listBox").hide(); // Hide the list if inputs are not valid
        }
    }

    // Debouncing: Delay the AJAX request to improve performance and reduce unnecessary requests
    var debounceTimer;
    $("#txtPartyName").on("input", function() {
        clearTimeout(debounceTimer);  // Clear previous timer
        debounceTimer = setTimeout(function() {
            fetchPartySuggestions();  // Call the function after delay
        }, 300);  // 300ms delay before triggering the AJAX request
    });

    // Hide list box if clicked outside of the input field
    $(document).click(function(e) {
        if (!$(e.target).closest("#txtPartyName").length) {
            $("#listBox").hide();
        }
    });

    // Prevent the list box from hiding when clicked inside
    $("#listBox").click(function(e) {
        e.stopPropagation();
    });

    // Optional: Hide the suggestion list when the input is cleared
    $("#txtPartyName").on("focus", function() {
        var partyName = $(this).val().trim();
        if (partyName === "") {
            $("#listBox").hide();
        }
    });
});
</script>


<script>
   $(document).ready(function() {
    $(".search-button").click(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

       
        // Get the values of the from-date and to-date
        var fromDate = $("#from-date").val().trim();
        var toDate = $("#to-date").val().trim();

        // Check if either from-date or to-date is empty
        if (!fromDate || !toDate) {
            alert("Please Select From_Date and To_Date Both.");
            return; // Stop the form submission if either date is not filled
        }


        // Check if radio button is selected
        var isRadioSelected = $("input[type='radio']").prop('checked');

        // Get contract number if the radio button is selected
        var contractNumber = $("input[placeholder='Contract_Number']").val().trim();

        // Prepare form data
        var formData = {
            party_type: $(".search-input:eq(1)").val(),
            party_name: $("#txtPartyName").val().trim(),
            from_date: $("#from-date").val(),
            to_date: $("#to-date").val(),
        };

        // If radio button is selected, include contract number and contract status
        if (isRadioSelected) {
            formData.contract_button = 'YES'; // Indicate that the contract button is selected
            if (contractNumber === "") {
                alert("Please fill in the Contract Number.");
                return; // Stop the form submission if contract number is not filled
            }
            formData.contract_number = contractNumber; // Include the contract number
        }

        // Log the form data to the console (for debugging)
        console.log("Form Data Sent to the API:", formData);

        // Determine which PHP file to send the request to based on selected report type
        var reportType = $("#impexp").val();
        var targetUrl;

        // Dynamically set the target PHP file based on the selected report
        switch (reportType) {
            case 'Asn Report':
                targetUrl = 'function1/ASN.php';
                break;
            case 'Po Report':
                targetUrl = 'function1/PO_REPORT.php';
                break;
            case 'Fixation Pending':
                targetUrl = 'function1/FIXATION_PENDING.php';
                break;
            case 'Pending Documents Rep':
                targetUrl = 'function1/PENDING_DOCUMENTS_REP.php';
                break;
            case 'Advance Payment Pending':
                targetUrl = 'function1/ADVANCED_PAYMENT_PENDING.php';
                break;
            case 'Advance Paid Report':
                targetUrl = 'function1/ADVANCED_PAID.php';
                break;
            case 'Pending Payments':
                targetUrl = 'function1/PENDING_PAYMENTS.php';
                break;
            case 'Omc/V Metals Pending Payments':
                targetUrl = 'function1/OMC_METAL_PENDING_PAYMENT.php';
                break;
            case 'Pending Shipment':
                targetUrl = 'function1/PENDING_SHIPMENT.php';
                break;
            case 'Courier Pending Report':
                targetUrl = 'function1/COURIER_PENDING.php';
                break;
            case 'Sales Contract Pending':
                targetUrl = 'function1/SALES_CONTRACT_PENDING.php';
                break;
            case 'Commission Report':
                targetUrl = 'function1/COMMISSION_REPORT.php';
                break;
            default:
                alert("Please select a valid report type.");
                return; // Exit if no report type is selected
        }

        // Make AJAX request to the dynamically selected PHP file
        $.ajax({
            url: targetUrl,
            method: 'POST',
            data: formData,
            success: function(response) {
                $(".output").html(response); // Display the filtered results
            },
            error: function() {
                alert("There was an error processing your request. Please try again.");
            }
        });
    });



    // Reset the form and clear results
    $(".reset-button").click(function() {
        $("input, select").val(''); // Clear all inputs
        $(".output").html(''); // Clear the displayed results
    });
});

</script>
<script>
   $(document).ready(function() {
      $(".export-exel-button").click(function() {
        // Get the content of the output div
        var outputContent = $("#searchResultsContainer").html();

        if (!outputContent) {
            alert("No data available to export.");
            return;
        }

        // Convert the HTML table or content to a worksheet
        var ws = XLSX.utils.table_to_sheet($("#searchResultsContainer")[0]);

        // Create a new workbook and append the worksheet
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Results");

        // Export the workbook to an Excel file
        XLSX.writeFile(wb, "Search_Results.xlsx");
    });
  });
</script>


</body>
</html>
