
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="name"> Name </label>
                                <x-form.input type="text" id="name" name="name" value="{{ isset($series) ? $series->name : '' }}" required />
                            </div>
                        </div><!--/row-->


                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="optional"> Description </label>
                                <x-form.textarea  id="description" name="description" value="{{ isset($series) ? $series->description : '' }}" />
                            </div>
                        </div><!--/row-->


                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <label for="start_date"> Start </label>
                                <x-form.input type="date" id="start_date" name="start_date" value="{{ isset($series) && $series->starts_at ? $series->starts_at->format('Y-m-d') : '' }}" />
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="end_date"> End </label>
                                <x-form.input type="date" id="end_date" name="end_date" value="{{ isset($series) && $series->ends_at ? $series->ends_at->format('Y-m-d') : '' }}" />                        
                            </div>
                        </div><!--/row-->


                        <div class="row mt-4">
                            <div class="col-12">                                
                                <label for="membership_fee" class="optional"> Membership fee </label>
                                

                                <div class="input-group {{ $errors->has('membership_fee') ? 'is-invalid' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="membership_fee"
                                        value="{{ old('membership_fee', (isset($series) ? $series->membership_fee : '')) }}"
                                        id="membership_fee"
                                        class="form-control {{ $errors->has('membership_fee') ? 'is-invalid' : '' }}" 
                                    >
                                </div>
                                <x-form.error name="membership_fee" />
                            </div>
                        </div><!--/row-->    
