<div class="eventList-section">
    <div class="header-container">
        <h2>Events List</h2>
        <button type="button" class="eventList-btn-add float-right" data-toggle="modal" data-target="#myModal">
            Add Event
        </button>
    </div>

    <!-- Combined Search -->
    <div class="eventList-search-bar">
        <input type="text" id="eventListSearchInput" class="eventList-input" placeholder="Search Events, Date, or Location">
        <button id="eventListearchButton" class="eventList-btn-primary" onclick="filterEventList()">
            <i class="fa fa-search"></i> Search
        </button>
        
    </div>

    <!-- Advanced Search Panel -->
    <div id="advancedSearchFields" class="eventList-advanced-search" style="display: none;">
        <div class="eventList-filter-row">
            <div class="eventList-filter-group">
                <label for="eventListName">Event:</label>
                <input type="text" id="eventListName" class="eventList-input" placeholder="Name">
            </div>
            <div class="eventList-filter-group">
                <label for="eventListDate">Date:</label>
                <input type="date" id="eventListDate" class="eventList-input">
            </div>
            <div class="eventList-filter-group">
                <label for="eventLocation">Location:</label>
                <input type="text" id="eventLocation" class="eventList-input" placeholder="Location">
            </div>
            <div class="eventList-filter-group">
                <label for="filterImportant">Important:</label>
                <select id="filterImportant" class="eventList-input">
                    <option value="">All</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="eventList-filter-group">
                <label for="filterUpcoming">Upcoming:</label>
                <select id="filterUpcoming" class="eventList-input">
                    <option value="">All</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
        <div class="eventList-filter-actions">
            <button id="applyFilters" class="eventList-btn-success" onclick="filterEventList()">Search</button>
            <button id="clearFiltersButton" class="eventList-btn-warning" onclick="clearEventFilters()">Clear All Filters</button>
        </div>
    </div>
</div>

<!-- Events Table -->
<table class="table events-table" id="eventsTable">
    <thead>
        <tr>
        <th class="text-center event-header">ID</th>
            <th class="text-center event-header">Title</th>
            <th class="text-center event-header">Date & Time</th>
            <th class="text-center event-header">Description</th>
            <th class="text-center event-header">Location</th>
            <th class="text-center event-header">Image</th>
            <th class="text-center event-header">Important</th>
            <th class="text-center event-header">Upcoming</th>
            <th class="text-center event-header" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include_once "../config/dbconnect.php";
        $sql = "SELECT * FROM event_list";
        $result = $conn->query($sql);
        $count = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            $imagePath = '..' . $row['image'];
        ?>
            <tr>
                <td><?=$count?></td>
                <td><?=$row["title"]?></td>
                <td><?=$row["event_date"]?></td>
                <td><?=$row["description"]?></td>
                <td><?=$row["location"]?></td>
                <td>
                    <button class="btn btn-link view-event-img" data-image="<?=htmlspecialchars($imagePath)?>">
                        View Image
                    </button>
                </td>
                <td><?=$row["important"] ? 'Yes' : 'No'?></td>
                <td><?=$row["upcoming"] ? 'Yes' : 'No'?></td>
                <td>
                    <button class="btn btn-primary edit-event-btn" style="height:40px" data-id="<?=$row['id']?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger delete-event-btn"  style="height:40px" data-id="<?=$row['id']?>">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button></td>
            </tr>
        <?php
            $count++;
            }
        }
        ?>
    </tbody>
</table>

<!-- Add Event Modal -->
<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Event</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addEventForm" enctype="multipart/form-data">
                    <!-- Event Form Fields -->
                    <div class="form-group">
                        <label for="event_name">Event Name:</label>
                        <input type="text" class="form-control" id="event_name" name="event_name" required>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Event Date:</label>
                        <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="important">Important:</label>
                        <select id="important" name="important" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="upcoming">Upcoming:</label>
                        <select id="upcoming" name="upcoming" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Choose Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" onclick="addEvent()" style="height:40px">Add Event</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="eventImgModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Event Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="eventModalImage" src="" alt="Event Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>