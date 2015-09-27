<style>
    .popup {
        width: 70%;
        left: 15%;
    }
</style>
<div id="modal" class="popup">
    <span id="modal-close" class="glyphicon glyphicon-remove" aria-hidden="true"></span>

<!-- Inputs for Start Time -->

    <div class="row">
        <div class="col-md-4">
            <label>
                Start
                <input id="start-day-picker" class="form-control" type="text">
            </label>
        </div>
        <div class="col-md-4">
            <label>
                <input id="start-all-day" type="checkbox"> All Day
            </label>
        </div>
    </div>
    <div id="start-time-options" class="row">
        <div class="col-md-3">
            <select id="start-hour" class="form-control">
                <option value="12">12</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="start-min" class="form-control">
                <option value="00">00</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="45">45</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="start-meridian" class="form-control">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
        </div>
    </div>

<!-- Options for End Time -->

    <div class="row">
        <div class="col-md-4">
            <label>
                End
                <input id="end-day-picker" class="form-control" type="text">
            </label>
        </div>
        <div class="col-md-4">
            <label>
                <input id="end-all-day" type="checkbox"> All Day
            </label>
        </div>
    </div>
    <div id="end-time-options" class="row">
        <div class="col-md-3">
            <select id="end-hour" class="form-control">
                <option value="12">12</option>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="end-min" class="form-control">
                <option value="00">00</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="45">45</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="end-meridian" class="form-control">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
        </div>
    </div>

    <hr>
    <div id="modal-buttons">
        <div class="row">
            <div class="col-md-12">
                <div id="modal-save" class="btn btn-primary">Save</div>
            </div>
        </div>
        <div class="row">
            <div id="modal-delete" class="col-md-12">
                <div class="btn btn-danger">Delete</div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#start-day-picker').datepicker({});
    $('#end-day-picker').datepicker({});
</script>