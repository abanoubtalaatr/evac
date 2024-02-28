<style>
    .autocomplete-results {
        z-index: 200;
        display: none;
    }
    .autocomplete-results li.selected {
        background-color: #007bff;
        color: #fff;
    }
</style>

<div class="form-group col-12">
    <div class="input-group">
        <input
            id="agent_search"
            type="text"
            class="form-control contact-input"
            placeholder="Search Travel Agent"
            autocomplete="off"
            autofocus
        />
        <ul id="list" class="autocomplete-results list-group position-absolute w-100" style="padding-left: 0px; margin-top: 51px; z-index: 200; display: none;"></ul>
    </div>
</div>
