<div class="container p-5">

<h4>Edit Event Details</h4>
<?php
    include_once "../config/dbconnect.php";
    $ID = $_POST['record'];
    $qry = mysqli_query($conn, "SELECT * FROM event_list WHERE id='$ID'");
    $numberOfRow = mysqli_num_rows($qry);
    if ($numberOfRow > 0) {
        while ($row1 = mysqli_fetch_array($qry)) {
            $imagePath = '..' . $row1["image"];
?>
<form id="update-Event" onsubmit="updateEvent()" enctype='multipart/form-data'>
    <div class="formz-group">
        <input type="text" class="form-control" id="event_id" value="<?=$row1['id']?>" hidden>
    </div>
    <div class="form-group">
        <label for="title">Event Name:</label>
        <input type="text" class="form-control" id="title" value="<?=$row1['title']?>">
    </div>
    <div class="form-group">
        <label for="desc">Event Description:</label>
        <textarea class="form-control" id="description"><?=$row1['description']?></textarea>
    </div>
    <div class="form-group">
        <label for="event_date">Date and Time:</label>
        <input type="datetime-local" class="form-control" id="event_date" value="<?=date('Y-m-d\TH:i', strtotime($row1['event_date']))?>">
    </div>
    <div class="form-group">
        <label for="location">Event Location:</label>
        <input type="text" class="form-control" id="location" value="<?=$row1['location']?>">
    </div>
    <div class="form-group">
        <label>Important:</label>
        <select id="important" class="form-control">
            <option value="1" <?=$row1['important'] ? 'selected' : ''?>>Yes</option>
            <option value="0" <?=!$row1['important'] ? 'selected' : ''?>>No</option>
        </select>
    </div>
    <div class="form-group">
        <label>Upcoming:</label>
        <select id="upcoming" class="form-control">
            <option value="1" <?=$row1['upcoming'] ? 'selected' : ''?>>Yes</option>
            <option value="0" <?=!$row1['upcoming'] ? 'selected' : ''?>>No</option>
        </select>
    </div>
    <div class="form-group">
        <img width='200px' height='150px' src='<?=$imagePath?>'>
        <div>
            <label for="file">Choose Image:</label>
            <input type="text" id="existingImage" class="form-control" value="<?=$imagePath?>" hidden>
            <input type="file" id="newImage" value="">
        </div>
    </div>
    <div class="form-group">
        <!-- Submit Button -->
        <button type="submit" style="height:40px" class="btn btn-primary">Update Event</button>
        <!-- Cancel Button -->
        <button type="button" class="btn btn-primary-cancel" style="height:40px" onclick="showEvents()">Cancel</button>
    </div>
<?php
        }
    }
?>
</form>

</div>
