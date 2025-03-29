<div class="participants-section">
    <h2>Participants List</h2>

    <!-- Combined Search -->
    <div class="participants-search-bar">
        <input type="text" id="participantSearchInput" class="participants-input" placeholder="Search Participants, Email, or Event">
        <button id="participantSearchButton" class="participants-btn-primary" onclick="filterParticipants()">
            <i class="fa fa-search"></i> Search
        </button>
       
    </div>

    <!-- Advanced Search Panel -->
    <div id="advancedSearchFields" class="participants-advanced-search" style="display: none;">
        <div class="participants-filter-row">
            <div class="participants-filter-group">
                <label for="participantName">Participant Name:</label>
                <input type="text" id="participantName" class="participants-input" placeholder="Name">
            </div>
            <div class="participants-filter-group">
                <label for="participantEmail">Email:</label>
                <input type="text" id="participantEmail" class="participants-input" placeholder="Email">
            </div>
            <div class="participants-filter-group">
                <label for="eventTitle">Event Title:</label>
                <input type="text" id="eventTitle" class="participants-input" placeholder="Event Title">
            </div>
            <div class="participants-filter-group">
                <label for="groupName">Group Name:</label>
                <input type="text" id="groupName" class="participants-input" placeholder="Group Name">
            </div>
        </div>
        <div class="participants-filter-actions">
            <button id="applyFilters" class="participants-btn-success" onclick="filterParticipants()">Search</button>
            <button id="clearFiltersButton" class="participants-btn-warning" onclick="clearFilters()">Clear All Filters</button>
        </div>
    </div>
</div>

<!-- Participants Table -->
<table class="table participants-table" id="participantsTable">
    <thead>
        <tr>
            <th class="text-center event-header">ID</th>
            <th class="text-center event-header">Name</th>
            <th class="text-center event-header">Email</th>
            <th class="text-center event-header">Event Title</th>
            <th class="text-center event-header">Group Name</th>
            <th class="text-center event-header">Valid ID</th>
            <th class="text-center event-header">Email Status</th>
            <th class="text-center event-header">Registration</th>
            <th class="text-center event-header">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include_once "../config/dbconnect.php";
        $sql = "SELECT * FROM event_registrations";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = '..' . $row['profileImage'];
                $emailStatus = $row['email_status']; // Delivered or Pending
                $regStatus = $row['registration_status']; // Decline or Accept

                // Determine CSS classes for statuses
                $emailStatusClass = strtolower($emailStatus) === 'delivered' ? 'status-delivered' :
                                    (strtolower($emailStatus) === 'pending' ? 'status-pending' : 'status-declined');
                $regStatusClass = strtolower($regStatus) === 'accept' ? 'status-accepted' :
                                (strtolower($regStatus) === 'decline' ? 'status-declined' : 'status-pending');
        ?>
            <tr id="participant-<?=$row['id']?>">
                <td><?=$row["id"]?></td>
                <td><?=$row["name"]?></td>
                <td><?=$row["email"]?></td>
                <td><?=$row["eventTitle"]?></td>
                <td><?=$row["groupName"] ?? 'N/A'?></td>
                <td>
                    <button class="btn btn-link view-id-btn" data-image="<?=htmlspecialchars($imagePath)?>">
                        View ID
                    </button>
                </td>
                <td class="email-status <?=$emailStatusClass?>"><?=$emailStatus?></td>
                <td class="reg-status <?=$regStatusClass?>"><?=$regStatus?></td>
                <td>
                    <button class="btn btn-primary edit-participant-btn" style="height:40px" data-id="<?=$row['id']?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger delete-participant-btn" style="height:40px" data-id="<?=$row['id']?>">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<!-- Image Modal -->
<div id="imageModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeImageModal()" style="cursor: pointer; font-size: 24px; font-weight: bold;">&times;</span>
        <img id="modalImage" src="" alt="Valid ID" style="width: 100%; max-height: 500px; object-fit: contain;">
    </div>
</div>

<script>
    // Function to show the modal (for testing or use in your app)
    function showImageModal(imageSrc) {
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");

        modalImage.src = imageSrc;
        modal.style.display = "block";
    }

    // Function to close the modal
    function closeImageModal() {
        const modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }

    // Optional: Close the modal when clicking outside of the modal content
    window.onclick = function (event) {
        const modal = document.getElementById("imageModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>

