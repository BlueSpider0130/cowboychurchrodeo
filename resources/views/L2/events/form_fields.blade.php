
                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="name"> Name </label>
                        <x-form.input type="text" id="name" name="name" value="{{ isset($event) ? $event->name : '' }}" required />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">                
                        <label for="description" class="optional"> Description </label>
                        <x-form.textarea id="description" name="description" rows="3" value="{{ isset($event) ? $event->description : '' }}" />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">      
                        <div class="form-group">
                            <legend class="legend-reset"> Event type </legend>
                            <hr class="my-2">
                            <div class="form-check">
                                @php
                                    $checked = isset($event) && $event->team_roping ? true : false;
                                    $checked = old() ? old('team_roping') : $checked;
                                @endphp
                                <input class="form-check-input" type="checkbox" id="team_roping" name="team_roping" {{ $checked ? 'checked' : '' }}>
                                <label class="form-check-label" for="team_roping">
                                    Team roping
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">     
                        <div class="form-group">
                            <legend class="legend-reset"> Result type </legend>
                            <hr class="my-2">
                            @php
                                $selected = isset($event) ? $event->result_type : null;
                                $selected = old() ? old('result_type') : $selected;
                            @endphp    

                            <div class="form-check">                            
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="result_type" 
                                    id="result-type-score" 
                                    value="score"
                                    {{ 'score' == $selected ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="result-type-score">
                                    Score
                                </label>
                            </div>

                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="result_type" 
                                    id="result-type-time" 
                                    value="time"
                                    {{ 'time' == $selected ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="result-type-time">
                                    Time
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
