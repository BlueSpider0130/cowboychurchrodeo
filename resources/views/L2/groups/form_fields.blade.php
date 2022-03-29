
                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="name"> Name </label>
                        <x-form.input type="text" id="name" name="name" value="{{ isset($group) ? $group->name : '' }}" required />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6 mb-3">                
                        <label for="description" class="optional"> Description </label>
                        <x-form.textarea id="description" name="description" rows="3" value="{{ isset($group) ? $group->description : '' }}" />
                    </div>
                </div>
