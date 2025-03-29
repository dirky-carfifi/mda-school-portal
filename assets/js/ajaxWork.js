// Edit Event
$(document).on('click', '.edit-event-btn', function () {
    console.log("data-id", $(this).data('id'));
    const eventId = $(this).data('id');
    console.log("eventEditForm triggered with ID:", eventId); // Debugging line
    $.ajax({
        url: "../admin/editEvent.php", // Fixed typo in the URL (editEventphp -> editEvent.php)
        method: "post",
        data: { record: eventId },
        success: function (data) {
            // Update the dashboard class with the returned data
            $('.dashboard').html(data);
        },
        error: function () {
            alert('Failed to load the event form. Please try again.');
        }
    });
});

// Delete Event
$(document).on('click', '.delete-event-btn', function () {
    const eventId = $(this).data('id');

    // Show confirmation dialog
    if (confirm("Are you sure you want to delete this event?")) {
        console.log("eventDeleteForm triggered with ID:", eventId); // Debugging line
        
        // Proceed with deletion
        $.ajax({
            url: "../admin/deleteEvent.php",
            method: "post",
            data: { record: eventId },
            success: function () {
                alert('Item successfully deleted');
                $('form').trigger('reset'); // Reset forms if needed
                showEvents(); // Refresh the event list
            },
            error: function () {
                alert('Failed to delete the event. Please try again.');
            }
        });
    } else {
        // Action canceled by the user
        console.log("Event deletion canceled for ID:", eventId); // Debugging line
    }
});

//update event
function updateEvent() {
    var event_id = $('#event_id').val();
    var title = $('#title').val();
    var description = $('#description').val();
    var event_date = $('#event_date').val();
    var location = $('#location').val();
    var important = $('#important').val(); // Get selected value from dropdown
    var upcoming = $('#upcoming').val(); // Get selected value from dropdown
    var newImage = $('#newImage')[0].files[0];
    var fd = new FormData();

    // Append form data
    fd.append('event_id', event_id);
    fd.append('title', title);
    fd.append('description', description);
    fd.append('event_date', event_date);
    fd.append('location', location);
    fd.append('important', important); // Append important value
    fd.append('upcoming', upcoming); // Append upcoming value
    if (newImage) {
        fd.append('newImage', newImage);
    } else {
        fd.append('existingImage', $('#existingImage').val()); // Use existing image if no new image is uploaded
    }
   
    $.ajax({
      url:'../admin/updateEvent.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(response){
        if (response === "true") {
            alert('Data Update Success.');
            $('form').trigger('reset');
            showEvents();
        } else {
            alert('Error updating event. ' + response);
        }
      }
    });
}

//add event
function addEvent() {
    var fd = new FormData($('#addEventForm')[0]); // Automatically includes all form fields

    // AJAX call
    $.ajax({
        url: '../admin/addEvent.php', // Adjust this URL as needed
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response === "true") {
                alert('Event added successfully!');
                // Explicitly hide the modal using Bootstrap's modal instance
                const modalInstance = $('#myModal').modal();
                modalInstance.modal('hide');
                $('#addEventForm')[0].reset(); // Reset the form
                showEvents();
            } else {
                alert('Error adding event: ' + response);
            }
        },
        error: function(err) {
            console.error('AJAX Error:', err);
            alert('Failed to add the event.');
        }
    });
}

//edit feedback
$(document).on('click', '.edit-feedback-btn', function() {
    const feedbackId = $(this).data('id');
    $.ajax({
        url: "../admin/editFeedback.php",
        method: "post",
        data: { record: feedbackId },
        success: function(data) {
            $('.dashboard').html(data);
        }
    });
});

//delete feedback
$(document).on('click', '.delete-feedback-btn', function() {
    const feedbackId = $(this).data('id');
    if (confirm("Are you sure you want to delete this feedback?")) {
        // Proceed with deletion if confirmed
        $.ajax({
            url: "../admin/deleteFeedback.php",
            method: "post",
            data: { record: feedbackId },
            success: function(data) {
                alert(data); // Display the response message (e.g., "Item Successfully deleted")
                $('form').trigger('reset');
                showFeedback(); // Refresh the feedback list
            },
            error: function(xhr, status, error) {
                alert("An error occurred: " + xhr.responseText); // Handle errors
            }
        });
    }
});

//update event
function updateFeedback() {
    var feedback_id = $('#feedback_id').val();
    var status = $('#status').val();
    var fd = new FormData();

    // Append form data
    fd.append('feedback_id', feedback_id);
    fd.append('status', status);
   
    $.ajax({
      url:'../admin/updateFeedback.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(response){
        if (response === "true") {
            alert('Data Update Success.');
            $('form').trigger('reset');
            showFeedback();
        } else {
            alert('Error updating feedback. ' + response);
        }
      }
    });
}

