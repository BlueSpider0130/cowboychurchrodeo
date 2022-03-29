    <div>
        <div class="form-group row">
            <label for="name-input" class="col-sm-2 col-form-label">
                Name 
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="name" :value="isset($organization) ? $organization->name : null" id="name-input" />
            </div>
        </div>


        <div class="form-group row">
            <label for="address-1-input" class="col-sm-2 col-form-label">
                Address
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="address_line_1" :value="isset($organization) ? $organization->address_line_1 : null" id="address-1-input" />
            </div>
        </div>    


        <div class="form-group row">
            <label for="address-2-input" class="col-sm-2 col-form-label">
                <span class="d-none"> Address line 2 </span> 
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="address_line_2" :value="isset($organization) ? $organization->address_line_2 : null" id="address-2-input" />
            </div>
        </div>        
        

        <div class="form-group row">
            <label for="city-input" class="col-sm-2 col-form-label">
                City
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="city" :value="isset($organization) ? $organization->city : null" id="city-input" />
            </div>
        </div>   


        <div class="form-group row">
            <label for="state-input" class="col-sm-2 col-form-label">
                State
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="state" :value="isset($organization) ? $organization->state : null" id="state-input" />
            </div>
        </div>  


        <div class="form-group row">
            <label for="postcode-input" class="col-sm-2 col-form-label">
                Postcode
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="text" name="postcode" :value="isset($organization) ? $organization->postcode : null" id="postcode-input" />
            </div>
        </div>  


        <div class="form-group row">
            <label for="phone-input" class="col-sm-2 col-form-label">
                Phone
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="tel" name="phone" :value="isset($organization) ? $organization->phone : null" id="phone-input" />
            </div>
        </div>  


        <div class="form-group row">
            <label for="email-input" class="col-sm-2 col-form-label">
                Email
            </label>
            <div class="col-sm-10 col-md-6">
                <x-form.input type="email" name="email" :value="isset($organization) ? $organization->email : null" id="email-input" />
            </div>
        </div>  
    </div>