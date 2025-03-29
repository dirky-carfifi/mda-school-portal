// Event Handling
let events = []; // Initialize an empty array for events

// Fetch events data from the server
function loadEvents() {
    console.log("Fetching events...");

    $.ajax({
        url: 'fetchEvents.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log("Fetched Data:", data);
            events = data.map(event => ({
                title: event.title,
                event_date: formatEventDate(event.event_date),
                desc: event.description,
                location: event.location,
                image: event.image,
                month: getMonthName(event.event_date),
                day: getDayNumber(event.event_date),
                important: event.important,
                upcoming: event.upcoming,
            }));
            updateEventList(); // Render events after fetching
        },
        error: function (err) {
            console.error("Failed to load events:", err);
            alert("Failed to load events. Please try again later.");
        }
    });    
}

// Format date
function formatEventDate(dateString) {
    const options = { year: "numeric", month: "long", day: "numeric", hour: "2-digit", minute: "2-digit" };
    return new Date(dateString).toLocaleDateString("en-US", options);
}

// Extract month
function getMonthName(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString("default", { month: "short" }); // Returns "Jan", "Feb", etc.
}

// Extract day
function getDayNumber(dateString) {
    const date = new Date(dateString);
    return date.getDate(); // Returns 1, 2, ..., 31
}

// Filters
let filterSearch = "";

// Update the event list in the UI
function updateEventList() {
    const eventList = document.getElementById("eventList");

    // Ensure the container exists
    if (!eventList) {
        console.error("Event list container not found.");
        return;
    }

    // Clear current events
    eventList.innerHTML = "";

    // Log the events for debugging
    console.log("Rendering events:", events);

    // Apply filters based on selected filter values
    const filteredEventsByCategory = events.filter(event => {
        if (filterImportant && event.important === 1) {
            return true; // Show only important events
        }
        if (filterUpcoming && event.upcoming === 1) {
            return true; // Show only upcoming events
        }
        if (!filterImportant && !filterUpcoming && event.important === 0 && event.upcoming === 0) {
            return true; // Show finished events
        }
        return false; // Exclude events that don't match any category
    });
      // Step 2: Apply search filter
      const filteredEvents = filteredEventsByCategory.filter(event => {
        const searchValue = filterSearch.toLowerCase();
        return (
            event.title.toLowerCase().includes(searchValue) ||
            event.desc.toLowerCase().includes(searchValue) ||
            event.location.toLowerCase().includes(searchValue)
        );
    });
    console.log('filter', filteredEvents);

     // Render each filtered event as a <li>
     filteredEvents.forEach(event => {
        const eventItem = document.createElement("li");
        eventItem.className = "event-card";
        eventItem.innerHTML = `
            <div class="date-ribbon">${event.month} <br> ${event.day}</div>
            <img class="event-img" src="${event.image}" alt="${event.title}">
            <div>
                <p class="name"><strong>${event.title}</strong></p>
                <p class="date">${event.event_date}</p>
                <p class="desc">${event.desc}</p>
                <p class="location">${event.location}</p>
                ${
                    (!event.upcoming && !event.important) ? '' :
                    `<button class="register-btn" onclick="openRegistrationForm('${event.title}')">Register</button>`
                }
            </div>
        `;
        eventList.appendChild(eventItem);
    });    

    if (filteredEvents.length === 0) {
        eventList.innerHTML = `<li>No events found.</li>`;
    }
}

// Handle filter radio buttons
document.querySelectorAll('input[name="filter"]').forEach(radio => {
    radio.addEventListener("change", event => {
        const value = event.target.value;
        if (value === "important") {
            filterUpcoming = false; // Do not show upcoming events
            filterImportant = true; // Show only important events
        } else if (value === "upcoming") {
            filterUpcoming = true; // Show only upcoming events
            filterImportant = false; // Exclude important-only events
        } else if (value === "finished") {
            filterUpcoming = false; // Exclude upcoming events
            filterImportant = false; // Exclude important events
        }
        updateEventList(); // Re-render the filtered list
    });
});

// Open registration form modal
function openRegistrationForm(eventTitle) {
    const formModal = document.getElementById("registrationModal");
    formModal.style.display = "block"; // Show modal
    document.getElementById("eventTitle").value = eventTitle; // Set event title in the form
}

// Close registration form modal
function closeRegistrationForm() {
    const formModal = document.getElementById("registrationModal");
    formModal.style.display = "none"; // Hide modal
}

// Handle form submi ssion
function submitRegistrationForm(event) {
    event.preventDefault();

    const form = document.getElementById("registrationForm");
    const formData = new FormData(form);

    fetch("admin/registerEvent.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert("Registration successful!");
        closeRegistrationForm();
        console.log("Server response:", data);
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to register. Please try again.");
    });
}

// Handle search input
document.getElementById("searchInput").addEventListener("input", event => {
    filterSearch = event.target.value;
    updateEventList();
});


function toggleGroupIndividualFields() {
    const participantType = document.getElementById("participantType").value;
    const groupField = document.getElementById("groupField");
    const individualFields = document.getElementById("individualFields");
    const groupMemberFields = document.getElementById("groupMemberFields");
    const nameField = document.getElementById("name");
    const emailField = document.getElementById("email");
    const profileImageField = document.getElementById("profileImage");
    const groupNameField = document.getElementById("groupName");

    if (participantType === "group") {
        groupField.style.display = "block";
        groupNameField.required = true;

        individualFields.style.display = "none";
        nameField.required = false;
        emailField.required = false;
        profileImageField.required = false;

        groupMemberFields.style.display = "block";
        if (document.getElementById("memberContainer").childElementCount === 0) {
            addGroupMemberField();
        }
    } else if (participantType === "individual") {
        groupField.style.display = "none";
        groupNameField.required = false;

        individualFields.style.display = "block";
        nameField.required = true;
        emailField.required = true;
        profileImageField.required = true;

        groupMemberFields.style.display = "none";
        clearGroupMemberFields();
    } else {
        groupField.style.display = "none";
        groupNameField.required = false;

        individualFields.style.display = "none";
        nameField.required = false;
        emailField.required = false;
        profileImageField.required = false;

        groupMemberFields.style.display = "none";
        clearGroupMemberFields();
    }
}

function addGroupMemberField() {
    const memberContainer = document.getElementById("memberContainer");

    const memberDiv = document.createElement("div");
    memberDiv.classList.add("group-member");
    memberDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <input type="text" name="memberName[]" placeholder="Member Name" required style="flex: 1;">
            <input type="email" name="memberEmail[]" placeholder="Member Email" required style="flex: 1;">
            <label>Upload ID:</label>
            <input type="file" name="memberProfileImage[]" accept="image/*" required style="flex: 1;">
            <button type="button" onclick="removeGroupMemberField(this)" style="background-color: red; color: white; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;">Remove</button>
        </div>
    `;
    memberContainer.appendChild(memberDiv);
}

function removeGroupMemberField(button) {
    button.parentElement.parentElement.remove();
}

function clearGroupMemberFields() {
    const memberContainer = document.getElementById("memberContainer");
    memberContainer.innerHTML = ""; // Clear all group member fields
}

document.addEventListener("DOMContentLoaded", () => {
    // Set the "Important" filter as default
    filterImportant = true;
    filterUpcoming = false; // Ensure other filters are off by default

    // Set the default selected radio button
    document.querySelector('input[name="filter"][value="important"]').checked = true;

    // Load and render events
    updateEventList(); // Render default "Important" list
    loadEvents(); // Fetch events and re-render the list
});
s