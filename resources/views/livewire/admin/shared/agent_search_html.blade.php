
    <div class="col-12 form-group my-2 hidden" wire:ignore  id="travelAgentContainer">
        <div class="input-group">
            <input
                id="agent_search"
                type="text"
                class="form-control contact-input"
                placeholder="Search Travel Agent"
                autocomplete="off"

            />
            <ul class="autocomplete-results list-group position-absolute w-100" style="padding-left: 0px; margin-top: 51px;z-index: 200; display: none;">
            </ul>
        </div>
    </div>
    @error('form.agent_id')
    <p style="color: red;">{{ $message }}</p>
    @enderror