//showFeedback
function showFeedback() {
    $.ajax({
        url: 'viewAllFeedbacks.php',
        type: 'GET',
        success: function (response) {
            $('.dashboard').html(response);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching feedbacks:', error);
            alert('An error occurred while fetching feedbacks.');
        }
    });
}

//showEvents
function showEvents() {
    $.ajax({
        url: 'viewAllEvents.php',
        type: 'GET',
        success: function (response) {
            $('.dashboard').html(response);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            alert('An error occurred while fetching events.');
        }
    });
}

function filterEvents() {
    const searchInput = document.getElementById("eventSearch").value.toLowerCase();
    const rows = document.querySelectorAll("#eventsTable tbody tr");

    rows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(" ");
        row.style.display = rowText.includes(searchInput) ? "" : "none";
    });
}

function sortTable(columnIndex, direction) {
    const table = document.getElementById("eventsTable");
    const rows = Array.from(table.rows).slice(1); // Exclude header row
    const isAscending = direction === "asc" ? 1 : -1;

    // Sort rows based on columnIndex and direction
    rows.sort((a, b) => {
        const aText = a.cells[columnIndex].textContent.trim().toLowerCase();
        const bText = b.cells[columnIndex].textContent.trim().toLowerCase();

        // Compare numbers or strings
        if (!isNaN(aText) && !isNaN(bText)) {
            return isAscending * (parseFloat(aText) - parseFloat(bText));
        } else {
            return isAscending * (aText > bText ? 1 : -1);
        }
    });

    // Append sorted rows back to the table body
    rows.forEach(row => table.tBodies[0].appendChild(row));
}

//showEventParticipants
function showEventParticipants() {
    $.ajax({
        url: 'viewAllEventParticipants.php',
        type: 'GET',
        success: function (response) {
            $('.dashboard').html(response);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            alert('An error occurred while fetching events.');
        }
    });
}

// Toggle Advanced Search Visibility
function toggleSearch() {
    const advancedSearchFields = document.getElementById("advancedSearchFields");
    if (advancedSearchFields.style.display === "none" || advancedSearchFields.style.display === "") {
        advancedSearchFields.style.display = "block";
    } else {
        advancedSearchFields.style.display = "none";
    }
}

// Filter Participants (Normal and Advanced)
function filterParticipants() {
    const normalSearchInput = document.getElementById("participantSearchInput").value.trim().toLowerCase();

    // Advanced Search Inputs
    const participantName = document.getElementById("participantName").value.trim().toLowerCase();
    const participantEmail = document.getElementById("participantEmail").value.trim().toLowerCase();
    const eventTitle = document.getElementById("eventTitle").value.trim().toLowerCase();
    const groupName = document.getElementById("groupName").value.trim().toLowerCase();

    const table = document.getElementById("participantsTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        const row = rows[i];
        const participantID = row.cells[0].textContent.trim().toLowerCase();
        const name = row.cells[1].textContent.trim().toLowerCase();
        const email = row.cells[2].textContent.trim().toLowerCase();
        const event = row.cells[3].textContent.trim().toLowerCase();
        const group = row.cells[4].textContent.trim().toLowerCase();

        let isMatch = true;

        // Apply Normal Search
        if (normalSearchInput) {
            isMatch =
                participantID.includes(normalSearchInput) ||
                name.includes(normalSearchInput) ||
                email.includes(normalSearchInput) ||
                event.includes(normalSearchInput) ||
                group.includes(normalSearchInput);
        }

        // Apply Advanced Search (if enabled)
        if (participantName && !name.includes(participantName)) isMatch = false;
        if (participantEmail && !email.includes(participantEmail)) isMatch = false;
        if (eventTitle && !event.includes(eventTitle)) isMatch = false;
        if (groupName && !group.includes(groupName)) isMatch = false;

        // Show/Hide rows based on match
        row.style.display = isMatch ? "" : "none";
    }
}

// Clear All Filters
function clearFilters() {
    // Reset all input fields
    document.getElementById("participantSearchInput").value = "";
    document.getElementById("participantName").value = "";
    document.getElementById("participantEmail").value = "";
    document.getElementById("eventTitle").value = "";
    document.getElementById("groupName").value = "";

    // Reset table to show all rows
    const table = document.getElementById("participantsTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        rows[i].style.display = "";
    }
}

