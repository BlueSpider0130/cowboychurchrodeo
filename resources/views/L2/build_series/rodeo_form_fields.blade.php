                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="name"> Name </label>
                                <x-form.input type="text" id="name" name="name" value="{{ isset($rodeo) ? $rodeo->name : '' }}" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="optional"> Description </label>
                                <x-form.textarea  id="description" name="description" value="{{ isset($rodeo) ? $rodeo->description : '' }}" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <label for="start_date"> Start </label>
                                <x-form.input type="date" id="start_date" name="start_date" :value="isset($rodeo) ? $rodeo->starts_at : null" />
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="end_date"> End </label>
                                <x-form.input type="date" id="end_date" name="end_date" :value="isset($rodeo) ? $rodeo->ends_at : null" />                        
                            </div>
                        </div>
      
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="open_time"> Registration opens at </label>
                                <x-form.input type="datetime-local" id="open_time" name="open_time" :value="isset($rodeo) ? $rodeo->opens_at : null"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="close_time"> Registration closes at </label>
                                <x-form.input type="datetime-local" id="close_time" name="close_time" :value="isset($rodeo) ? $rodeo->closes_at : null"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">                                
                                <label for="office_fee" class="optional"> Office fee </label>
                                <div class="input-group {{ $errors->has('office_fee') ? 'is-invalid' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="office_fee"
                                        value="{{ old('office_fee', (isset($rodeo) ? $rodeo->office_fee : '' )) }}"
                                        id="office_fee"
                                        class="form-control {{ $errors->has('office_fee') ? 'is-invalid' : '' }}" 
                                    >
                                </div>
                                <x-form.error name="office_fee" />
                            </div>
                        </div>