// Enable Editing for Email and Registration Status
$(document).on('click', '.edit-participant-btn', function () {
    const participantId = $(this).data('id');
    const row = $(`#participant-${participantId}`);
    const emailStatusCell = row.find('td:nth-child(7)');
    const regStatusCell = row.find('td:nth-child(8)');

    // Replace static text with dropdowns
    emailStatusCell.html(`
        <select class="email-status-dropdown">
            <option value="Delivered">Delivered</option>
            <option value="Pending">Pending</option>
        </select>
    `);

    regStatusCell.html(`
        <select class="reg-status-dropdown">
            <option value="Accept">Accept</option>
            <option value="Decline">Decline</option>
        </select>
    `);

    // Add Save and Cancel buttons in the Actions column
    const actionCell = row.find('td:last-child');
    actionCell.html(`
        <button class="btn btn-success save-participant-btn" style="height:40px" data-id="${participantId}">
            <i class="fa fa-check" aria-hidden="true"></i>
        </button>
        <button class="btn btn-secondary cancel-edit-btn" style="height:40px" data-id="${participantId}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    `);
});

// Save Updated Status
$(document).on('click', '.save-participant-btn', function () {
    const participantId = $(this).data('id');
    const emailStatus = $(`#participant-${participantId} .email-status-dropdown`).val();
    const regStatus = $(`#participant-${participantId} .reg-status-dropdown`).val();

    $.ajax({
        url: '../admin/updateParticipantStatus.php',
        method: 'POST',
        data: {
            id: participantId,
            email_status: emailStatus,
            registration_status: regStatus,
        },
        success: function (response) {
            alert('Participant status updated successfully.');

            // Replace dropdowns with static text
            const row = $(`#participant-${participantId}`);
            row.find('td:nth-child(7)').html(emailStatus);
            row.find('td:nth-child(8)').html(regStatus);

            // Restore action buttons
            row.find('td:last-child').html(`
                <button class="btn btn-primary edit-participant-btn" style="height:40px" data-id="${participantId}">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-danger delete-participant-btn" style="height:40px" data-id="${participantId}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            `);
        },
        error: function (error) {
            console.error('Error updating participant status:', error);
            alert('Failed to update participant status. Please try again.');
        },
    });
});

// Cancel Editing
$(document).on('click', '.cancel-edit-btn', function () {
    const participantId = $(this).data('id');
    const row = $(`#participant-${participantId}`);
    const emailStatus = row.find('td:nth-child(7)').data('value');
    const regStatus = row.find('td:nth-child(8)').data('value');

    // Restore original static text
    row.find('td:nth-child(7)').html(emailStatus);
    row.find('td:nth-child(8)').html(regStatus);

    // Restore action buttons
    row.find('td:last-child').html(`
        <button class="btn btn-primary edit-participant-btn" style="height:40px" data-id="${participantId}">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
        <button class="btn btn-danger delete-participant-btn" style="height:40px" data-id="${participantId}">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    `);
});

// Delete Participant
$(document).on('click', '.delete-participant-btn', function () {
    const participantId = $(this).data('id');
    if (confirm('Are you sure you want to delete this participant?')) {
        $.ajax({
            url: '../admin/deleteParticipant.php',
            method: 'POST',
            data: { id: participantId },
            success: function (response) {
                alert('Participant deleted successfully.');
                $(`#participant-${participantId}`).remove();
            },
            error: function (error) {
                console.error('Error deleting participant:', error);
                alert('Failed to delete participant. Please try again.');
            },
        });
    }
});

// Open Image Modal
$(document).on('click', '.view-id-btn', function () {
    const imagePath = $(this).data('image');
    const modal = $('#imageModal');
    $('#modalImage').attr('src', imagePath); // Set the image source
    modal.css('display', 'block'); // Show the modal
});

// Close Image Modal
function closeImageModal() {
    $('#imageModal').css('display', 'none'); // Hide the modal
    $('#modalImage').attr('src', ''); // Clear the image source
}

function filterEventList() {
    const normalSearchInput = document.getElementById("eventListSearchInput").value.trim().toLowerCase();

    // Advanced Search Inputs
    const eventName = document.getElementById("eventListName").value.trim().toLowerCase();
    const eventDate = document.getElementById("eventListDate").value.trim().toLowerCase();
    const eventLocation = document.getElementById("eventLocation").value.trim().toLowerCase();
    const important = document.getElementById("filterImportant").value; // Dropdown for Important
    const upcoming = document.getElementById("filterUpcoming").value; // Dropdown for Upcoming

    const table = document.getElementById("eventsTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        const row = rows[i];
        const id = row.cells[0].textContent.trim().toLowerCase();
        const title = row.cells[1].textContent.trim().toLowerCase();
        const date = row.cells[2].textContent.trim().toLowerCase();
        const description = row.cells[3].textContent.trim().toLowerCase();
        const location = row.cells[4].textContent.trim().toLowerCase();
        const isImportant = row.cells[6].textContent.trim().toLowerCase(); // "Yes" or "No"
        const isUpcoming = row.cells[7].textContent.trim().toLowerCase(); // "Yes" or "No"

        let isMatch = true;

        // Apply Normal Search
        if (normalSearchInput) {
            isMatch =
                id.includes(normalSearchInput) ||
                title.includes(normalSearchInput) ||
                date.includes(normalSearchInput) ||
                description.includes(normalSearchInput) ||
                location.includes(normalSearchInput) ||
                isImportant.includes(normalSearchInput) ||
                isUpcoming.includes(normalSearchInput);
        }

        // Apply Advanced Search (if enabled)
        if (eventName && !title.includes(eventName)) isMatch = false;
        if (eventDate && !date.includes(eventDate)) isMatch = false;
        if (eventLocation && !location.includes(eventLocation)) isMatch = false;
        if (important && (important === "1" ? isImportant !== "yes" : isImportant !== "no")) isMatch = false;
        if (upcoming && (upcoming === "1" ? isUpcoming !== "yes" : isUpcoming !== "no")) isMatch = false;

        // Show/Hide rows based on match
        row.style.display = isMatch ? "" : "none";
    }
}

function toggleEventSearch() {
    const advancedSearch = document.getElementById("advancedSearchFields");
    advancedSearch.style.display = advancedSearch.style.display === "none" ? "block" : "none";
}

function clearEventFilters() {
    document.getElementById("eventListName").value = "";
    document.getElementById("eventListDate").value = "";
    document.getElementById("eventLocation").value = "";
    document.getElementById("filterImportant").value = "";
    document.getElementById("filterUpcoming").value = "";
    filtereventList();
}

// Open Image Modal
$(document).on('click', '.view-event-img', function () {
    const imagePath = $(this).data('image');
    $('#eventModalImage').attr('src', imagePath); // Set the image source
    $('#eventImgModal').modal('show'); // Open the Bootstrap modal
});

// Close Image Modal (optional, since Bootstrap handles it automatically)
function closeImageModal() {
    $('#eventImgModal').modal('hide'); // Close the Bootstrap modal
    $('#eventModalImage').attr('src', ''); // Clear the image source
}

// Toggle Advanced Search Panel
function toggleFeedbackSearch() {
    const advancedSearch = document.getElementById("advancedSearchFields");
    advancedSearch.style.display =
        advancedSearch.style.display === "none" ? "block" : "none";
}

// Filter Feedback List
// Filter Feedback List
function filterFeedbackList() {
    const normalSearchInput = document.getElementById("feedbackListSearchInput").value.trim().toLowerCase();

    // Advanced Search Inputs
    const feedbackFrom = document.getElementById("feedbackListName").value.trim().toLowerCase();
    const feedbackEmail = document.getElementById("feedbackListEmail").value.trim().toLowerCase();
    const feedbackCategory = document.getElementById("filterCategory").value.trim().toLowerCase();
    const feedbackDate = document.getElementById("feedbackListDate").value.trim().toLowerCase();
    const feedbackStatus = document.getElementById("filterFeedbackStatus").value.trim().toLowerCase();

    const table = document.getElementById("feedbacksTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        const row = rows[i];
        const name = row.cells[1].textContent.trim().toLowerCase();
        const email = row.cells[2].textContent.trim().toLowerCase();
        const category = row.cells[3].textContent.trim().toLowerCase();
        const feedback = row.cells[4].textContent.trim().toLowerCase();
        const dateSubmitted = row.cells[5].textContent.trim().toLowerCase();
        const status = row.cells[6].textContent.trim().toLowerCase();

        let isMatch = true;

        // Apply Normal Search
        if (normalSearchInput) {
            isMatch =
                name.includes(normalSearchInput) ||
                email.includes(normalSearchInput) ||
                category.includes(normalSearchInput) ||
                feedback.includes(normalSearchInput) ||
                dateSubmitted.includes(normalSearchInput) ||
                status.includes(normalSearchInput);
        }

        // Apply Advanced Search
        if (feedbackFrom && !name.includes(feedbackFrom)) isMatch = false;
        if (feedbackEmail && !email.includes(feedbackEmail)) isMatch = false;
        if (feedbackCategory && !category.includes(feedbackCategory)) isMatch = false;
        if (feedbackDate && !dateSubmitted.includes(feedbackDate)) isMatch = false;
        if (feedbackStatus && !status.includes(feedbackStatus)) isMatch = false;

        // Show/Hide rows based on match
        row.style.display = isMatch ? "" : "none";
    }
}

// Clear Filters
function clearFeedbackFilters() {
    // Clear Normal Search Input
    document.getElementById("feedbackListSearchInput").value = "";

    // Clear Advanced Search Inputs
    document.getElementById("feedbackListName").value = "";
    document.getElementById("feedbackListEmail").value = "";
    document.getElementById("filterCategory").value = "";
    document.getElementById("feedbackListDate").value = "";
    document.getElementById("filterFeedbackStatus").value = "";
    filterFeedbackList();
}